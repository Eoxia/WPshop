/**
 * Mini cart: header cart icon that opens the cart in a side panel.
 */
import { registerBlockType } from '@wordpress/blocks';

import './scss/style.scss';

import Edit from './edit';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: Edit,
	save: () => null,
} );
