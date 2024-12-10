			<footer id="site-footer" role="contentinfo">

				<?php if ( is_active_sidebar( 'footer-one' ) || is_active_sidebar( 'footer-two' ) || is_active_sidebar( 'footer-three' ) ) : ?>

					<div class="footer-widgets-outer-wrapper section-inner">

						<div class="footer-widgets-wrapper">

							<div class="footer-widgets">
								<?php dynamic_sidebar( 'footer-one' ); ?>
							</div>

							<div class="footer-widgets">
								<?php dynamic_sidebar( 'footer-two' ); ?>
							</div>

							<div class="footer-widgets">
								<?php dynamic_sidebar( 'footer-three' ); ?>
							</div>

						</div>

					</div>

				<?php endif; ?>

				<p class="credits">
					<?php
					printf( _x( '<a href="https://qingchun.love/"><img src="https://qingchun.love/image/index/logo.png" style="width: 123px; margin: auto;"></a> %s', 'Translators: $s = name of the theme developer', '' ), '<a href="https://">' . __( '', '' ) . '</a>' ); ?>
				</p>

			</footer>
			
			<?php wp_footer(); ?>

		</div>

	</body>
</html>