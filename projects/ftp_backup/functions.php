<?php

/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles()
{

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);
}
add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

add_filter('gettext', function ($translated_text, $text, $domain) {
	if ($domain === 'awesome-support') {
		$replacements = [
			'Ticket' => 'Submit Complaint',
		];
		if (isset($replacements[$text])) {
			$translated_text = $replacements[$text];
		}
	}
	return $translated_text;
}, 10, 3);

add_action('wp_enqueue_scripts', function () {
	wp_enqueue_script(
		'awesome-support-custom-js',
		get_stylesheet_directory_uri() . '/js/custom.js',
		['jquery'], // dependencies
		'1.0',
		true // load in footer
	);
});

add_action('wp_enqueue_scripts', function () {
	if (is_singular('event')) {
		wp_enqueue_style(
			'single-event-css',
			get_stylesheet_directory_uri() . '/custom-css/single-event.css',
			array(),
			filemtime(get_stylesheet_directory() . '/custom-css/single-event.css')
		);
	}
});


function create_event_post_type()
{
	$labels = array(
		'name'                  => _x('Events', 'Post type general name'),
		'singular_name'         => _x('Event', 'Post type singular name'),
		'menu_name'             => _x('Events', 'Admin Menu text'),
		'name_admin_bar'        => _x('Event', 'Add New on Toolbar'),
		'add_new'               => __('Add New'),
		'add_new_item'          => __('Add New Event'),
		'new_item'              => __('New Event'),
		'edit_item'             => __('Edit Event'),
		'view_item'             => __('View Event'),
		'all_items'             => __('All Events'),
		'search_items'          => __('Search Events'),
		'parent_item_colon'     => __('Parent Events:'),
		'not_found'             => __('No Events found.'),
		'not_found_in_trash'    => __('No Events found in Trash.'),
		'featured_image'        => _x('Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3'),
		'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3'),
		'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3'),
		'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3'),
		'archives'              => _x('Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4'),
		'insert_into_item'      => _x('Insert into event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4'),
		'uploaded_to_this_item' => _x('Uploaded to this events', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4'),
		'filter_items_list'     => _x('Filter events list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4'),
		'items_list_navigation' => _x('Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4'),
		'items_list'            => _x('Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4'),
	);

	$args = array(
		'labels'             => $labels,
		'menu_icon' => 'dashicons-calendar-alt',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array('slug' => 'event'),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array('title', 'editor', 'thumbnail'),
	);

	register_post_type('event', $args);
}

add_action('init', 'create_event_post_type');

// EVENT GALLERY LOAD MORE

add_action('wp_enqueue_scripts',function(){
	wp_enqueue_script(
		'event-gallery-js',
		get_stylesheet_directory_uri() . '/js/event-gallery.js',
		['jquery'],
		filemtime(get_template_directory() . '/js/event-gallery.js'),
		true
	);
	wp_localize_script('event-gallery-js','eventGallery',[
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'post_id' =>get_the_ID()
	]);
});


add_action('wp_ajax_nopriv_load_more','event_gallery_load_more');
add_action('wp_ajax_load_more','event_gallery_load_more');

function event_gallery_load_more(){
	$post_id = intval($_POST['post_id']);
	$offset = intval($_POST['offset']);
	$limit = intval($_POST['limit']);

	$images = array_values(get_attached_media('image',$post_id));
	$slice = array_slice($images,$offset,$limit);

	$newImages = '';

	foreach($slice as $img){
		$img_url = wp_get_attachment_image($img->ID,'large');
		$newImages .= '<div class="event-gallery-image"><img src = " '. esc_url($img_url) .' " alt = ""></div>';
	}

	echo $newImages;
	wp_die();
}