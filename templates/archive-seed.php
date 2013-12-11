<?php

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

			<header class="archive-header">
				<h1 class="archive-title">All Seed Types</h1>
			</header><!-- .archive-header -->

			<?php
			if( have_posts() ) {
				$i = 0;
				while( have_posts() ){
					$classes = 'four columns seed-type';
					the_post();
					echo( '<div id="' . $classes . '">' . "\n");
					the_title( '<h1><a href="' . get_permalink() . '">', "</a></h1>\n" );
					if( has_post_thumbnail() ) {
						the_post_thumbnail( 'seed_thumb' );
					}
					the_excerpt();
					echo( "</div>\n" );
					$i++;
				}
			}
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>