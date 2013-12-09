<?php
/*
 * Plugin Name: PIHG Enhancements
 * Plugin URI: http://patj.ca/wp/plugins/pihg/
 * Author: Patrick Johanneson
 * Author URI: http://patrickjohanneson.com/
 * Description: Provides CPTs and other enhancements for the PIHG site.
 */

require_once( plugin_dir_path( __FILE__ ) . 'lib/cmb-extended/pihg-metaboxen.php' );
// require_once( plugin_dir_path( __FILE__ ) . 'lib/cmb-extended/example-functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/cmb-extended/custom-meta-boxes.php' );

class PIHG {

	var $types = array( 'seeds', 'contracts', );

	function __construct() {
		add_action( 'init', array( $this, 'pihg_post_types' ) );
		add_action( 'template_redirect', array( $this, 'template_selector' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'pihg_load_scripts' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'pihg_load_styles' ) );


		// some debugging
		// add_action( 'shutdown', array( $this, 'debooger' ) );

	}

	function pihg_post_types() {

		foreach( $this->types as $type ) {

			$singular = rtrim( $type, 's' );

			$labels = array(
				'name'				=> ucfirst( $type ),
				'singular_name'		=> ucfirst( $singular ),
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New ' . ucfirst( $singular ),
				'edit_item'          => 'Edit ' . ucfirst( $singular ),
				'new_item'           => 'New ' . ucfirst( $singular ),
				'all_items'          => 'All ' . ucfirst( $type ),
				'view_item'          => 'View ' . ucfirst( $singular ),
				'search_items'       => 'Search ' . ucfirst( $type ),
				'not_found'          => 'No ' . $type . ' found',
				'not_found_in_trash' => 'No ' . $type . ' found in Trash',
				'parent_item_colon'  => '',
				'menu_name'          => ucfirst( $type ),
			  );

			$args = array(
			  'labels'             => $labels,
			  'public'             => true,
			  'publicly_queryable' => true,
			  'show_ui'            => true,
			  'show_in_menu'       => true,
			  'query_var'          => true,
			  'rewrite'            => array( 'slug' => $singular, ),
			  'capability_type'    => 'post',
			  'has_archive'        => true,
			  'hierarchical'       => false,
			  'menu_position'      => null,
			  'supports'           => array( 'title', 'editor', 'author', 'thumbnail' )
			);

			register_post_type( $type, $args );

			// while we're here, let's set some image sizes
			add_image_size( $type . '_thumb', 60, 60, true );
			add_image_size( $type . '_featured_image', 300, 999, false );
			add_image_size( $type . '_page_header', 800, 999, false );
		}

	}

	function template_selector() {
		global $post;
		$post_type = get_post_type( $post->ID );
		if( ! in_array( $post_type, $this->types ) ) {
			return;
		}
		if( is_singular( $post_type ) ) {
			include( plugin_dir_path( __FILE__ ) . "templates/single-{$post_type}.php" );
			exit;
		}

	}

	function pihg_load_scripts() {
		$handle = 'pihg';
		$src = plugins_url( 'scripts/pihg.jquery.js', __FILE__ );
		$deps = array( 'jquery' );
		$ver = false;
		$in_footer = true;
		wp_register_script($handle, $src, $deps, $ver, $in_footer );
		wp_enqueue_script( $handle );

	}

	function pihg_load_styles() {
		$handle = 'pihg-admin-styles';
		$src = plugins_url( 'css/admin-styles.css', __FILE__ );
		$deps = array( 'cmb-styles' );
		$ver = false;
		wp_register_style( $handle, $src, $deps, $ver );
		wp_enqueue_style( $handle );

	}

	function debooger() {
		global $template;
		echo( "Template: $template<br />\n" );
		if( is_singular( 'seed' ) ) {
			// get the meta
			global $post;
			echo( "<pre>" );
			var_dump( get_post_meta( $post->ID, '_pihg_seed_info_table' ) );
			echo( "</pre>" );
		}


	}
}
new PIHG();
