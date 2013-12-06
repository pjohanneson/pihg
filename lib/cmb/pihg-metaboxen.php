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

	$meta_boxes['pihg_metabox'] = array(
		'id'         => $prefix . 'seed_meta',
		'title'      => __( 'Seed Information', 'pihg' ),
		'pages'      => array( 'seed', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
		'fields'     => array(
			'name'	=> 'Seed Info',
			'id'	=> $prefix . 'seed_info',
			'type'	=> 'table_seed_info',
			'desc'	=> 'Seed Info',
		),
	);

	return $meta_boxes;
}

add_action( 'cmb_render_table_seed_info', 'pihg_cmb_render_seed_info', 10, 2 );
function pihg_cmb_render_seed_info( $field, $meta ) {


	echo( "hi there" );
/*
	echo( '
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
			<td><input type="text" name="' . $field['id'] . '[year]" value="' . $meta['id']['year'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[PA]" value="' . $meta['id']['PA'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[SA]" value="' . $meta['id']['SA'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[0A]" value="' . $meta['id']['0A'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[LA]" value="' . $meta['id']['LA'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[GLA]" value="' . $meta['id']['GLA'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[ALA]" value="' . $meta['id']['ALA'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[SDA]" value="' . $meta['id']['SDA'] . '" /></td>
			<td><input type="text" name="' . $field['id'] . '[Oil]" value="' . $meta['id']['Oil'] . '" /></td>
		</tr>
	</tbody>
</table>
');
*/

}

//add_filter( 'cmb_validate_table_seed_info', 'pihg_validate_seed_info' );
function pihg_validate_seed_info( $new ) {
	echo( "<pre>" );
	var_dump( $new );
	echo( "</pre>" );
	exit;
}

// using some sample code
add_action( 'cmb_render_text_email', 'rrh_cmb_render_text_email', 10, 2 );
function rrh_cmb_render_text_email( $field, $meta ) {
    echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" style="width:97%" />','<p class="cmb_metabox_description">', $field['desc'], '</p>';
}

add_filter( 'cmb_validate_text_email', 'rrh_cmb_validate_text_email' );
function rrh_cmb_validate_text_email( $new ) {
    if ( !is_email( $new ) ) {$new = "";}
    return $new;
}

add_filter( 'cmb_meta_boxes', 'rrh_person_meta_boxes' );
function rrh_person_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array(
		'id' => 'rrh_person_metabox',
		'title' => 'Person Information',
		'pages' => array('seed'),
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => 'Email',
				'id' => 'rrh_person_email',
				'type' => 'text_email',
				'desc' => 'Invalid email addresses will be wiped out.'
			)
		)
	);
	return $meta_boxes;
}

// And done
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}
