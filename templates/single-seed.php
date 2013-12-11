<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header();

while( have_posts() ) {
	the_post();
	the_title( '<h3>', "</h3>\n" );

	echo( '<div class="seed-image">' );
	the_post_thumbnail( 'seed_featured_image' );
	echo( "</div>\t<!-- .seed-image -->\n" );

    echo( "<div class='seed-attributes'>\n" );
    the_content();
    echo( "</div>\t<!-- .seed-attributes -->\n" );

	$seed_info = get_post_meta( get_the_ID(), '_pihg_seed_info_table' );
	if( is_array( $seed_info ) && ! empty( $seed_info ) ) {
		usort( $seed_info, '_pihg_seed_table_sorter' );
		echo( "<div class='seed-table'>\n" );
		echo( "<table class='data-table'>\n" );
		echo( "<thead>\n" );
		echo( "<tr>\n" );
		echo( "<th>Year</th>\n" );
		echo( "<th>PA (16:0)</th>\n" );
        echo( "<th>SA (18:0)</th>\n" );
        echo( "<th>0A (18:1)</th>\n" );
        echo( "<th>LA (18:2)</th>\n" );
        echo( "<th>GLA (18:3)</th>\n" );
        echo( "<th>ALA (18:3)</th>\n" );
        echo( "<th>SDA (18:4)</th>\n" );
		echo( "<th>Avg % Oil Content</th>\n" );
		echo( "</thead>\n" );

		echo( "<tbody>\n" );
		foreach( $seed_info as $seed_row ) {
			echo( "<tr>\n" );
			foreach( $seed_row as $key => $value ) {
				echo("<td>$value</td>\n" );
			}
			echo( "</tr>\n" );
		}
		echo( "</tbody>\n" );
		echo( "</table>\n" );
	}

	echo( "</div><!-- end seed-table -->\n" );
	echo( "</div><!-- end seed container -->\n" );

echo('
			</div><!-- end .entry -->

        	</div> <!-- end sixteen columns -->

            </div>

        </div> <!-- containter -->

	</section> <!-- section:first-row -->
	');
}
?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>