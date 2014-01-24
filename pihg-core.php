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
require_once( plugin_dir_path( __FILE__ ) . 'lib/helpers/helpers.php' );
require_once( plugin_dir_path( __FILE__ ) . 'widgets/class-pihg-widgets.php' );

class PIHG {

	var $types = array( 'pihg-seed', 'pihg-contract', );
	var $version = '0.6';

	function __construct() {

		// frontend / global
		add_action( 'init', array( $this, 'post_types' ) );
		add_action( 'init', array( $this, 'on_plugin_update' ) ); // remove once testing is complete!
		add_action( 'pihg_seed_info', array( $this, 'seed_info' ) );

		// load the widgets
		//add_action( 'widgets_init', function(){ register_widget( 'PIHG_News_Widget' ); });

		add_shortcode( 'all-pihg-seeds', array( $this, 'all_seeds' ) );
		add_shortcode( 'all-pihg-contracts', array( $this, 'all_contracts' ) );

		// admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		// add_action( 'admin_menu', array( $this, 'settings_panels' ) );

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
			  'rewrite'            => array( 'slug' => $type, ),
			  'capability_type'    => 'post',
			  'has_archive'        => false,
			  'hierarchical'       => false,
			  'menu_position'      => null,
			  'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt',  )
			);

			register_post_type( $_type, $args );

			// while we're here, let's set some image sizes
			add_image_size( $_type . '-thumb', 300, 200, true );
			add_image_size( $_type . '-featured-image', 300, 999, false );
			add_image_size( $_type . '-page-header', 800, 999, false );
		}

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

		/**
	 * Shortcode for the seed list page.
	 * @return string
	 */
	function all_seeds() {
		$all_seeds = "<div class='row'>\n";
		$args = array(
			'post_type' => 'pihg-seed',
			'posts_per_page' => -1,
		);
		$seeds = new WP_Query( $args );
		if ( $seeds->have_posts() ) {
		$i = 0;
		while ( $seeds->have_posts() ) {
			$seeds->the_post();
			$greek = '';
			if ( $i % 3 == 0 ) {
				if( $i > 0 ) {
					$all_seeds .= "</div>\t<!-- .row -->\n";
					$all_seeds .= "<div class='row'>\n";
				}
				$greek = ' alpha';
			}
			if( $i % 3 == 2 ) {
				$greek = ' omega';
			}
			$all_seeds .= "<div class='four columns $greek seed-type'>\n";
			$all_seeds .= "<div class='entry'>\n";
			$all_seeds .= "<h2 id='post-" .	get_the_ID() .
					"'><a href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>\n";

			if( has_post_thumbnail() ) {
				$all_seeds .= '<a href="' . get_permalink() . '">' .
						get_the_post_thumbnail( get_the_ID(), 'pihg-seed-thumb' ) .
						'</a>' . PHP_EOL;
			}
			$all_seeds .= get_the_excerpt();
			$all_seeds .= "</div><!-- end .entry -->\n";
			$all_seeds .= '</div> <!-- .four columns seed-type -->' . PHP_EOL;
			$i++;
		}	// while have_posts()
		$all_seeds .= "</div>\t<!-- .row -->\n";
		wp_reset_postdata();
	} // if have_posts()

	return $all_seeds;
	}

	/**
	 * Shortcode for the contract list page.
	 * @return string
	 */
	function all_contracts() {
		$all_contracts = "<div class='row'>\n";
		$args = array(
			'post_type' => 'pihg-contract',
			'posts_per_page' => -1,
		);
		$contracts = new WP_Query( $args );
		if ( $contracts->have_posts() ) {
			while ( $contracts->have_posts() ) {
				$contracts->the_post();
				
				$all_contracts .= "<div class='entry'>\n";
				$all_contracts .= "<h2 id='post-" .	get_the_ID() .
						"'><a href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>\n";
				$all_contracts .= '<div class="five columns alpha">' . PHP_EOL;
				if( has_post_thumbnail() ) {
					$all_contracts .= '<a href="' . get_permalink() . '">' .
							get_the_post_thumbnail( get_the_ID(), 'pihg-contract-thumb' ) .
							'</a>' . PHP_EOL;
				}
				$all_contracts .= '</div>	<!-- .five columns alpha -->' . PHP_EOL;
				$all_contracts .= '<div class="seven columns omega">' . get_the_excerpt() .
						'</div>	<!-- .seven columns omega -->' . PHP_EOL;
				$all_contracts .= "</div><!-- end .entry -->\n";
			}	// while have_posts()
			$all_contracts .= "</div>\t<!-- .row -->\n";
			wp_reset_postdata();
		} // if have_posts()

		return $all_contracts;
	}

	function settings_panels() {
		foreach( $this->types as $_type ) {
			$type = str_replace( 'pihg-', '', $_type );
			add_submenu_page(
					'edit.php?post_type=' . $_type,
					ucfirst( $type ) . ' Settings',
					ucfirst( $type ) . ' Settings',
					'edit_posts',
					$type . '-settings',
					array( $this, $type . '_archive_settings' )
			);
		}
	}

	function seed_archive_settings() {
		if( $_POST ) {
			check_admin_referer( 'update_sas', '_sas_nonce' );
			$parent_id = esc_attr( $_POST['sas_parent_id'] );
			update_option( '_pihg_sas', array( 'parent_id' => $parent_id, ) );
		}
		$settings = get_option( '_pihg_sas' );
		if( $settings ) {
			extract( $settings );
		}
		echo( "<h1>Seed Settings</h1>\n" );
		// get the pages
		$args = array(
			'post_type' => 'page',
			'posts_per_page' => -1,
			'orderby' => 'post_title',
			'order' => 'ASC',
		);
		$pages = get_posts( $args );
		if( $pages ) {
			$page_list = '<select name="sas_parent_id">' . PHP_EOL;
			foreach( $pages as $page ) {
				$page_list .= "<option value='{$page->ID}'";
				if( isset( $parent_id ) && $page->ID == $parent_id ) {
					$page_list .= " selected='selected'";
				}
				$page_list .= ">{$page->post_title}</option>" . PHP_EOL;
			}
			$page_list .= '</select>' . PHP_EOL;
		}

		echo( "<p>\n" );
		echo( "<form action='" . menu_page_url( 'pihg-seed-settings', $echo = false ) . "' method='POST'>\n" );
		echo( "<strong>Select Seed Archive Page</strong>" . PHP_EOL );
		if( $page_list ) {
			echo( $page_list );
		}
		wp_nonce_field( 'update_sas', '_sas_nonce' );
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
