import { createRoot } from '@wordpress/element';

export function render( element, containerClass ) {
    document.addEventListener( 'DOMContentLoaded', function() {
        const container = document.querySelector( containerClass );
        if ( ! container ) {
            return;
        }

        const root = createRoot( container );
        root.render( element );
    });
}