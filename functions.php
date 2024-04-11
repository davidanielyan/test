<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */


defined( 'ABSPATH' ) || exit;



/**
 * Removes the pare es stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	
	
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}



add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 11 );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 'my_theme_enqueue_styles');



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );




function create_property_post_type() {
    register_post_type('property',
        array(
            'labels' => array(
                'name' => __('Недвижимость'),
                'singular_name' => __('Объект недвижимости')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        )
    );

    
    register_taxonomy(
        'property_type',
        'property',
        array(
            'label' => __('Тип недвижимости'),
            'rewrite' => array('slug' => 'property-type'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_property_post_type');



add_theme_support('post-thumbnails', array('property'));




function get_city_id_by_name($city_name) {
    $city = get_page_by_title($city_name, OBJECT, 'city');
    if ($city) {
        return $city->ID;
    } else {
        return false;
    }
}


function create_city_post_type() {
    register_post_type('city',
        array(
            'labels' => array(
                'name' => __('Города'),
                'singular_name' => __('Город')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
        )
    );
}
add_action('init', 'create_city_post_type');



function save_property_city($post_id) {
    if (isset($_POST['property_city'])) {
        update_post_meta($post_id, 'property_city', sanitize_text_field($_POST['property_city']));
    }
}
add_action('save_post', 'save_property_city');


function add_property_city_meta_box() {
    add_meta_box(
        'property_city_meta_box',
        'Выберите город',
        'render_property_city_meta_box',
        'property', 
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'add_property_city_meta_box');

function render_property_city_meta_box($post) {
    $city_id = get_post_meta($post->ID, 'property_city', true);
    $cities = get_posts(array('post_type' => 'city', 'posts_per_page' => -1));

    if ($cities) {
        echo '<select name="property_city">';
        echo '<option value="">Выберите город</option>';
        foreach ($cities as $city) {
            $selected = ($city->ID == $city_id) ? 'selected' : '';
            echo '<option value="' . $city->ID . '" ' . $selected . '>' . $city->post_title . '</option>';
        }
        echo '</select>';
    } else {
        echo 'Города не найдены.';
    }
}

$city_id = get_post_meta(get_the_ID(), 'property_city', true);
$city = get_post($city_id);
if ($city) {
    echo 'Город: ' . $city->post_title;
}





function get_property_types_options() {
    $property_types = get_terms(array(
        'taxonomy' => 'property_type',
        'hide_empty' => false, 
    ));

    $options = '';
    foreach ($property_types as $property_type) {
        $options .= '<option value="' . $property_type->slug . '">' . $property_type->name . '</option>';
    }

    return $options;
}

add_action('wp_enqueue_scripts', 'add_custom_scripts');
function add_custom_scripts() {
   wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . "/dist/js/custom.js", array('jquery'), '1.0', true);
    
    $translation_array = array(
        'ajax_url' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('custom-script', 'ajax_object', $translation_array);
}

add_action('wp_ajax_submit_post', 'submit_post_callback');
add_action('wp_ajax_nopriv_submit_post', 'submit_post_callback'); // Allow for non-logged-in users

function submit_post_callback() {
    if (isset($_POST['title'])  && isset($_POST['area']) && isset($_POST['price']) && isset($_POST['address']) && isset($_POST['living_space']) && isset($_POST['floor'])) {
        
        $new_post = array(
            'post_title'    => sanitize_text_field($_POST['title']),
            'post_status'   => 'publish',
            'post_type'     => 'property' // Changed post type to "property"
        );

        
        $post_id = wp_insert_post($new_post);

        
        update_field('area', $_POST['area'], $post_id);
        update_field('price', $_POST['price'], $post_id);
        update_field('address', $_POST['address'], $post_id);
        update_field('living_space', $_POST['living_space'], $post_id);
        update_field('floor', $_POST['floor'], $post_id);


        $property_type = sanitize_text_field($_POST['property_type']);
        $term = get_term_by('slug', $property_type, 'property_type'); // Get term by name
        if ($term !== false && !is_wp_error($term)) {
            wp_set_object_terms($post_id, $term->term_id, 'property_type'); // Set term for the post
        } 




       
        if (!empty($_FILES['featuredImage']['name'])) {
            $attachment_id = upload_featured_image($post_id);
            set_post_thumbnail($post_id, $attachment_id);
            echo 'Post added successfully with featured image.';
        } else {
            echo 'Post added successfully without featured image.';
        }
    } else {
        echo 'Please provide title, content, and address.';
    }

    wp_die();
}

function upload_featured_image($post_id) {
    $file = $_FILES['featuredImage'];
    $upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));

    if (isset($upload['error']) && $upload['error'] != 0) {
        echo 'Error uploading featured image.';
        return false;
    }

    $filename = $upload['file'];
    $filetype = wp_check_filetype(basename($filename), null);
    $wp_upload_dir = wp_upload_dir();
    $attachment = array(
        'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
        'post_mime_type' => $filetype['type'],
        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $filename, $post_id);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    return $attach_id;
}
