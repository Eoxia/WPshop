/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './scss/editor.scss';
import { useState } from '@wordpress/element';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {
	const [isLogin, setIsLogin] = useState( true );
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps } className={`${ blockProps.className } wp-block-wpshop-connection-block-editor`}>
			<div className="connection-container">
				<div className="connection-header">
					<div className="connection-tabs">
						<button 
							className={`connection-tab ${ isLogin ? 'active' : ''}`}
							onClick={ () => setIsLogin( true ) }
						>
							{ __( 'Login', 'wpshop' ) }
						</button>
						<button 
							className={`connection-tab ${ !isLogin ? 'active' : ''}`}
							onClick={ () => setIsLogin( false ) }
						>
							{ __( 'Register', 'wpshop' ) }
						</button>
					</div>
				</div>
				
				<div className="connection-content">
					{ isLogin ? (
						<form className="login-form">
							<div className="form-group">
								<label htmlFor="username">{ __( 'Username', 'wpshop' ) }</label>
								<input
									type="text"
									id="username"
									name="username"
									disabled
								/>
							</div>
							
							<div className="form-group">
								<label htmlFor="password">{ __( 'Password', 'wpshop' ) }</label>
								<input
									type="password"
									id="password"
									name="password"
									value="********"
									disabled
								/>
							</div>
							
							<div className="form-group checkbox-group">
								<input
									type="checkbox"
									id="rememberMe"
									name="rememberMe"
									disabled
								/>
								<label htmlFor="rememberMe">{ __( 'Remember me', 'wpshop' ) }</label>
							</div>
							
							<div className="form-actions">
								<button type="button" className="submit-button" disabled>
									{ __( 'Login', 'wpshop' ) }
								</button>
								<a href="#" className="forgot-password">
									{ __( 'Forgot your password?', 'wpshop' ) }
								</a>
							</div>
						</form>
					) : (
						<form className="register-form">
							<div className="form-group">
								<label htmlFor="reg-username">{ __( 'Username', 'wpshop' ) }</label>
								<input
									type="text"
									id="reg-username"
									name="username"
									disabled
								/>
							</div>
							
							<div className="form-group">
								<label htmlFor="email">{ __( 'Email', 'wpshop' ) }</label>
								<input
									type="email"
									id="email"
									name="email"
									disabled
								/>
							</div>
							
							<div className="form-group">
								<label htmlFor="reg-password">{ __( 'Password', 'wpshop' ) }</label>
								<input
									type="password"
									id="reg-password"
									name="password"
									value="********"
									disabled
								/>
							</div>
							
							<div className="form-group">
								<label htmlFor="confirm-password">{ __( 'Confirm Password', 'wpshop' ) }</label>
								<input
									type="password"
									id="confirm-password"
									name="confirmPassword"
									value="********"
									disabled
								/>
							</div>
							
							<div className="form-group checkbox-group">
								<input
									type="checkbox"
									id="agreeTerms"
									name="agreeTerms"
									disabled
								/>
								<label htmlFor="agreeTerms">
									{ __( 'I agree to the Terms and Conditions', 'wpshop' ) }
								</label>
							</div>
							
							<div className="form-actions">
								<button type="button" className="submit-button" disabled>
									{ __( 'Register', 'wpshop' ) }
								</button>
							</div>
						</form>
					) }

					<div className="editor-info-text">
						{ __( 'This is a preview of the Connection Block. The form will be fully functional on the front-end.', 'wpshop' ) }
					</div>
				</div>
			</div>
		</div>
	);
}
