<?php get_header(); ?>

<main id="site-content" role="main">

	<header class="single-container bg-color-white">

		<div class="post-inner section-inner">

			<h1><?php _e( '', '' ); ?></h1>

			<p class="sans-excerpt"><?php _e( "", '' ); ?></p>

			<a class="go-home" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( '', '' ); ?> &rarr;</a>

		</div>

	</header>

</main>

<?php get_footer(); ?>