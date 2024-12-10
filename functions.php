<?php

if (!function_exists('koji_setup')):
	function koji_setup()
	{

		add_theme_support('automatic-feed-links');

		add_theme_support('custom-background');

		global $content_width;
		$content_width = 560;

		add_theme_support('post-thumbnails');

		set_post_thumbnail_size(1870, 9999);

		add_image_size('koji_preview_image_low_resolution', 400, 9999, false);
		add_image_size('koji_preview_image_high_resolution', 800, 9999, false);

		add_theme_support('custom-logo', array(
			'height' => 200,
			'width' => 600,
			'flex-height' => true,
			'flex-width' => true,
			'header-text' => array('site-title', 'site-description'),
		));

		add_theme_support('title-tag');

		register_nav_menu('primary-menu', __('', ''));
		register_nav_menu('mobile-menu', __('', ''));
		register_nav_menu('social', __('', ''));

		add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

		load_theme_textdomain('', get_template_directory() . '/languages');

	}
	add_action('after_setup_theme', 'koji_setup');
endif;

require get_template_directory() . '/inc/classes/class-koji-customize.php';

if (!function_exists('koji_load_style')):
	function koji_load_style()
	{

		if (is_admin())
			return;

		wp_enqueue_style('koji-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme('')->get('Version'));

	}
	add_action('wp_enqueue_scripts', 'koji_load_style');
endif;

if (!function_exists('koji_add_editor_styles')):
	function koji_add_editor_styles()
	{

		add_editor_style(array(
			'/assets/css/koji-classic-editor-styles.css',
		));

	}
	add_action('init', 'koji_add_editor_styles');
endif;

if (!function_exists('koji_enqueue_scripts')):
	function koji_enqueue_scripts()
	{

		wp_enqueue_script('koji_construct', get_template_directory_uri() . '/assets/js/construct.js', array('jquery', 'imagesloaded', 'masonry'), wp_get_theme('')->get('Version'), true);

		if ((!is_admin()) && is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		$ajax_url = admin_url('admin-ajax.php');

		wp_localize_script('koji_construct', 'koji_ajax_load_more', array(
			'ajaxurl' => esc_url($ajax_url),
		));

	}
	add_action('wp_enqueue_scripts', 'koji_enqueue_scripts');
endif;

if (!function_exists('koji_post_classes')):
	function koji_post_classes($classes)
	{

		global $post;

		$classes[] = (has_post_thumbnail() ? 'has-thumbnail' : 'missing-thumbnail');

		return $classes;

	}
	add_action('post_class', 'koji_post_classes');
endif;

if (!function_exists('koji_body_classes')):
	function koji_body_classes($classes)
	{

		global $post;

		$pagination_type = get_theme_mod('koji_pagination_type') ? get_theme_mod('koji_pagination_type') : 'button';
		switch ($pagination_type) {
			case 'button':
				$classes[] = 'pagination-type-button';
				break;
			case 'scroll':
				$classes[] = 'pagination-type-scroll';
				break;
			case 'links':
				$classes[] = 'pagination-type-links';
				break;
		}

		if (is_singular() && has_post_thumbnail()) {
			$classes[] = 'has-post-thumbnail';
		} elseif (is_singular()) {
			$classes[] = 'missing-post-thumbnail';
		}

		if (is_customize_preview()) {
			$classes[] = 'customizer-preview';
		}

		if (is_page_template()) {
			$classes[] = preg_replace('/\\.[^.\\s]{3,4}$/', '', get_page_template_slug($post->ID));
		}

		return $classes;

	}
	add_action('body_class', 'koji_body_classes');
endif;

if (!function_exists('koji_has_js')):
	function koji_has_js()
	{

		?>
		<script>document.documentElement.className = document.documentElement.className.replace('no-js', 'js');</script>
		<?php

	}
	add_action('wp_head', 'koji_has_js');
endif;

if (!function_exists('koji_custom_logo')):
	function koji_custom_logo()
	{

		$logo_id = get_theme_mod('custom_logo');

		$logo = wp_get_attachment_image_src($logo_id, 'full');

		if ($logo) {

			$logo_url = esc_url($logo[0]);
			$logo_width = esc_attr($logo[1]);
			$logo_height = esc_attr($logo[2]);

			if (get_theme_mod('koji_retina_logo')) {
				$logo_width = floor($logo_width / 2);
				$logo_height = floor($logo_height / 2);
			}

			$logo_alt_text = get_post_meta($logo_id, '_wp_attachment_image_alt', true) ? get_post_meta($logo_id, '_wp_attachment_image_alt', true) : get_bloginfo('site-title');

			?>

			<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>" class="custom-logo-link">
				<img src="<?php echo esc_url($logo_url); ?>" width="<?php echo esc_attr($logo_width); ?>"
					height="<?php echo esc_attr($logo_height); ?>" alt="<?php echo esc_attr($logo_alt_text); ?>" />
			</a>

			<?php
		}

	}
endif;

if (!function_exists('koji_widget_areas')):
	function koji_widget_areas()
	{

		register_sidebar(array(
			'name' => __('', ''),
			'id' => 'sidebar',
			'description' => __('', ''),
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget' => '</div><div class="clear"></div></div>',
		));

		register_sidebar(array(
			'name' => __('', ''),
			'id' => 'footer-one',
			'description' => __('', ''),
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget' => '</div><div class="clear"></div></div>',
		));

		register_sidebar(array(
			'name' => __('', ''),
			'id' => 'footer-two',
			'description' => __('', ''),
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget' => '</div><div class="clear"></div></div>',
		));

		register_sidebar(array(
			'name' => __('', ''),
			'id' => 'footer-three',
			'description' => __('', ''),
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content">',
			'after_widget' => '</div><div class="clear"></div></div>',
		));

	}
	add_action('widgets_init', 'koji_widget_areas');
endif;

if (!function_exists('koji_remove_archive_title_prefix')):
	function koji_remove_archive_title_prefix($title)
	{

		if (is_category()) {
			$title = single_cat_title('', false);
		} elseif (is_tag()) {
			$title = single_tag_title('', false);
		} elseif (is_author()) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif (is_year()) {
			$title = get_the_date('Y');
		} elseif (is_month()) {
			$title = get_the_date('F Y');
		} elseif (is_day()) {
			$title = get_the_date(get_option('date_format'));
		} elseif (is_tax('post_format')) {
			if (is_tax('post_format', 'post-format-aside')) {
				$title = _x('Asides', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-gallery')) {
				$title = _x('Galleries', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-image')) {
				$title = _x('Images', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-video')) {
				$title = _x('Videos', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-quote')) {
				$title = _x('Quotes', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-link')) {
				$title = _x('Links', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-status')) {
				$title = _x('Statuses', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-audio')) {
				$title = _x('Audio', 'post format archive title', '');
			} elseif (is_tax('post_format', 'post-format-chat')) {
				$title = _x('Chats', 'post format archive title', '');
			}
		} elseif (is_post_type_archive()) {
			$title = post_type_archive_title('', false);
		} elseif (is_tax()) {
			$title = single_term_title('', false);
		} elseif (is_search()) {
			$title = '&ldquo;' . get_search_query() . '&rdquo;';
		} else {
			$title = __('', '');
		}
		return $title;

	}
	add_filter('get_the_archive_title', 'koji_remove_archive_title_prefix');
endif;

if (!function_exists('koji_get_archive_title_prefix')):
	function koji_get_archive_title_prefix()
	{

		if (is_category()) {
			$title_prefix = __('', '');
		} elseif (is_tag()) {
			$title_prefix = __('', '');
		} elseif (is_author()) {
			$title_prefix = __('', '');
		} elseif (is_year()) {
			$title_prefix = __('', '');
		} elseif (is_month()) {
			$title_prefix = __('', '');
		} elseif (is_day()) {
			$title_prefix = __('', '');
		} elseif (is_tax()) {
			$tax = get_taxonomy(get_queried_object()->taxonomy);
			$title_prefix = $tax->labels->singular_name;
		} elseif (is_search()) {
			$title_prefix = __('', '');
		} else {
			$title_prefix = __('', '');
		}
		return $title_prefix;

	}
endif;

if (!function_exists('koji_get_fallback_image_url')):
	function koji_get_fallback_image_url()
	{

		$disable_fallback_image = get_theme_mod('koji_disable_fallback_image');

		if ($disable_fallback_image) {
			return '';
		}

		$fallback_image_id = get_theme_mod('koji_fallback_image');

		if ($fallback_image_id) {
			$fallback_image = wp_get_attachment_image_src($fallback_image_id, 'full');
		}

		$fallback_image_url = isset($fallback_image) ? esc_url($fallback_image[0]) : get_template_directory_uri() . '';

		return $fallback_image_url;

	}
endif;

if (!function_exists('koji_the_fallback_image')):
	function koji_the_fallback_image()
	{

		$fallback_image_url = koji_get_fallback_image_url();

		if (!$fallback_image_url) {
			return;
		}

		echo '<img class="fallback-image" src="' . $fallback_image_url . '" alt="' . __('', '') . '" />';

	}
endif;

if (!function_exists('koji_get_preview_image_size')):
	function koji_get_preview_image_size()
	{

		$low_res_images = get_theme_mod('koji_activate_low_resolution_images');

		if ($low_res_images) {
			return 'koji_preview_image_low_resolution';

		} else {
			return 'koji_preview_image_high_resolution';
		}

	}
endif;

if (!function_exists('koji_the_post_meta')):
	function koji_the_post_meta($post_id = null, $location = 'single')
	{

		echo koji_get_post_meta($post_id, $location);

	}
endif;

if (!function_exists('koji_get_post_meta')):
	function koji_get_post_meta($post_id = null, $location = 'single')
	{

		if (!$post_id) {
			return;
		}

		$allowed_post_types = apply_filters('koji_allowed_post_types_for_meta_output', array('post'));
		if (!in_array(get_post_type($post_id), $allowed_post_types)) {
			return;
		}

		$post_meta_wrapper_classes = '';
		$post_meta_classes = '';

		if ('preview' === $location) {
			$post_meta = get_theme_mod('koji_post_meta_preview');

			$post_meta_wrapper_classes = ' post-meta-preview';

			if (!$post_meta) {
				$post_meta = array(
					'post-date',
					'comments',
				);
			}
		} else {
			$post_meta = get_theme_mod('koji_post_meta_single');

			$post_meta_wrapper_classes = ' post-meta-single';
			$post_meta_classes = ' stack-mobile';

			if (!$post_meta) {
				$post_meta = array(
					'post-date',
					'categories',
				);
			}
		}

		if ($post_meta && !in_array('empty', $post_meta)):

			ob_start();

			setup_postdata($post_id);

			?>

			<div class="card post-meta-wrapper<?php echo $post_meta_wrapper_classes; ?>">

				<ul class="post-meta<?php echo $post_meta_classes; ?>">

					<?php

					if (in_array('post-date', $post_meta)): ?>
						<li class="post-date">
							<a class="meta-wrapper" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<span class="screen-reader-text"><?php _e('', ''); ?></span>
								<div class="meta-icon">
									<img aria-hidden="true"
										src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/calendar-days.svg" />
								</div>
								<span class="meta-content"><?php the_time(get_option('date_format')); ?></span>
							</a>
						</li>
					<?php endif;

					if (in_array('post-date', $post_meta)): ?>
						<li class="post-categories meta-wrapper">
							<div class="meta-icon"><img aria-hidden="true"
									src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/folder.svg" />
							</div>
							<span class="screen-reader-text"><?php _e('', ''); ?></span>
							<span class="meta-content"><?php the_category(', '); ?></span>
						</li>
					<?php endif;

					if (in_array('post-date', $post_meta)): ?>
						<li class="post-tags meta-wrapper">
							<div class="meta-icon"><img aria-hidden="true"
									src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/tag.svg" />
							</div>
							<span class="screen-reader-text"><?php _e('', ''); ?></span>
							<span class="meta-content"><?php the_tags('', ', ', ''); ?></span>
						</li>
						<?php
					endif;

					if (in_array('author', $post_meta)): ?>
						<li class="post-author">
							<span class="screen-reader-text"><?php _e('', ''); ?></span>
							<a class="meta-wrapper" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
								<div class="meta-icon"><img aria-hidden="true"
										src="<?php echo esc_url(get_template_directory_uri()); ?>" />
								</div>
								<span class="meta-content"><?php the_author_meta('nickname'); ?></span>
							</a>
						</li>
						<?php
					endif;

					if (in_array('tags', $post_meta) && has_tag()): ?>
						<li class="post-tags meta-wrapper">
							<div class="meta-icon"><img aria-hidden="true" src="<?php echo esc_url(get_template_directory_uri()); ?>" />
							</div>
							<span class="screen-reader-text"><?php _e('', ''); ?></span>
							<span class="meta-content"><?php the_tags('', ', ', ''); ?></span>
						</li>
						<?php
					endif;

					if (in_array('comments', $post_meta) && comments_open()): ?>
						<li class="post-comment-link">
							<a class="meta-wrapper" href="<?php echo esc_url(get_comments_link($post_id)); ?>">
								<span class="screen-reader-text"><?php _e('', ''); ?></span>
								<div class="meta-icon">
									<img aria-hidden="true"
										src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/chat-bubble-oval-left.svg" />
								</div>
								<span class="meta-content"><?php echo get_comments_number(); ?></span>
							</a>
						</li>
						<?php
					endif;

					if (in_array('sticky', $post_meta) && is_sticky()): ?>
						<li class="post-sticky meta-wrapper">
							<div class="meta-icon"><img aria-hidden="true" src="<?php echo esc_url(get_template_directory_uri()); ?>" />
							</div>
							<span class="meta-content"><?php _e('', ''); ?></span>
						</li>
					<?php endif;

					if (in_array('edit-link', $post_meta) && current_user_can('edit_post', get_the_ID())): ?>
						<li class="edit-post">

							<?php
							if (is_customize_preview()) { ?>
								<div class="meta-wrapper">
									<div class="meta-icon"><img aria-hidden="true"
											src="<?php echo esc_url(get_template_directory_uri()); ?>" />
									</div>
									<span class="meta-content"><?php _e('', ''); ?></span>
								</div>
								<?php
							} else {
								echo '<a href="' . esc_url(get_edit_post_link()) . '" class="meta-wrapper"><div class="meta-icon">';
								echo '<img aria-hidden="true" src="' . get_template_directory_uri() . '' . '" />';
								echo '</div>';
								echo '<span class="meta-content">' . __('', '') . '</span>';
								echo '</a>';
							}
							?>

						</li>
					<?php endif; ?>

				</ul>

			</div>

			<?php

			$post_meta_contents = ob_get_clean();

			wp_reset_postdata();

			return $post_meta_contents;

		endif;

		return;

	}
endif;

if (class_exists('WP_Customize_Control')):
	if (!class_exists('Koji_Customize_Control_Checkbox_Multiple')):

		class Koji_Customize_Control_Checkbox_Multiple extends WP_Customize_Control
		{

			public $type = 'checkbox-multiple';

			public function render_content()
			{

				if (empty($this->choices)):
					return;
				endif;

				if (!empty($this->label)): ?>
					<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
				<?php endif;

				if (!empty($this->description)): ?>
					<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
				<?php endif;

				$multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value(); ?>

				<ul>
					<?php foreach ($this->choices as $value => $label): ?>

						<li>
							<label>
								<input type="checkbox" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?> />
								<?php echo esc_html($label); ?>
							</label>
						</li>

					<?php endforeach; ?>
				</ul>

				<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $multi_values)); ?>" />
				<?php
			}
		}

	endif;
endif;

if (!function_exists('koji_filter_comment_text')):
	function koji_filter_comment_text($comment_text, $comment, $args)
	{

		if (empty($comment) || !isset($comment->user_id))
			return $comment_text;

		$comment_author_user_id = $comment->user_id;
		$post_author_user_id = get_post_field('post_author', $comment->comment_post_ID);

		if ($comment_author_user_id === $post_author_user_id) {
			$comment_text .= '<div class="by-post-author-wrapper">' . __('', '') . '</div>';
		}

		return $comment_text;

	}
	add_filter('comment_text', 'koji_filter_comment_text', 10, 3);
endif;

if (!function_exists('koji_loading_indicator')):
	function koji_loading_indicator()
	{

		echo '<div class="loader"></div>';

	}
endif;

if (!function_exists('koji_ajax_load_more')):
	function koji_ajax_load_more()
	{

		$query_args = json_decode(wp_unslash($_POST['json_data']), true);

		$ajax_query = new WP_Query($query_args);

		$post_type = $ajax_query->get('post_type');

		if (is_array($post_type)) {
			$post_type = 'post';
		}

		if ($ajax_query->have_posts()):

			while ($ajax_query->have_posts()):
				$ajax_query->the_post();

				get_template_part('preview', $post_type);

			endwhile;

		endif;

		die();

	}
	add_action('wp_ajax_nopriv_koji_ajax_load_more', 'koji_ajax_load_more');
	add_action('wp_ajax_koji_ajax_load_more', 'koji_ajax_load_more');
endif;

if (!function_exists('koji_add_gutenberg_features')):
	function koji_add_gutenberg_features()
	{

		add_theme_support('align-wide');


		add_theme_support('editor-color-palette', array(
			array(
				'name' => _x('Black', 'Name of the black color in the Gutenberg palette', ''),
				'slug' => 'black',
				'color' => '#232D37',
			),
			array(
				'name' => _x('Darkest gray', 'Name of the darkest gray color in the Gutenberg palette', ''),
				'slug' => 'darkest-gray',
				'color' => '#4B555F',
			),
			array(
				'name' => _x('Darker Gray', 'Name of the darker gray color in the Gutenberg palette', ''),
				'slug' => 'darker-gray',
				'color' => '#69737D',
			),
			array(
				'name' => _x('Gray', 'Name of the gray color in the Gutenberg palette', ''),
				'slug' => 'gray',
				'color' => '#9BA5AF',
			),
			array(
				'name' => _x('Light gray', 'Name of the light gray color in the Gutenberg palette', ''),
				'slug' => 'light-gray',
				'color' => '#DCDFE2',
			),
			array(
				'name' => _x('Lightest gray', 'Name of the lightest gray color in the Gutenberg palette', ''),
				'slug' => 'lightest-gray',
				'color' => '#E6E9EC',
			),
			array(
				'name' => _x('White', 'Name of the white color in the Gutenberg palette', ''),
				'slug' => 'white',
				'color' => '#FFF',
			),
		));

		add_theme_support('editor-font-sizes', array(
			array(
				'name' => _x('Small', 'Name of the small font size in Gutenberg', ''),
				'shortName' => _x('S', 'Short name of the small font size in the Gutenberg editor.', ''),
				'size' => 16,
				'slug' => 'small',
			),
			array(
				'name' => _x('Normal', 'Name of the regular font size in Gutenberg', ''),
				'shortName' => _x('N', 'Short name of the regular font size in the Gutenberg editor.', ''),
				'size' => 19,
				'slug' => 'normal',
			),
			array(
				'name' => _x('Large', 'Name of the large font size in Gutenberg', ''),
				'shortName' => _x('L', 'Short name of the large font size in the Gutenberg editor.', ''),
				'size' => 24,
				'slug' => 'large',
			),
			array(
				'name' => _x('Larger', 'Name of the larger font size in Gutenberg', ''),
				'shortName' => _x('XL', 'Short name of the larger font size in the Gutenberg editor.', ''),
				'size' => 32,
				'slug' => 'larger',
			),
		));

	}
	add_action('after_setup_theme', 'koji_add_gutenberg_features');
endif;

if (!function_exists('koji_block_editor_styles')):
	function koji_block_editor_styles()
	{

		wp_enqueue_style('koji-block-editor-styles', get_template_directory_uri() . '/assets/css/koji-block-editor-styles.css', array(), '1.0', 'all');

	}
	add_action('enqueue_block_editor_assets', 'koji_block_editor_styles', 1);
endif;