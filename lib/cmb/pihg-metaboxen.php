<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'pihg_metaboxen' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function pihg_metaboxen( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_pihg_';

	$meta_boxes['pihg_metabox'] = array(
		'id'         => $prefix . 'seed_meta',
		'title'      => __( 'Seed Information', 'pihg' ),
		'pages'      => array( 'seed', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => false, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			'name'	=> __( 'Seed Info', 'pihg' ),
			'id'	=> $prefix . 'seed_info',
			'type'	=> 'table_seed_info',
		)
	);
	_dump( $meta_boxes );
	return $meta_boxes;
}

add_action( 'cmb_render_table_seed_info', 'pihg_cmb_render_seed_info', 10, 2 );
function pihg_cmb_render_seed_info( $field, $meta ) {
//PA (16:0) 	SA (18:0) 	0A (18:1) 	LA (18:2) 	GLA (18:3) 	ALA (18:3) 	SDA (18:4) 	Avg % Oil Content

	echo <<< EOT
<table>

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
			<th>Avg % Oil</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td><input type="text" name="{$field['id']}[year]" default="Year" /></td>
			<td><input type="text" name="{$field['id']}[PA]" default="PA (16:0)" /></td>
			<td><input type="text" name="{$field['id']}[SA]" default="SA (18:0)" /></td>
			<td><input type="text" name="{$field['id']}[0A]" default="0A (18:1)" /></td>
			<td><input type="text" name="{$field['id']}[LA]" default="LA (18:2)" /></td>
			<td><input type="text" name="{$field['id']}[GLA]" default="GLA (18:3)" /></td>
			<td><input type="text" name="{$field['id']}[ALA]" default="ALA (18:3)" /></td>
			<td><input type="text" name="{$field['id']}[SDA]" default="SDA (18:4)" /></td>
			<td><input type="text" name="{$field['id']}[Oil]" default="Avg % Oil" /></td>
		</tr>
	</tbody>
</table>
EOT;
}

add_filter( 'cmb_validate_table_seed_info', 'pihg_validate_seed_info' );
function pihg_validate_seed_info( $new ) {
	echo( "<pre>" );
	var_dump( $new );
	echo( "</pre>" );
	exit;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
