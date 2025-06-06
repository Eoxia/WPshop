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
import { useEffect, useState } from '@wordpress/element';

import { useSelect, useDispatch, use } from '@wordpress/data';

import apiFetch from '@wordpress/api-fetch';

const ConnectionBlock = ({ accountUrl }) => {
    const [isLogin, setIsLogin] = useState(true);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');
    
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

    const handleLoginSubmit = (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError('');
        
        apiFetch({
            path: 'wp-shop/v1/login',
            method: 'POST',
            data: {
                username: loginCredentials.username,
                password: loginCredentials.password,
            },
        }).then((response) => {
            // Redirect to my-account page on successful login
            redirectToMyAccount();
        }).catch((error) => {
            setError(error.message || __('Login failed. Please check your credentials.', 'wpshop'));
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
            setError(__('Passwords do not match', 'wpshop'));
            setIsLoading(false);
            return;
        }
        
        if (!registerInfo.agreeTerms) {
            setError(__('You must agree to the terms and conditions', 'wpshop'));
            setIsLoading(false);
            return;
        }
        
        apiFetch({
            path: 'wp-shop/v1/register',
            method: 'POST',
            data: {
                username: registerInfo.username,
                email: registerInfo.email,
                password: registerInfo.password,
            },
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
            setError(error.message || __('Registration failed.', 'wpshop'));
        }).finally(() => {
            setIsLoading(false);
        });
    };

    return (
        <div className="wp-block-wpshop-connection-block">
            <div className="connection-container">
                <div className="connection-header">
                    <div className="connection-tabs">
                        <button 
                            className={`connection-tab ${isLogin ? 'active' : ''}`}
                            onClick={() => setIsLogin(true)}
                        >
                            {__('Login', 'wpshop')}
                        </button>
                        <button 
                            className={`connection-tab ${!isLogin ? 'active' : ''}`}
                            onClick={() => setIsLogin(false)}
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
                    
                    {isLogin ? (
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
                            
                            <div className="form-actions">
                                <button type="submit" className="submit-button" disabled={isLoading}>
                                    {isLoading ? __('Logging in...', 'wpshop') : __('Login', 'wpshop')}
                                </button>
                                <a href="#" className="forgot-password">
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
/>, '.wp-block-wpshop-connection-block');
export default ConnectionBlock;
