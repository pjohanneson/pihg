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
	the_post_thumbnail( 'seed_post_thumbnail' );
	echo( "</div>\t<!-- .seed-image -->\n" );

    echo( "<div class='seed-attributes'>\n" );
    the_content();
    echo( "</div>\t<!-- .seed-attributes -->\n" );

	$seed_info = get_post_meta( get_the_ID(), '_pihg_seed_info_table' );
	echo( "<hr />\n" );
	_dump( $seed_info );
	/*
                            	<div class="seed-table">
                                	<h4>Alyssa 2011</h4>
                                    <table class="data-table">
                                        <tbody><tr>
                                            <th>PA (16:0)</th>
                                            <th>SA (18:0)</th>
                                            <th>0A (18:1)</th>
                                            <th>LA (18:2)</th>
                                            <th>GLA (18:3)</th>
                                            <th>ALA (18:3)</th>
                                            <th>SDA (18:4)</th>
                                            <th>Avg % Oil Content</th>
                                        </tr>
                                        <tr>
                                            <td>5.929</td>
                                            <td>2.944</td>
                                            <td>15.548</td>
                                            <td>55.303</td>
                                            <td>3.025</td>
                                            <td>16.197</td>
                                            <td>1.053</td>
                                            <td>24.8</td>
                                        </tr>
                                    </tbody></table>
                            	</div><!-- end seed-table -->

                            </div><!-- end seed container -->
      */





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