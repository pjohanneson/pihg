<?php
/*
 * Plugin Name: PIHG Enhancements
 * Plugin URI: http://patj.ca/wp/plugins/pihg/
 * Author: Patrick Johanneson
 * Author URI: http://patrickjohanneson.com/
 * Description: Provides CPTs and other enhancements for the PIHG site.
 */

class PIHG {

	var $types = array( 'seed', 'contract', );

	function __construct() {
		add_action( 'init', array( $this, 'pihg_post_types' ) );
		add_action( 'template_redirect', array( $this, 'template_selector' ) );

		// some debugging
		add_action( 'shutdown', array( $this, 'debooger' ) );

	}

	function pihg_post_types() {



		foreach( $this->types as $type ) {

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
			  'rewrite'            => array( 'slug' => $type ),
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
			add_image_size( $type . '_page_header', 800, 100, false );
		}

	}

	function template_selector() {
		global $post;
		$post_type = get_post_type( $post->ID );
		if( in_array( $post_type, $this->types ) ) {
			if( is_singular( $post_type ) ) {
				include( plugins_url( "templates/single-{$post_type}.php", __FILE__ ) );
				exit;
			}
		}
		return;
	}

	function debooger() {
		global $template;
		echo( "Template: $template<br />\n" );

	}
}
new PIHG();
