<?php

get_header(); ?>

	<section id="primary" class="site-content">
		<div id="content" role="main">

			<header class="archive-header">
				<h1 class="archive-title">All Seed Types</h1>
			</header><!-- .archive-header -->

			<?php
			if( have_posts() ) {
				while( have_posts() ){
					the_post();
					if( has_post_thumbnail() ) {
						the_post_thumbnail();
					}
					the_title();
					the_excerpt();
				}
			}
			?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>