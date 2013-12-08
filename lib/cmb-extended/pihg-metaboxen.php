<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'pihg_metaboxen', 20 );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function pihg_metaboxen( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_pihg_';

	// set up the fields
	$seed_info_fields = array(
		array( 'id' => 'year',  'name' => 'Year', 'type' => 'text_small' ),
		array( 'id' => 'pa_16_0', 'name' => 'PA (16:0)', 'type' => 'text_small' ),
	);

	$meta_boxes[] = array(
		'id'         => $prefix . 'seed_meta',
		'title'      => __( 'Seed Information', 'pihg' ),
		'pages'      => array( 'seed', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'repeatable' => true,
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			array(
				'name'	=> 'Seed Info',
				'id'	=> $prefix . 'seed_info',
				'type'	=> 'group',
				'desc'	=> 'Seed Info',
				'fields'	=> $seed_info_fields,
			),
		),
	);

	return $meta_boxes;
}

