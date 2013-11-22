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
		add_action( );
	}

	function pihg_post_types() {

		$types = array(
			array(
				'name' => 'seed',
				'Name' => 'Seed',
			),
		);

		foreach( $types as $type ) {

			$labels = array(
				'name'				=> $type['Name'] . 's',
				'singular_name'		=> $type['Name'],
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New ' . $type['Name'],
				'edit_item'          => 'Edit ' . $type['Name'],
				'new_item'           => 'New ' . $type['Name'],
				'all_items'          => 'All ' . $type['Name'] . 's',
				'view_item'          => 'View ' . $type['Name'],
				'search_items'       => 'Search ' . $type['Name'] .'s',
				'not_found'          => 'No ' . $type['name'] . ' found',
				'not_found_in_trash' => 'No ' . $type['name'] . 's found in Trash',
				'parent_item_colon'  => '',
				'menu_name'          => $type['Name'] . 's',
			  );

			$args = array(
			  'labels'             => $labels,
			  'public'             => true,
			  'publicly_queryable' => true,
			  'show_ui'            => true,
			  'show_in_menu'       => true,
			  'query_var'          => true,
			  'rewrite'            => array( 'slug' => $type['name'] ),
			  'capability_type'    => 'post',
			  'has_archive'        => true,
			  'hierarchical'       => false,
			  'menu_position'      => null,
			  'supports'           => array( 'title', 'editor', 'author', 'thumbnail' )
			);

			register_post_type( $type['name'], $args );

			// while we're here, let's set some image sizes
			add_image_size( $type['name'] . '_thumb', 60, 60, true );
			add_image_size( $type['name'] . '_page_header', 800, 100, false );
		}



	}
}
new PIHG();
