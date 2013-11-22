<?php
/*
 * Plugin Name: PIHG Enhancements
 * Plugin URI: http://patj.ca/wp/plugins/pihg/
 * Author: Patrick Johanneson
 * Author URI: http://patrickjohanneson.com/
 * Description: Provides CPTs and other enhancements for the PIHG site.
 */

class PIHG {

	function __construct() {
		add_action( 'init', array( $this, 'pihg_post_types' ) );
	}

	function pihg_post_types() {
		$labels = array(
			'name'				=> 'Seed',
			'singular_name'		=> 'Seed',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Seed',
			'edit_item'          => 'Edit Seed',
			'new_item'           => 'New Seed',
			'all_items'          => 'All Seeds',
			'view_item'          => 'View Seed',
			'search_items'       => 'Search Seedss',
			'not_found'          => 'No seeds found',
			'not_found_in_trash' => 'No seeds found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Seeds',
		  );

		$args = array(
		  'labels'             => $labels,
		  'public'             => true,
		  'publicly_queryable' => true,
		  'show_ui'            => true,
		  'show_in_menu'       => true,
		  'query_var'          => true,
		  'rewrite'            => array( 'slug' => 'seed' ),
		  'capability_type'    => 'post',
		  'has_archive'        => true,
		  'hierarchical'       => false,
		  'menu_position'      => null,
		  'supports'           => array( 'title', 'editor', 'author', 'thumbnail' )
		);

		register_post_type( 'seed', $args );

	}
}
new PIHG();
