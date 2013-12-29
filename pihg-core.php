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
		add_action( 'init', array( $this, 'on_plugin_update' ) ); // remove once testing is complete!
		// add_filter( 'the_content', array( $this, 'seed_archive_prepend' ) );
		add_action( 'pihg_seed_info', array( $this, 'seed_info' ) );

		// admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'admin_menu', array( $this, 'boilerplate_panels' ) );

		// debugging
		// add_action( 'shutdown', array( $this, 'debooger' ) );
		add_filter( 'pihg_top_of_page', array( $this, 'handy_info' ) );

	}

	/**
	 * Create the post types
	 *
	 */
	function post_types() {

		foreach( $this->types as $_type ) {

			$type = str_replace( 'pihg-', '', $_type );

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
			  'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',  )
			);

			register_post_type( $_type, $args );

			// while we're here, let's set some image sizes
			add_image_size( $_type . '-thumb', 60, 60, true );
			add_image_size( $_type . '-featured-image', 300, 999, false );
			add_image_size( $_type . '-page-header', 800, 999, false );
		}

	}

	function seed_archive_prepend( $content ) {
		if( is_post_type_archive( 'pihg-seed' ) ) {
			$sbp = get_option( '_pihg_sbp' );
			$content =
					"<h1>{$sbp['title']}</h1>\n" .
					"<p>{$sbp['content']}</p>\n" .
					$content;
		}
		return $content;
	}

	/**
	 * Add the Seed Info table to the end of the seed CPT single-post content
	 * @param string $content
	 * @return string
	 */
	function seed_info( $content ) {
		$table = '';
		if ( is_singular( 'pihg-seed' ) ) {
			$seed_info = get_post_meta( get_the_ID(), '_pihg_seed_info_table' );
			if( $seed_info ) {
				usort( $seed_info, array( $this, 'seed_info_sorter' ) );
				// table header
				$table = '
					<table id="seed-info" class="seed-info">
					<thead>
					<tr>
					<th>Year</th>
					<th>PA (16:0)</th>
					<th>SA (18:0)</th>
					<th>0A (18:1)</th>
					<th>LA (18:2)</th>
					<th>GLA (18:3)</th>
					<th>ALA (18:3)</th>
					<th>SDA (18:4)</th>
					<th>Avg % Oil Content</th>
					</tr>
					</thead>

					<tbody>
					';
				foreach( $seed_info as $row ) {
					$table .= "<tr>\n";
					foreach( $row as $key => $value ) {
						$class = 'class="' . str_replace( array( '_pihg_', '_' ), array( '', '-' ), $key ) . '"';
						$table .= "<td $class>$value</td>\n";
					}
					$table .= "</tr>\n";
				}
				// end o' table
				$table .= '
					</tbody>
					</table> <!-- #seed-info .seed-info -->
					';
			}
		}

		echo( $table );
	}

	function boilerplate_panels() {
		foreach( $this->types as $_type ) {
			$type = str_replace( 'pihg-', '', $_type );
			add_submenu_page(
					'edit.php?post_type=' . $_type,
					ucfirst( $type ) . ' Archive Text',
					ucfirst( $type ) . ' Archive Text',
					'edit_posts',
					$type . '-boilerplate',
					array( $this, $type . '_archive_boilerplate_edit' )
			);
		}
	}

	function seed_archive_boilerplate_edit() {
		if( $_POST ) {
			check_admin_referer( 'update_sbp', '_sbp_nonce' );
			$title = esc_attr( $_POST['sbp_title'] );
			$content = esc_attr( $_POST['sbp_content'] );
			update_option( '_pihg_sbp', array( 'title' => $title, 'content' => $content ) );
		}
		$boilerplate = get_option( '_pihg_sbp');
		extract( $boilerplate );
		echo( "<h1>Edit Seed Archive Boilerplate</h1>\n" );
		echo( "<p>\n" );
		echo( "<form action='" . menu_page_url( 'pihg-seed-boilerplate', $echo = false ) . "' method='POST'>\n" );
		echo( "<strong>Title</strong> <input type='text' size='40' name='sbp_title' default='Add Title Here' value='{$title}' /><br />\n" );
		$args = array(
			'textarea_name' => 'sbp_content',
			'teeny' => true,
			'media_buttons' => false,
		);
		wp_editor( $content, 'sbp_content', $args );
		wp_nonce_field( 'update_sbp', '_sbp_nonce' );
		echo( "<input type='submit' value='Save' name='submit' />\n" );
		echo( "</form>\n" );
		echo( "</p>\n" );

	}

	/**
	 * Load the JS etc that are needed
	 */
	function load_scripts() {
		$handle = 'pihg';
		$src = plugins_url( 'scripts/pihg.jquery.js', __FILE__ );
		$deps = array( 'jquery' );
		$ver = false;
		$in_footer = true;
		wp_register_script($handle, $src, $deps, $ver, $in_footer );
		wp_enqueue_script( $handle );

	}

	/**
	 * Load the CSS files
	 */
	function load_styles() {
		$handle = 'pihg-admin-styles';
		$src = plugins_url( 'css/admin-styles.css', __FILE__ );
		$deps = array( 'cmb-styles' );
		$ver = false;
		wp_register_style( $handle, $src, $deps, $ver );
		wp_enqueue_style( $handle );

	}

	/**
	 * When the version # changes, do some things
	 */
	function on_plugin_update() {
		$saved_version = get_option( '_pihg_plugin_version' );
		if( $saved_version != $this->version ) {
			update_option( '_pihg_plugin_version', $this->version );
			flush_rewrite_rules();
		}
	}

	/**
	 * Debuggery.
	 * @global type $template
	 * @return type
	 */
	function debooger() {
		if( is_admin() ) {
			return;
		}
		global $template;
		echo( "Template: $template<br />\n" );
	}

	function handy_info( $content ) {
		if( current_user_can( 'update_core' ) ) {
			if( is_admin() ) {
				return;
			}
			global $template;
			$content = "<p>Template: $template</p>\n";
		}
		return $content;

	}

	function seed_info_sorter( $a, $b ) {
		if( $a['_pihg_seed_info_year'] == $b['_pihg_seed_info_year'] ) {
			return 0;
		}
		return( $a['_pihg_seed_info_year'] > $b['_pihg_seed_info_year'] ) ? -1 : 1;
	}


	/* Handy functions */

	/**
	 * Display debug info.
	 * @param mixed $x
	 */
	function _dump( $x ) {
		echo( "<pre>\n" );
		if( is_array( $x ) || is_object( $x ) ) {
			print_r( $x );
		} else {
			echo( $x );
		}
		echo( "</pre>\n" );
	}
}

new PIHG();
