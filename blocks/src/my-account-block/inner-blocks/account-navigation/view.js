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
 
import { render } from '../../../utils';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';

import { useSelect, useDispatch } from '@wordpress/data';
import { storeName } from '../../../store/my-account-store';

import apiFetch from '@wordpress/api-fetch';

import { select } from '@wordpress/data';

const AccountNavigation = (props) => {

    const { logoutUrl } = props;

    const {setPage} = useDispatch(storeName);
    const page = useSelect((select) => {
        try {
            const store = select(storeName);
            return store ? store.getPage() : null;
        } catch (e) {
            console.error('Erreur lors de l\'accès au store:', e);
            return null;
        }
    }, []);

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
            url: logoutUrl,
        }
    }

    return (
        <div className="wp-block-wpshop-account-navigation">
            <ul className="wps-account-navigation gridw-2">
                {navigation && Object.keys(navigation).map((key) => {
                    const element = navigation[key];
                    return (
                        <li className="wps-account-navigation-item">
                            <a  className={key === page ? 'active' : ''} 
                                href={element.url ? element.url : undefined}
                                onClick={element.url ? undefined : () => setPage(key)}
                            >
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

render(<AccountNavigation logoutUrl={
    document.getElementsByClassName('wp-block-wpshop-account-navigation')[0].getAttribute('data-logout-url')
}
 />, '.wp-block-wpshop-account-navigation');
export default AccountNavigation;
