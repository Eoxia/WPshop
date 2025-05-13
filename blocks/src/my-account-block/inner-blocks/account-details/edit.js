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

	const contact = {
		firstname: 'John',
		lastname: 'Doe',
		email: 'john.doe@exemple.mail',
	}
	const third_party = {
		address: '123 Main St',
		zip: '12345',
		town: 'Anytown',
		country: 'Country',
	}

	// Determine if we should show loading state
	const isLoading = contact === null && third_party === null;

	return (
        <div className={'wp-block-wpshop-account-details wpeo-form'}>

            <div class="form-element form-element-disable">
                <span class="form-label">{ __( 'Login', 'wpshop' ) }</span>
                <label class="form-field-container">
                    <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.login} readonly/>
                </label>
            </div>

            <div class="gridlayout grid-2">
                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Firstname', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.firstname} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Lastname', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.lastname} readonly/>
                    </label>
                </div>
            </div>

            <div class="gridlayout grid-2">
                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Email', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.email} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Phone', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={contact?.phone} readonly/>
                    </label>
                </div>
            </div>

            <div class="form-element form-element-disable">
                <span class="form-label">{ __( 'Address', 'wpshop' ) }</span>
                <label class="form-field-container">
                    <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.address} readonly/>
                </label>
            </div>

            <div class="gridlayout grid-3">
                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Zip', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.zip} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Town', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.town} readonly/>
                    </label>
                </div>

                <div class="form-element form-element-disable">
                    <span class="form-label">{ __( 'Country', 'wpshop' ) }</span>
                    <label class="form-field-container">
                        <input type="text" class={`form-field ${isLoading ? 'animate-pulse' : ''}`} name="email" value={third_party?.country} readonly/>
                    </label>
                </div>
            </div>
        </div>
    )
}
