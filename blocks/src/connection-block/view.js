/**
 * Use this file for JavaScript code that you want to run in the front-end 
 * on posts/pages that contain this block.
 *
 * When this file is defined as the value of the `viewScript` property
 * in `block.json` it will be enqueued on the front end of the site.
 *
 * Example:
 *
 * ```js
 * {
 *   "viewScript": "file:./view.js"
 * }
 * ```
 *
 * If you're not making any changes to this file because your project doesn't need any 
 * JavaScript running in the front-end, then you should delete this file and remove 
 * the `viewScript` property from `block.json`. 
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-metadata/#view-script
 */
 
import { render } from '../utils';
import { __ } from '@wordpress/i18n';
import { useEffect, useState, useRef } from '@wordpress/element';

import { useSelect, useDispatch, use } from '@wordpress/data';

import apiFetch from '@wordpress/api-fetch';

// reCAPTCHA component
const ReCaptcha = ({ onVerify, onExpired, onError, uniqueId, siteKey }) => {
    const captchaRef = useRef(null);
    const [captchaId, setCaptchaId] = useState(null);
    const [isLoaded, setIsLoaded] = useState(false);
    const isLoadingRef = useRef(false);
    
    useEffect(() => {
        const loadRecaptcha = () => {
            if (window.grecaptcha && window.grecaptcha.render && captchaRef.current && !isLoaded && !isLoadingRef.current && siteKey) {
                isLoadingRef.current = true;
                try {
                    // Create a unique div for this captcha instance
                    const captchaDiv = document.createElement('div');
                    captchaDiv.id = `recaptcha-${uniqueId}-${Date.now()}`;
                    
                    // Clear any existing content
                    captchaRef.current.innerHTML = '';
                    captchaRef.current.appendChild(captchaDiv);
                    
                    const newCaptchaId = window.grecaptcha.render(captchaDiv, {
                        sitekey: siteKey,
                        callback: onVerify,
                        'expired-callback': onExpired,
                        'error-callback': onError,
                    });
                    setCaptchaId(newCaptchaId);
                    setIsLoaded(true);
                } catch (e) {
                    console.log('Error rendering captcha:', e);
                    // Clear and reset on error
                    if (captchaRef.current) {
                        captchaRef.current.innerHTML = '';
                    }
                    setIsLoaded(false);
                    setCaptchaId(null);
                } finally {
                    isLoadingRef.current = false;
                }
            }
        };

        // Only load if not already loaded and siteKey is provided
        if (!isLoaded && !isLoadingRef.current && siteKey) {
            if (window.grecaptcha && window.grecaptcha.render) {
                loadRecaptcha();
            } else {
                const existingScript = document.querySelector('script[src="https://www.google.com/recaptcha/api.js"]');
                if (!existingScript) {
                    const script = document.createElement('script');
                    script.src = 'https://www.google.com/recaptcha/api.js';
                    script.async = true;
                    script.defer = true;
                    script.onload = loadRecaptcha;
                    document.head.appendChild(script);
                } else {
                    const checkInterval = setInterval(() => {
                        if (window.grecaptcha && window.grecaptcha.render) {
                            clearInterval(checkInterval);
                            loadRecaptcha();
                        }
                    }, 100);
                    
                    setTimeout(() => clearInterval(checkInterval), 10000);
                }
            }
        }

        // Cleanup function
        return () => {
            if (captchaRef.current) {
                captchaRef.current.innerHTML = '';
            }
            setIsLoaded(false);
            setCaptchaId(null);
            isLoadingRef.current = false;
        };
    }, [uniqueId, siteKey]); // Added siteKey dependency

    // Don't render if no site key provided
    if (!siteKey) {
        return null;
    }

    return <div ref={captchaRef} className="recaptcha-container"></div>;
};

const ConnectionBlock = ({ accountUrl, useRecaptcha, reCaptchaPublicKey }) => {
    const [isLogin, setIsLogin] = useState(true);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');
    const [showForgotPassword, setShowForgotPassword] = useState(false);
    const [forgotPasswordEmail, setForgotPasswordEmail] = useState('');
    const [forgotPasswordMessage, setForgotPasswordMessage] = useState('');
    
    // Login form states
    const [loginCredentials, setLoginCredentials] = useState({
        username: '',
        password: '',
        rememberMe: false,
    });
    
    // Register form states
    const [registerInfo, setRegisterInfo] = useState({
        username: '',
        email: '',
        password: '',
        confirmPassword: '',
        agreeTerms: false,
    });

    // reCAPTCHA states
    const [recaptchaToken, setRecaptchaToken] = useState('');
    const [recaptchaError, setRecaptchaError] = useState('');

    // Add a counter to force unique keys
    const [formKey, setFormKey] = useState(0);

    const handleLoginChange = (e) => {
        const { name, value, type, checked } = e.target;
        setLoginCredentials({
            ...loginCredentials,
            [name]: type === 'checkbox' ? checked : value,
        });
    };

    const handleRegisterChange = (e) => {
        const { name, value, type, checked } = e.target;
        setRegisterInfo({
            ...registerInfo,
            [name]: type === 'checkbox' ? checked : value,
        });
    };

    // Function to redirect to my-account page
    const redirectToMyAccount = () => {
        window.location.href = accountUrl || '/my-account'; // Use the accountUrl prop if available
    };

    // reCAPTCHA handlers
    const handleRecaptchaVerify = (token) => {
        setRecaptchaToken(token);
        setRecaptchaError('');
    };

    const handleRecaptchaExpired = () => {
        setRecaptchaToken('');
        setRecaptchaError(__('[WPS-AUTH-012] reCAPTCHA expired. Please verify again.', 'wpshop'));
    };

    const handleRecaptchaError = () => {
        setRecaptchaToken('');
        setRecaptchaError(__('[WPS-AUTH-004] reCAPTCHA error. Please try again.', 'wpshop'));
    };

    const resetRecaptcha = () => {
        if (window.grecaptcha && window.grecaptcha.getResponse) {
            try {
                // Check if there's an active captcha before resetting
                const response = window.grecaptcha.getResponse();
                if (response !== undefined) {
                    window.grecaptcha.reset();
                }
            } catch (e) {
                // Silently handle the error
            }
        }
        setRecaptchaToken('');
        setRecaptchaError('');
    };

    const handleLoginSubmit = (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError('');
        
        if (useRecaptcha && !recaptchaToken) {
            setError(__('[WPS-AUTH-005] Captcha response is required.', 'wpshop'));
            setIsLoading(false);
            return;
        }
        
        const requestData = {
            username: loginCredentials.username,
            password: loginCredentials.password,
        };
        
        if (useRecaptcha) {
            requestData.recaptcha_token = recaptchaToken;
        }
        
        apiFetch({
            path: 'wp-shop/v1/login',
            method: 'POST',
            data: requestData,
        }).then((response) => {
            // Redirect to my-account page on successful login
            redirectToMyAccount();
        }).catch((error) => {
            setError(error.message || __('[WPS-AUTH-001] Login failed. Please check your credentials.', 'wpshop'));
            if (useRecaptcha) {
                resetRecaptcha();
            }
        }).finally(() => {
            setIsLoading(false);
        });
    };

    const handleRegisterSubmit = (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError('');
        
        // Validation
        if (registerInfo.password !== registerInfo.confirmPassword) {
            setError(__('[WPS-AUTH-013] Passwords do not match.', 'wpshop'));
            setIsLoading(false);
            return;
        }
        
        if (!registerInfo.agreeTerms) {
            setError(__('[WPS-AUTH-014] You must agree to the terms and conditions.', 'wpshop'));
            setIsLoading(false);
            return;
        }
        
        if (useRecaptcha && !recaptchaToken) {
            setError(__('[WPS-AUTH-005] Captcha response is required.', 'wpshop'));
            setIsLoading(false);
            return;
        }
        
        const requestData = {
            username: registerInfo.username,
            email: registerInfo.email,
            password: registerInfo.password,
        };
        
        if (useRecaptcha) {
            requestData.recaptcha_token = recaptchaToken;
        }
        
        apiFetch({
            path: 'wp-shop/v1/register',
            method: 'POST',
            data: requestData,
        }).then((response) => {
            // Reset form data
            setRegisterInfo({
                username: '',
                email: '',
                password: '',
                confirmPassword: '',
                agreeTerms: false,
            });
            // Redirect to my-account page on successful registration
            redirectToMyAccount();
        }).catch((error) => {
            setError(error.message || __('[WPS-AUTH-002] Registration failed. Please try again.', 'wpshop'));
            if (useRecaptcha) {
                resetRecaptcha();
            }
        }).finally(() => {
            setIsLoading(false);
        });
    };

    const handleForgotPasswordSubmit = (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError('');
        setForgotPasswordMessage('');

        if (useRecaptcha && !recaptchaToken) {
            setError(__('[WPS-AUTH-005] Captcha response is required.', 'wpshop'));
            setIsLoading(false);
            return;
        }

        const requestData = {
            email: forgotPasswordEmail,
        };
        
        if (useRecaptcha) {
            requestData.recaptcha_token = recaptchaToken;
        }

        apiFetch({
            path: 'wp-shop/v1/forgot-password',
            method: 'POST',
            data: requestData,
        }).then((response) => {
            setForgotPasswordMessage(__('If this email exists in our system, you will receive a password reset link.', 'wpshop'));
            setForgotPasswordEmail('');
            if (useRecaptcha) {
                resetRecaptcha();
            }
        }).catch((error) => {
            setError(error.message || __('[WPS-AUTH-003] Failed to send reset email. Please try again.', 'wpshop'));
            if (useRecaptcha) {
                resetRecaptcha();
            }
        }).finally(() => {
            setIsLoading(false);
        });
    };

    const handleBackToLogin = () => {
        setShowForgotPassword(false);
        setForgotPasswordMessage('');
        setForgotPasswordEmail('');
        setError('');
        if (useRecaptcha) {
            resetRecaptcha();
        }
    };

    // Effect to reset reCAPTCHA when switching between forms
    useEffect(() => {
        // Clear token and error when switching forms
        if (useRecaptcha) {
            setRecaptchaToken('');
            setRecaptchaError('');
            // Force re-render of captcha with new key
            setFormKey(prev => prev + 1);
        }
    }, [isLogin, showForgotPassword, useRecaptcha]);

    return (
        <div className="wp-block-wpshop-connection-block">
            <div className="connection-container">
                <div className="connection-header">
                    <div className="connection-tabs">
                        <button 
                            className={`connection-tab ${isLogin ? 'active' : ''}`}
                            disabled={showForgotPassword}
                            onClick={() => {
                                if (!showForgotPassword) {
                                    setIsLogin(true);
                                    setError('');
                                    setRecaptchaError('');
                                    setRecaptchaToken('');
                                }
                            }}
                        >
                            {__('Login', 'wpshop')}
                        </button>
                        <button 
                            className={`connection-tab ${!isLogin ? 'active' : ''}`}
                            disabled={showForgotPassword}
                            onClick={() => {
                                if (!showForgotPassword) {
                                    setIsLogin(false);
                                    setError('');
                                    setRecaptchaError('');
                                    setRecaptchaToken('');
                                }
                            }}
                        >
                            {__('Register', 'wpshop')}
                        </button>
                    </div>
                </div>

                <div className="connection-content">
                    {error && (
                        <div className="connection-error">
                            {error}
                        </div>
                    )}

                    {recaptchaError && (
                        <div className="connection-error">
                            {recaptchaError}
                        </div>
                    )}

                    {forgotPasswordMessage && (
                        <div className="connection-success">
                            {forgotPasswordMessage}
                        </div>
                    )}
                    
                    {showForgotPassword ? (
                        <form className="forgot-password-form" onSubmit={handleForgotPasswordSubmit}>
                            <div className="form-group">
                                <label htmlFor="forgot-email">{__('Email Address', 'wpshop')}</label>
                                <input
                                    type="email"
                                    id="forgot-email"
                                    name="email"
                                    value={forgotPasswordEmail}
                                    onChange={(e) => setForgotPasswordEmail(e.target.value)}
                                    required
                                />
                            </div>
                            
                            {useRecaptcha && (
                                <div className="form-group recaptcha-group">
                                    <ReCaptcha 
                                        key={`forgot-captcha-${formKey}`}
                                        uniqueId={`forgot-${formKey}`}
                                        siteKey={reCaptchaPublicKey}
                                        onVerify={handleRecaptchaVerify}
                                        onExpired={handleRecaptchaExpired}
                                        onError={handleRecaptchaError}
                                    />
                                </div>
                            )}
                            
                            <div className="form-actions">
                                <button type="submit" className="submit-button" disabled={isLoading}>
                                    {isLoading ? __('Sending...', 'wpshop') : __('Send Reset Link', 'wpshop')}
                                </button>
                                <button type="button" className="back-to-login" onClick={handleBackToLogin}>
                                    {__('Back to Login', 'wpshop')}
                                </button>
                            </div>
                        </form>
                    ) : isLogin ? (
                        <form className="login-form" onSubmit={handleLoginSubmit}>
                            <div className="form-group">
                                <label htmlFor="username">{__('Username', 'wpshop')}</label>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value={loginCredentials.username}
                                    onChange={handleLoginChange}
                                    required
                                />
                            </div>
                            
                            <div className="form-group">
                                <label htmlFor="password">{__('Password', 'wpshop')}</label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    value={loginCredentials.password}
                                    onChange={handleLoginChange}
                                    required
                                />
                            </div>
                            
                            <div className="form-group checkbox-group">
                                <input
                                    type="checkbox"
                                    id="rememberMe"
                                    name="rememberMe"
                                    checked={loginCredentials.rememberMe}
                                    onChange={handleLoginChange}
                                />
                                <label htmlFor="rememberMe">{__('Remember me', 'wpshop')}</label>
                            </div>
                            
                            {useRecaptcha && (
                                <div className="form-group recaptcha-group">
                                    <ReCaptcha 
                                        key={`login-captcha-${formKey}`}
                                        uniqueId={`login-${formKey}`}
                                        siteKey={reCaptchaPublicKey}
                                        onVerify={handleRecaptchaVerify}
                                        onExpired={handleRecaptchaExpired}
                                        onError={handleRecaptchaError}
                                    />
                                </div>
                            )}
                            
                            <div className="form-actions">
                                <button type="submit" className="submit-button" disabled={isLoading}>
                                    {isLoading ? __('Logging in...', 'wpshop') : __('Login', 'wpshop')}
                                </button>
                                <a href="#" className="forgot-password" onClick={(e) => {
                                    e.preventDefault();
                                    setShowForgotPassword(true);
                                }}>
                                    {__('Forgot your password?', 'wpshop')}
                                </a>
                            </div>
                        </form>
                    ) : (
                        <form className="register-form" onSubmit={handleRegisterSubmit}>
                            <div className="form-group">
                                <label htmlFor="reg-username">{__('Username', 'wpshop')}</label>
                                <input
                                    type="text"
                                    id="reg-username"
                                    name="username"
                                    value={registerInfo.username}
                                    onChange={handleRegisterChange}
                                    required
                                />
                            </div>
                            
                            <div className="form-group">
                                <label htmlFor="email">{__('Email', 'wpshop')}</label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value={registerInfo.email}
                                    onChange={handleRegisterChange}
                                    required
                                />
                            </div>
                            
                            <div className="form-group">
                                <label htmlFor="reg-password">{__('Password', 'wpshop')}</label>
                                <input
                                    type="password"
                                    id="reg-password"
                                    name="password"
                                    value={registerInfo.password}
                                    onChange={handleRegisterChange}
                                    required
                                />
                            </div>
                            
                            <div className="form-group">
                                <label htmlFor="confirm-password">{__('Confirm Password', 'wpshop')}</label>
                                <input
                                    type="password"
                                    id="confirm-password"
                                    name="confirmPassword"
                                    value={registerInfo.confirmPassword}
                                    onChange={handleRegisterChange}
                                    required
                                />
                            </div>
                            
                            <div className="form-group checkbox-group">
                                <input
                                    type="checkbox"
                                    id="agreeTerms"
                                    name="agreeTerms"
                                    checked={registerInfo.agreeTerms}
                                    onChange={handleRegisterChange}
                                    required
                                />
                                <label htmlFor="agreeTerms">
                                    {__('I agree to the Terms and Conditions', 'wpshop')}
                                </label>
                            </div>
                            
                            {useRecaptcha && (
                                <div className="form-group recaptcha-group">
                                    <ReCaptcha 
                                        key={`register-captcha-${formKey}`}
                                        uniqueId={`register-${formKey}`}
                                        siteKey={reCaptchaPublicKey}
                                        onVerify={handleRecaptchaVerify}
                                        onExpired={handleRecaptchaExpired}
                                        onError={handleRecaptchaError}
                                    />
                                </div>
                            )}
                            
                            <div className="form-actions">
                                <button type="submit" className="submit-button" disabled={isLoading}>
                                    {isLoading ? __('Registering...', 'wpshop') : __('Register', 'wpshop')}
                                </button>
                            </div>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
};

render(<ConnectionBlock 
    accountUrl={document.getElementsByClassName('wp-block-wpshop-connection-block')[0].getAttribute('data-account-link')}
    useRecaptcha={document.getElementsByClassName('wp-block-wpshop-connection-block')[0].getAttribute('data-use-re-captcha')}
    reCaptchaPublicKey={document.getElementsByClassName('wp-block-wpshop-connection-block')[0].getAttribute('data-re-captcha-public-key')}
/>, '.wp-block-wpshop-connection-block');
export default ConnectionBlock;
