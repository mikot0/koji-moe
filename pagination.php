<?php

global $wp_query;

$query_args = array_merge( $wp_query->query, $wp_query->query_vars );

if ( ! array_key_exists( 'max_num_pages', $query_args ) ) {
	$query_args['max_num_pages'] = $wp_query->max_num_pages;
}

if ( ! array_key_exists( 'post_status', $query_args ) ) {
	$query_args['post_status'] = 'publish';
}

if ( ! array_key_exists( 'paged', $query_args ) || 0 == $query_args['paged'] ) {

	$query_args['paged'] = 1;

}

if ( $query_args['max_num_pages'] > $query_args['paged'] ) :

	$json_query_args = wp_json_encode( $query_args ); ?>

	<section class="pagination-wrapper mpad-u-0 mpad-d-80 tpad-d-100 dpad-d-180">

		<div id="pagination" data-query-args="<?php echo esc_attr( $json_query_args ); ?>" data-load-more-target=".load-more-target">

			<button type="button" id="load-more" class="mfs-32 tfs-36 dfs-48 color-dark-gray color-black-hover" aria-controls="posts"><?php _e( '加载更多', '' ); ?></button>

			<p class="out-of-posts" aria-live="polite" aria-relevant="text"><?php _e( '', '' ); ?></p>

			<div class="loading-icon">
				<?php koji_loading_indicator(); ?>
			</div>

			<?php

			$has_previous_link = get_previous_posts_link();
			$has_next_link = get_next_posts_link();

			if ( $has_previous_link || $has_next_link ) :

				if ( ! $has_previous_link ) {
					$pagination_class = ' only-next';
				} else {
					$pagination_class = '';
				}

				?>

				<nav class="link-pagination<?php echo $pagination_class; ?>">

					<?php if ( get_previous_posts_link() ) : ?>
						<?php previous_posts_link( __( '', '' ) ); ?>
					<?php endif; ?>

					<?php if ( get_next_posts_link() ) : ?>
						<?php next_posts_link( __( '', '' ) ); ?>
					<?php endif; ?>

				</nav>

			<?php endif; ?>

		</div>

	</section>

<?php endif; ?>