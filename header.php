<!DOCTYPE html>

<?php

$html_class = is_admin_bar_showing() ? ' showing-admin-bar' : ''; ?>

<html class="no-js<?php echo $html_class; ?>" <?php language_attributes(); ?>>

<head>

	<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>" charset="<?php bloginfo('charset'); ?>" />
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<?php
	if (function_exists('wp_body_open')) {
		wp_body_open();
	}
	?>

	<div id="site-wrapper">

		<header id="site-header" role="banner">

			<a class="skip-link" href="#site-content"><?php _e('', ''); ?></a>
			<a class="skip-link" href="#main-menu"><?php _e('', ''); ?></a>

			<div class="header-top section-inner">

				<?php

				if (function_exists('the_custom_logo') && get_theme_mod('custom_logo')):

					koji_custom_logo();

				elseif (is_front_page() || is_home()): ?>

					<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
					</h1>

				<?php else: ?>

					<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></p>

				<?php endif; ?>

				<button type="button" aria-pressed="false" class="toggle nav-toggle"
					data-toggle-target=".mobile-menu-wrapper" data-toggle-scroll-lock="true" data-toggle-attribute="">
					<label>
						<span class="show">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
								stroke="currentColor" class="size-6" style="width: 32px;">
								<path stroke-linecap="round" stroke-linejoin="round"
									d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
							</svg>
							<?php _e('', ''); ?></span>
						<span class="hide">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
								stroke="currentColor" class="size-6" style="width: 32px;">
								<path stroke-linecap="round" stroke-linejoin="round"
									d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z" />
							</svg>
							<?php _e('', ''); ?></span>
					</label>
				</button>

			</div>

			<div class="header-inner section-inner">

				<div class="header-inner-top">

					<?php if (get_bloginfo('description')): ?>

						<p class="site-description"><?php echo wp_kses_post(get_bloginfo('description')); ?></p>

					<?php endif; ?>

					<!-- <ul class="site-nav reset-list-style" id="main-menu" role="navigation">
							<?php
							if (has_nav_menu('primary-menu')) {
								wp_nav_menu(array(
									'container' => '',
									'items_wrap' => '%3$s',
									'theme_location' => 'primary-menu',
								));
							} else {
								wp_list_pages(array(
									'container' => '',
									'title_li' => '',
								));
							}
							?>
						</ul> -->

					<?php if (is_active_sidebar('sidebar')): ?>

						<div class="sidebar-widgets">
							<?php dynamic_sidebar('sidebar'); ?>
						</div>

					<?php endif; ?>

				</div>

				<div class="social-menu-wrapper">

					<?php

					$disable_search = get_theme_mod('koji_disable_search') ? get_theme_mod('koji_disable_search') : false;
					$show_social_menu = has_nav_menu('social') || !$disable_search;

					if ($show_social_menu): ?>

						<ul class="social-menu reset-list-style social-icons">

							<?php if (!$disable_search): ?>

								<li class="search-toggle-wrapper"><button type="button" aria-pressed="false"
										data-toggle-target=".search-overlay" data-set-focus=".search-overlay .search-field"
										class="toggle search-toggle"><span
											class="screen-reader-text"><?php _e('', ''); ?></span></button>
								</li>

								<?php
							endif;

							$social_menu_args = array(
								'theme_location' => 'social',
								'container' => '',
								'container_class' => '',
								'items_wrap' => '%3$s',
								'menu_id' => '',
								'menu_class' => '',
								'depth' => 1,
								'link_before' => '<span class="screen-reader-text">',
								'link_after' => '</span>',
								'fallback_cb' => '',
							);

							wp_nav_menu($social_menu_args);

							?>

						</ul>

					<?php endif; ?>

				</div>

			</div>

		</header>

		<div class="mobile-menu-wrapper" aria-expanded="false">

			<div class="mobile-menu section-inner">

				<div class="mobile-menu-top">

					<?php if (get_bloginfo('description')): ?>

						<p class="site-description"><?php echo wp_kses_post(get_bloginfo('description')); ?></p>

					<?php endif; ?>

					<!-- <ul class="site-nav reset-list-style" id="mobile-menu" role="navigation">
						<?php
						if (has_nav_menu('mobile-menu')) {
							wp_nav_menu(array(
								'container' => '',
								'items_wrap' => '%3$s',
								'theme_location' => 'mobile-menu',
							));
						} else {
							wp_list_pages(array(
								'container' => '',
								'title_li' => '',
							));
						}
						?>
					</ul> -->

					<?php if (is_active_sidebar('sidebar')): ?>

						<div class="sidebar-widgets">
							<?php dynamic_sidebar('sidebar'); ?>
						</div>

					<?php endif; ?>

				</div>

				<div class="social-menu-wrapper">

					<?php if ($show_social_menu): ?>

						<ul class="social-menu reset-list-style social-icons mobile">

							<?php if (!$disable_search): ?>

								<li class="search-toggle-wrapper"><button type="button" aria-pressed="false"
										data-toggle-target=".search-overlay" data-set-focus=".search-overlay .search-field"
										class="toggle search-toggle"><span
											class="screen-reader-text"><?php _e('', ''); ?></span></button>
								</li>

								<?php
							endif;

							wp_nav_menu($social_menu_args); ?>

						</ul>

					<?php endif; ?>

				</div>

			</div>

		</div>

		<?php if (!$disable_search): ?>

			<div class="search-overlay cover-modal" aria-expanded="false">

				<div class="section-inner search-overlay-form-wrapper">
					<?php echo get_search_form(); ?>
				</div>

				<button type="button" class="toggle search-untoggle" data-toggle-target=".search-overlay"
					data-set-focus=".search-toggle:visible">
					<div class="search-untoggle-inner">
						<img aria-hidden="true"
							src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/x-mark.svg" />
					</div>
					<span class="screen-reader-text"><?php _e('', ''); ?></span>
				</button>

			</div>

		<?php endif; ?>