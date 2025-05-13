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

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit() {

    const page = 'account';

    const navigation = {
        account: {
            title: __('My Account', 'wpshop'),
            icon: 'icon-account',
        },
        orders: {
            title: __('Orders', 'wpshop'),
            icon: 'icon-orders',
        },
        logout: {
            title: __('Logout', 'wpshop'),
            icon: 'icon-logout',
        }
    }

    return (
        <div className="wp-block-wpshop-account-navigation">
            <ul className="wps-account-navigation gridw-2">
                {navigation && Object.keys(navigation).map((key) => {
                    const element = navigation[key];
                    return (
                        <li className="wps-account-navigation-item">
                            <a className={key === page ? 'active' : ''} >
                                <i className={`navigation-icon ${element.icon}`}></i>
                                {element.title}
                            </a>
                        </li>
                    );
                }
                )}

            </ul>
        </div>
    );
}
