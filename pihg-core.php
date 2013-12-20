<?php
/*
 * Plugin Name: PIHG Enhancements
 * Plugin URI: http://patj.ca/wp/plugins/pihg/
 * Author: Patrick Johanneson
 * Author URI: http://patrickjohanneson.com/
 * Description: Provides CPTs and other enhancements for the PIHG site.
 */

require_once( plugin_dir_path( __FILE__ ) . 'lib/cmb-extended/custom-meta-boxes.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/cmb-extended/pihg-metaboxen.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/cmb-extended/example-functions.php' );
require_once( plugin_dir_path (__FILE__ ) . 'lib/helpers/helpers.php' );

class PIHG {

	var $types = array( 'pihg-seed', 'pihg-contract', );
	var $version = '0.5';

	function __construct() {

		// frontend / global
		add_action( 'init', array( $this, 'post_types' ) );
		add_action( 'init', array( $this, 'on_update' ) );

		// admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

		// debugging
		add_action( 'shutdown', array( $this, 'debooger' ) );

	}

	function post_types() {

		foreach( $this->types as $_type ) {

			$type = str_replace( 'pihg', '', $_type );

			$labels = array(
				'name'				=> ucfirst( $type ). 's',
				'singular_name'		=> ucfirst( $type ),
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New ' . ucfirst( $type ),
				'edit_item'          => 'Edit ' . ucfirst( $type ),
				'new_item'           => 'New ' . ucfirst( $type ),
				'all_items'          => 'All ' . ucfirst( $type ) . 's',
				'view_item'          => 'View ' . ucfirst( $type ),
				'search_items'       => 'Search ' . ucfirst( $type ) .'s',
				'not_found'          => 'No ' . $type . ' found',
				'not_found_in_trash' => 'No ' . $type . 's found in Trash',
				'parent_item_colon'  => '',
				'menu_name'          => ucfirst( $type ) . 's',
			  );

			$args = array(
			  'labels'             => $labels,
			  'public'             => true,
			  'publicly_queryable' => true,
			  'show_ui'            => true,
			  'show_in_menu'       => true,
			  'query_var'          => true,
			  'rewrite'            => array( 'slug' => $type . 's', ),
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

	function load_scripts() {
		$handle = 'pihg';
		$src = plugins_url( 'scripts/pihg.jquery.js', __FILE__ );
		$deps = array( 'jquery' );
		$ver = false;
		$in_footer = true;
		wp_register_script($handle, $src, $deps, $ver, $in_footer );
		wp_enqueue_script( $handle );

	}

	function load_styles() {
		$handle = 'pihg-admin-styles';
		$src = plugins_url( 'css/admin-styles.css', __FILE__ );
		$deps = array( 'cmb-styles' );
		$ver = false;
		wp_register_style( $handle, $src, $deps, $ver );
		wp_enqueue_style( $handle );

	}

	function on_update() {
		$saved_version = get_option( '_pihg_plugin_version' );
		if( $saved_version != $this->version ) {
			update_option( '_pihg_plugin_version', $this->version );
			flush_rewrite_rules();
		}
	}

	function debooger() {
		global $template;
		echo( "Template: $template<br />\n" );

	}
}
new PIHG();
