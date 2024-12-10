<?php

if ( ! class_exists( 'Koji_Customize' ) ) :
	class Koji_Customize {

		public static function koji_register( $wp_customize ) {

			$wp_customize->add_setting( 'koji_retina_logo', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'koji_sanitize_checkbox',
				'transport'			=> 'postMessage',
			) );

			$wp_customize->add_control( 'koji_retina_logo', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'title_tagline',
				'priority'		=> 10,
				'label' 		=> __( '', '' ),
				'description' 	=> __( '', '' ),
			) );

			$wp_customize->add_section( 'koji_image_options', array(
				'title' 		=> __( '', '' ),
				'priority' 		=> 40,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( '', '' ),
			) );

			$wp_customize->add_setting( 'koji_activate_low_resolution_images', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'koji_sanitize_checkbox'
			) );

			$wp_customize->add_control( 'koji_activate_low_resolution_images', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'koji_image_options',
				'priority'		=> 5,
				'label' 		=> __( '', '' ),
				'description'	=> __( '', '' ),
			) );

			$wp_customize->add_setting( 'koji_fallback_image', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'absint'
			) );

			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'koji_fallback_image', array(
				'label'			=> __( '', '' ),
				'description'	=> __( '', '' ),
				'priority'		=> 10,
				'mime_type'		=> 'image',
				'section' 		=> 'koji_image_options',
			) ) );

			$wp_customize->add_setting( 'koji_disable_fallback_image', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'koji_sanitize_checkbox'
			) );

			$wp_customize->add_control( 'koji_disable_fallback_image', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'koji_image_options',
				'priority'		=> 15,
				'label' 		=> __( '', '' )
			) );

			$wp_customize->add_section( 'koji_post_meta_options', array(
				'title' 		=> __( '', '' ),
				'priority' 		=> 41,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( '', '' ),
			) );

			$post_meta_choices = apply_filters( 'koji_post_meta_choices_in_the_customizer', array(
				'author'		=> __( '', '' ),
				'categories'	=> __( '', '' ),
				'comments'		=> __( '', '' ),
				'edit-link'		=> __( '', '' ),
				'post-date'		=> __( '', '' ),
				'sticky'		=> __( '', '' ),
				'tags'			=> __( '', '' ),
			) );

			$wp_customize->add_setting( 'koji_post_meta_single', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'post-date', 'categories' ),
				'sanitize_callback' => 'koji_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new Koji_Customize_Control_Checkbox_Multiple( $wp_customize, 'koji_post_meta_single', array(
				'section' 		=> 'koji_post_meta_options',
				'label'   		=> __( '', '' ),
				'description'	=> __( '', '' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			$wp_customize->add_setting( 'koji_post_meta_preview', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'post-date', 'comments' ),
				'sanitize_callback' => 'koji_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new Koji_Customize_Control_Checkbox_Multiple( $wp_customize, 'koji_post_meta_preview', array(
				'section' 		=> 'koji_post_meta_options',
				'label'   		=> __( '', '' ),
				'description'	=> __( '', '' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			$wp_customize->add_section( 'koji_pagination_options', array(
				'title' 		=> __( '', '' ),
				'priority' 		=> 45,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( '', '' ),
			) );

			$wp_customize->add_setting( 'koji_pagination_type', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => 'button',
				'sanitize_callback' => 'koji_sanitize_radio',
			) );

			$wp_customize->add_control( 'koji_pagination_type', array(
				'type'			=> 'radio',
				'section' 		=> 'koji_pagination_options',
				'label'   		=> __( '', '' ),
				'choices' 		=> array(
					'button'		=> __( '', '' ),
					'scroll'		=> __( '', '' ),
					'links'			=> __( '', '' ),
				),
			) );

			$wp_customize->add_section( 'koji_search_options', array(
				'title' 		=> __( '', '' ),
				'priority' 		=> 50,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> '',
			) );

			$wp_customize->add_setting( 'koji_disable_search', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'koji_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'koji_disable_search', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'koji_search_options',
				'priority'		=> 10,
				'label' 		=> __( '', '' ),
				'description' 	=> __( '', '' ),
			) );

			$wp_customize->add_section( 'koji_related_posts_options', array(
				'title' 		=> __( '', '' ),
				'priority' 		=> 60,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> '',
			) );

			$wp_customize->add_setting( 'koji_disable_related_posts', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'koji_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'koji_disable_related_posts', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'koji_related_posts_options',
				'priority'		=> 10,
				'label' 		=> __( '', '' ),
				'description' 	=> __( '', '' ),
			) );

			function koji_sanitize_checkbox( $checked ) {
				return ( ( isset( $checked ) && true == $checked ) ? true : false );
			}

			function koji_sanitize_multiple_checkboxes( $values ) {
				$multi_values = ! is_array( $values ) ? explode( ',', $values ) : $values;
				return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
			}

			function koji_sanitize_radio( $input, $setting ) {
				$input = sanitize_key( $input );
				$choices = $setting->manager->get_control( $setting->id )->choices;
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
			}

		}

		public static function koji_customize_controls() {
			wp_enqueue_script( 'koji-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array( 'jquery', 'customize-controls' ), '', true );
		}

	}

	add_action( 'customize_register', array( 'Koji_Customize', 'koji_register' ) );

	add_action( 'customize_controls_init', array( 'Koji_Customize', 'koji_customize_controls' ) );

endif;