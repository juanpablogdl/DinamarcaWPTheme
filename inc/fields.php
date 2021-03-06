<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function yourprefix_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template
	if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function yourprefix_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a message if the $post_id is 2
 *
 * @param  array             $field_args Array of field parameters
 * @param  CMB2_Field object $field      Field object
 */
function yourprefix_before_row_if_2( $field_args, $field ) {
	if ( 2 == $field->object_id ) {
		echo '<p>Testing <b>"before_row"</b> parameter (on $post_id 2)</p>';
	} else {
		echo '<p>Testing <b>"before_row"</b> parameter (<b>NOT</b> on $post_id 2)</p>';
	}
}

add_action( 'cmb2_init', 'yourprefix_register_about_page_metabox' );
/**
 * Hook in and add a metabox that only appears on the 'About' page
 */
function yourprefix_register_about_page_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_yourprefix_about_';

	/**
	 * Metabox to be displayed on a single page ID
	 */
	$cmb_about_page = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'About Page Metabox', 'cmb2' ),
		'object_types' => array( 'post', ), // Post type
		'context'      => 'normal',
		'priority'     => 'high',
		'show_names'   => true, // Show field names on the left
		'show_on'      => array( 'id' => array( 2, ) ), // Specific post IDs to display this metabox
	) );

	$cmb_about_page->add_field( array(
		'name' => __( 'Test Text', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'text',
		'type' => 'text',
	) );

}

add_action( 'cmb2_init', 'yourprefix_register_repeatable_group_field_metabox' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function yourprefix_register_repeatable_group_field_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_yourprefix_group_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb_group = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Repeating Field Group', 'cmb2' ),
		'object_types' => array( 'post', ),
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb_group->add_field( array(
		'id'          => $prefix . 'demo',
		'type'        => 'group',
		'description' => __( 'Generates reusable form entries', 'cmb2' ),
		'options'     => array(
			'group_title'   => __( 'Entry {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Another Entry', 'cmb2' ),
			'remove_button' => __( 'Remove Entry', 'cmb2' ),
			'sortable'      => true, // beta
		),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb_group->add_group_field( $group_field_id, array(
		'name'       => __( 'Entry Title', 'cmb2' ),
		'id'         => 'title',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name'        => __( 'Description', 'cmb2' ),
		'description' => __( 'Write a short description for this entry', 'cmb2' ),
		'id'          => 'description',
		'type'        => 'textarea_small',
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name' => __( 'Entry Image', 'cmb2' ),
		'id'   => 'image',
		'type' => 'file',
	) );

	$cmb_group->add_group_field( $group_field_id, array(
		'name' => __( 'Image Caption', 'cmb2' ),
		'id'   => 'image_caption',
		'type' => 'text',
	) );

}

/**
 * Fields for Gallery (page-gallery.php);
 */
function dinamarca_gallery() {
	$prefix = '_gallery_dinamarca';

	$gallery = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Gallery Images', 'dinamarca' ),
		'object_types' => array( 'page' ),
		'show_on'      => array( 'key' => 'page-template', 'value' => 'templates/page-gallery.php' )
	) );

	$gallery->add_field( array(
		'name'         => __( 'Multiple Files', 'cmb2' ),
		'desc'         => __( 'Upload or add multiple images/attachments.', 'cmb2' ),
		'id'           => $prefix . 'file_list',
		'type'         => 'file_list',
		'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
	) );
}
add_action( 'cmb2_init', 'dinamarca_gallery' );


/**
 * Fields for Single Service (page-singleService.php);
 */
function single_service() {
	$prefix = '_single_Service';

	$singleService = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'More Fields', 'dinamarca' ),
		'object_types' => array( 'page' ),
		'show_on'      => array( 'key' => 'page-template', 'value' => 'templates/page-singleService.php' )
	) );

	$singleService->add_field( array(
		'name'    => __( 'Icon or Image for Service', 'cmb2' ),
		'desc'    => __( 'Use a Square Image for this, Recommended Size 140x140', 'cmb2' ),
		'id'      => $prefix . '_service_image',
		'type'    => 'file',
	) );
	$singleService->add_field( array(
		'name' => __( 'Short Description', 'cmb2' ),
		'desc' => __( 'Add a small description to this service', 'cmb2' ),
		'id'   => $prefix . '_description',
		'type' => 'text',
	) );
}
add_action( 'cmb2_init', 'single_service' );


add_action( 'cmb2_init', 'user_profile' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function user_profile() {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_user_';
	/**
	 * Metabox for the user profile screen
	 */
	$user_bio = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		'title'            => __( 'User Profile Metabox', 'cmb2' ),
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );
	$user_bio->add_field( array(
		'name'     => __( 'Extra Info', 'cmb2' ),
		'desc'     => __( 'field description (optional)', 'cmb2' ),
		'id'       => $prefix . 'extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );
	$user_bio->add_field( array(
		'name'    => __( 'Avatar', 'cmb2' ),
		'desc'    => __( 'Use a Square Image for this Recommended Size 150x150', 'cmb2' ),
		'id'      => $prefix . 'avatar',
		'type'    => 'file',
	) );

	/** SOCIALS **/
	$user_bio->add_field( array(
		'name' => __( 'Dribble URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your   profile', 'cmb2' ),
		'id'   => $prefix . 'dribbleurl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Facebook URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Facebook  profile', 'cmb2' ),
		'id'   => $prefix . 'facebookurl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Flickr URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Flickr  profile', 'cmb2' ),
		'id'   => $prefix . 'flickrUrl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'GitHub URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Github  profile', 'cmb2' ),
		'id'   => $prefix . 'githuburl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Google+ URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Google+  profile', 'cmb2' ),
		'id'   => $prefix . 'googleplusurl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Instagram URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Instagram  profile', 'cmb2' ),
		'id'   => $prefix . 'instagramrUrl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'JSFiddle URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your JSFiddle  profile', 'cmb2' ),
		'id'   => $prefix . 'jsfiddlerUrl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Linkedin URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Linkedin  profile', 'cmb2' ),
		'id'   => $prefix . 'linkedinurl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Pinterest URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Pinterest profile', 'cmb2' ),
		'id'   => $prefix . 'pinteresturl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'SoundCloud URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your SoundCloud  profile', 'cmb2' ),
		'id'   => $prefix . 'soundCloudrUrl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Stackoverflow URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Stackoverflow  profile', 'cmb2' ),
		'id'   => $prefix . 'stackoverflowrUrl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Tumblr URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Tumblr  profile', 'cmb2' ),
		'id'   => $prefix . 'tumblUrl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Twitter URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Twitter  profile', 'cmb2' ),
		'id'   => $prefix . 'twitterurl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'Vimeo URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Vimeo  profile', 'cmb2' ),
		'id'   => $prefix . 'vimeorUrl',
		'type' => 'text_url',
	) );
	$user_bio->add_field( array(
		'name' => __( 'YouTube URL', 'cmb2' ),
		'desc' => __( 'Add your URL to your Youtube  profile', 'cmb2' ),
		'id'   => $prefix . 'youtubeurl',
		'type' => 'text_url',
	) );
}


/** Fields Slider */

function slider_fields() {
	$prefix = '_slider_fields';

	$slider = new_cmb2_box( array(
		'id'            => $prefix . '_metabox',
		'title'         => __( 'More Fields', 'cmb2' ),
		'object_types'  => array( 'slider', ), // Post type
	) );

	$slider->add_field( array(
		'name' => __( 'URL', 'cmb2' ),
		'desc' => __( 'External URL', 'cmb2' ),
		'id'   => $prefix . '_slider_url',
		'type' => 'text_url',
	) );
	$slider->add_field( array(
		'name' => __( 'Open in New Window', 'cmb2' ),
		'desc' => __( 'Check to open in new Window', 'cmb2' ),
		'id'   => $prefix . '_slider_check',
		'type' => 'checkbox',
	) );
}
add_action( 'cmb2_init', 'slider_fields' );
