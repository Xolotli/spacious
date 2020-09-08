<?php
/**
 * Spacious Theme Customizer
 *
 * @package    ThemeGrill
 * @subpackage Spacious
 * @since      Spacious 1.3.4
 */

function spacious_customize_register( $wp_customize ) {

	// Custom customizer section classes.
	require_once SPACIOUS_INCLUDES_DIR . '/customizer/class-spacious-upsell-section.php';

	// Include control classes.
	require_once SPACIOUS_INCLUDES_DIR . '/customizer/class-spacious-image-radio-control.php';
	require_once SPACIOUS_INCLUDES_DIR . '/customizer/class-spacious-custom-css-control.php';
	require_once SPACIOUS_INCLUDES_DIR . '/customizer/class-spacious-text-area-control.php';
	require_once SPACIOUS_INCLUDES_DIR . '/customizer/class-spacious-editor-custom-control.php';
	require_once SPACIOUS_INCLUDES_DIR . '/customizer/class-spacious-typography-control.php';
	require_once SPACIOUS_INCLUDES_DIR . '/customizer/class-spacious-heading-control.php';

	$wp_customize->register_control_type( 'Spacious_Heading_Control' );

	// Transport postMessage variable set
	$customizer_selective_refresh = isset( $wp_customize->selective_refresh ) ? 'postMessage' : 'refresh';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '#site-title a',
			'render_callback' => 'spacious_customize_partial_blogname',
		) );

		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '#site-description',
			'render_callback' => 'spacious_customize_partial_blogdescription',
		) );
	}

	/*
	 * Assigning the theme name
	 */
	$spacious_themename = get_option( 'stylesheet' );
	$spacious_themename = preg_replace( "/\W/", "_", strtolower( $spacious_themename ) );

	/****************************************Start of the global Options****************************************/
	$wp_customize->add_panel(
		'spacious_global_options',
		array(
			'capabitity' => 'edit_theme_options',
			'priority'   => 50,
			'title'      => esc_html__( 'Global', 'spacious' ),
		)
	);

	// Site primary color option.
	$wp_customize->add_section(
		'spacious_global_color_setting',
		array(
			'panel'    => 'spacious_global_options',
			'priority' => 7,
			'title'    => esc_html__( 'Colors', 'spacious' ),
		)
	);

	$wp_customize->add_setting(
		$spacious_themename . '[spacious_primary_color]',
		array(
			'default'              => '#0FBE7C',
			'type'                 => 'option',
			'transport'            => 'postMessage',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'spacious_color_option_hex_sanitize',
			'sanitize_js_callback' => 'spacious_color_escaping_option_sanitize',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			$spacious_themename . '[spacious_primary_color]',
			array(
				'label'    => esc_html__( 'Primary Color', 'spacious' ),
				'section'  => 'spacious_global_color_setting',
				'settings' => $spacious_themename . '[spacious_primary_color]',
			)
		)
	);

	// Site dark light skin option.
	$wp_customize->add_setting(
		$spacious_themename . '[spacious_color_skin]',
		array(
			'default'           => 'light',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_select_sanitize',
		)
	);

	$wp_customize->add_control(
		new Spacious_Image_Radio_Control( $wp_customize,
			$spacious_themename . '[spacious_color_skin]',
			array(
				'type'     => 'radio',
				'label'    => esc_html__( 'Color Skin', 'spacious' ),
				'section'  => 'spacious_global_color_setting',
				'settings' => $spacious_themename . '[spacious_color_skin]',
				'choices'  => array(
					'light' => SPACIOUS_ADMIN_IMAGES_URL . '/light-color.jpg',
					'dark'  => SPACIOUS_ADMIN_IMAGES_URL . '/dark-color.jpg',
				),
			)
		)
	);

	// Global typography options.
	$wp_customize->add_section(
		'spacious_global_typography_section',
		array(
			'panel'    => 'spacious_global_options',
			'priority' => 7,
			'title'    => esc_html__( 'Typography', 'spacious' ),
		)
	);

	$wp_customize->add_setting(
		$spacious_themename . '[spacious_content_font]',
		array(
			'default'           => 'Lato',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_font_sanitize',
		)
	);

	$wp_customize->add_control(
		new Spacious_Typography_Control(
			$wp_customize,
			$spacious_themename . '[spacious_content_font]',
			array(
				'priority' => 8,
				'label'    => esc_html__( 'Body', 'spacious' ),
				'section'  => 'spacious_global_typography_section',
				'settings' => $spacious_themename . '[spacious_content_font]',
			)
		)
	);

	// Heading Typography option.
	$wp_customize->add_setting(
		$spacious_themename . '[spacious_titles_font]',
		array(
			'default'           => 'Lato',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_font_sanitize',
		)
	);

	$wp_customize->add_control(
		new Spacious_Typography_Control(
			$wp_customize,
			$spacious_themename . '[spacious_titles_font]',
			array(
				'priority' => 8,
				'label'    => esc_html__( 'Headings', 'spacious' ),
				'section'  => 'spacious_global_typography_section',
				'settings' => $spacious_themename . '[spacious_titles_font]',
			)
		)
	);

	// Global Background options.
	$wp_customize->add_section(
		'spacious_global_background_section',
		array(
			'panel'    => 'spacious_global_options',
			'priority' => 7,
			'title'    => esc_html__( 'Background', 'spacious' ),
		)
	);

	$wp_customize->add_setting(
		'spacious[global_background_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'global_background_heading',
			array(
				'label'    => esc_html__( 'Outside Container', 'spacious' ),
				'section'  => 'spacious_global_background_section',
				'settings' => 'spacious[global_background_heading]',
				'priority' => 10,
			)
		)
	);

	$wp_customize->get_control( 'background_color' )->section  = 'spacious_global_background_section';
	$wp_customize->get_control( 'background_color' )->priority = 20;

	$wp_customize->get_control( 'background_image' )->section  = 'spacious_global_background_section';
	$wp_customize->get_control( 'background_image' )->priority = 20;

	$wp_customize->get_control( 'background_preset' )->section  = 'spacious_global_background_section';
	$wp_customize->get_control( 'background_preset' )->priority = 20;

	$wp_customize->get_control( 'background_position' )->section  = 'spacious_global_background_section';
	$wp_customize->get_control( 'background_position' )->priority = 20;

	$wp_customize->get_control( 'background_size' )->section  = 'spacious_global_background_section';
	$wp_customize->get_control( 'background_size' )->priority = 20;

	$wp_customize->get_control( 'background_repeat' )->section  = 'spacious_global_background_section';
	$wp_customize->get_control( 'background_repeat' )->priority = 20;

	$wp_customize->get_control( 'background_attachment' )->section  = 'spacious_global_background_section';
	$wp_customize->get_control( 'background_attachment' )->priority = 20;

	// Layout option.
	$wp_customize->add_section(
		'spacious_global_layout_section',
		array(
			'panel'    => 'spacious_global_options',
			'priority' => 7,
			'title'    => esc_html__( 'Layout', 'spacious' ),
		)
	);

	// Site layout heading
	$wp_customize->add_setting(
		'spacious[global_site_layout_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'global_site_layout_heading',
			array(
				'label'    => esc_html__( 'Site Layout', 'spacious' ),
				'section'  => 'spacious_global_layout_section',
				'settings' => 'spacious[global_site_layout_heading]',
			)
		)
	);

	$wp_customize->add_setting(
		$spacious_themename . '[spacious_site_layout]',
		array(
			'default'           => 'box_1218px',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_select_sanitize',
		)
	);

	$wp_customize->add_control(
		$spacious_themename . '[spacious_site_layout]',
		array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Choose your site layout. The change is reflected in whole site.', 'spacious' ),
			'choices' => array(
				'box_1218px'  => esc_html__( 'Boxed layout with content width of 1218px', 'spacious' ),
				'box_978px'   => esc_html__( 'Boxed layout with content width of 978px', 'spacious' ),
				'wide_1218px' => esc_html__( 'Wide layout with content width of 1218px', 'spacious' ),
				'wide_978px'  => esc_html__( 'Wide layout with content width of 978px', 'spacious' ),
			),
			'section' => 'spacious_global_layout_section',
		)
	);

	// Site layout heading
	$wp_customize->add_setting(
		'spacious[global_sidebar_layout_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			$spacious_themename . 'global_sidebar_layout_heading',
			array(
				'label'    => esc_html__( 'Sidebar Layout', 'spacious' ),
				'section'  => 'spacious_global_layout_section',
				'settings' => 'spacious[global_sidebar_layout_heading]',
			)
		)
	);

	$wp_customize->add_setting(
		$spacious_themename . '[spacious_default_layout]',
		array(
			'default'           => 'right_sidebar',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_select_sanitize',
		)
	);

	$wp_customize->add_control(
		new Spacious_Image_Radio_Control(
			$wp_customize,
			$spacious_themename . '[spacious_default_layout]',
			array(
				'type'     => 'radio',
				'label'    => esc_html__( 'Default layout', 'spacious' ),
				'section'  => 'spacious_global_layout_section',
				'settings' => $spacious_themename . '[spacious_default_layout]',
				'choices'  => array(
					'right_sidebar'                => SPACIOUS_ADMIN_IMAGES_URL . '/right-sidebar.png',
					'left_sidebar'                 => SPACIOUS_ADMIN_IMAGES_URL . '/left-sidebar.png',
					'no_sidebar_full_width'        => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
					'no_sidebar_content_centered'  => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png',
					'no_sidebar_content_stretched' => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-stretched-layout.png',
				),
			)
		)
	);

	// default layout for pages.
	$wp_customize->add_setting(
		$spacious_themename . '[spacious_pages_default_layout]',
		array(
			'default'           => 'right_sidebar',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_select_sanitize',
		) );

	$wp_customize->add_control(
		new Spacious_Image_Radio_Control(
			$wp_customize,
			$spacious_themename . '[spacious_pages_default_layout]',
			array(
				'type'     => 'radio',
				'label'    => esc_html__( 'Default layout for pages only', 'spacious' ),
				'section'  => 'spacious_global_layout_section',
				'settings' => $spacious_themename . '[spacious_pages_default_layout]',
				'choices'  => array(
					'right_sidebar'                => SPACIOUS_ADMIN_IMAGES_URL . '/right-sidebar.png',
					'left_sidebar'                 => SPACIOUS_ADMIN_IMAGES_URL . '/left-sidebar.png',
					'no_sidebar_full_width'        => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
					'no_sidebar_content_centered'  => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png',
					'no_sidebar_content_stretched' => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-stretched-layout.png',
				),
			)
		)
	);

	$wp_customize->add_setting(
		$spacious_themename . '[spacious_single_posts_default_layout]',
		array(
			'default'           => 'right_sidebar',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_select_sanitize',
		)
	);

	$wp_customize->add_control(
		new Spacious_Image_Radio_Control(
			$wp_customize,
			$spacious_themename . '[spacious_single_posts_default_layout]',
			array(
				'type'     => 'radio',
				'label'    => esc_html__( 'Default layout for single posts only', 'spacious' ),
				'section'  => 'spacious_global_layout_section',
				'settings' => $spacious_themename . '[spacious_single_posts_default_layout]',
				'choices'  => array(
					'right_sidebar'                => SPACIOUS_ADMIN_IMAGES_URL . '/right-sidebar.png',
					'left_sidebar'                 => SPACIOUS_ADMIN_IMAGES_URL . '/left-sidebar.png',
					'no_sidebar_full_width'        => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
					'no_sidebar_content_centered'  => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png',
					'no_sidebar_content_stretched' => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-stretched-layout.png',
				),
			)
		)
	);

	/****************************************Start of the Header Options****************************************/
	// Header Options Area
	$wp_customize->add_panel( 'spacious_header_options', array(
		'capabitity' => 'edit_theme_options',
		'priority'   => 50,
		'title'      => esc_html__( 'Header', 'spacious' ),
	) );

	$wp_customize->get_section( 'title_tagline' )->panel    = 'spacious_header_options';
	$wp_customize->get_section( 'title_tagline' )->priority = 2;

	$wp_customize->add_setting(
		'spacious[site_logo_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'site_logo_heading',
			array(
				'label'    => esc_html__( 'Site Logo', 'spacious' ),
				'section'  => 'title_tagline',
				'settings' => 'spacious[site_logo_heading]',
				'priority' => 1,
			)
		)
	);

	// Retina Logo Option.
	$wp_customize->add_setting(
		$spacious_themename . '[spacious_different_retina_logo]',
		array(
			'default'           => 0,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_checkbox_sanitize',
		)
	);

	$wp_customize->add_control(
		$spacious_themename . '[spacious_different_retina_logo]',
		array(
			'type'     => 'checkbox',
			'priority' => 8,
			'label'    => esc_html__( 'Different Logo for Retina Devices?', 'spacious' ),
			'section'  => 'title_tagline',
		)
	);

	// Retina Logo Upload.
	$wp_customize->add_setting(
		$spacious_themename . '[spacious_retina_logo_upload]',
		array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			$spacious_themename . '[spacious_retina_logo_upload]',
			array(
				'label'           => esc_html__( 'Retina Logo', 'spacious' ),
				'description'     => esc_html__( 'Please upload the retina logo double the size of logo. For eg: If you upload 100 * 100 pixels for logo then use 200 * 200 pixels for retina logo.', 'spacious' ),
				'priority'        => 8,
				'setting'         => 'spacious[spacious_retina_logo_upload]',
				'section'         => 'title_tagline',
				'active_callback' => 'spacious_retina_logo_option',
			)
		)
	);

	// Heading for Site Icon.
	$wp_customize->add_setting(
		'spacious[site_icon_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'site_icon_heading',
			array(
				'label'    => esc_html__( 'Site icon', 'spacious' ),
				'section'  => 'title_tagline',
				'settings' => 'spacious[site_icon_heading]',
				'priority' => 8,
			)
		)
	);

	$wp_customize->get_control( 'site_icon' )->priority = 9;

	// Heading for Site Title.
	$wp_customize->add_setting(
		'spacious[site_title_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'site_title_heading',
			array(
				'label'    => esc_html__( 'Site Title', 'spacious' ),
				'section'  => 'title_tagline',
				'settings' => 'spacious[site_title_heading]',
				'priority' => 9,
			)
		)
	);

	$wp_customize->get_control( 'blogname' )->priority = 10;

	// Heading for Site Tagline.
	$wp_customize->add_setting(
		'spacious[site_tagline_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'site_tagline_heading',
			array(
				'label'    => esc_html__( 'Site Tagline', 'spacious' ),
				'section'  => 'title_tagline',
				'settings' => 'spacious[site_tagline_heading]',
				'priority' => 10,
			)
		)
	);

	$wp_customize->get_control( 'blogdescription' )->priority = 11;

	// Heading for logo and header text Visibility.
	$wp_customize->add_setting(
		'spacious[logo_text_visibility_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'logo_text_visibility_heading',
			array(
				'label'    => esc_html__( 'Visibility', 'spacious' ),
				'section'  => 'title_tagline',
				'settings' => 'spacious[logo_text_visibility_heading]',
				'priority' => 14,
			)
		)
	);

	$wp_customize->get_control( 'display_header_text' )->section  = 'title_tagline';
	$wp_customize->get_control( 'display_header_text' )->priority = 15;

	$wp_customize->add_setting(
		$spacious_themename . '[spacious_show_header_logo_text]',
		array(
			'default'           => 'text_only',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_select_sanitize',
		)
	);

	$wp_customize->add_control(
		$spacious_themename . '[spacious_show_header_logo_text]',
		array(
			'priority' => 15,
			'type'     => 'radio',
			'label'    => esc_html__( 'Choose the option that you want.', 'spacious' ),
			'section'  => 'title_tagline',
			'choices'  => array(
				'logo_only' => esc_html__( 'Header Logo Only', 'spacious' ),
				'text_only' => esc_html__( 'Header Text Only', 'spacious' ),
				'both'      => esc_html__( 'Show Both', 'spacious' ),
				'none'      => esc_html__( 'Disable', 'spacious' ),
			),
		)
	);

	// Heading for header text color.
	$wp_customize->add_setting(
		'spacious[header_text_color_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'header_text_color_heading',
			array(
				'label'    => esc_html__( 'Colors', 'spacious' ),
				'section'  => 'title_tagline',
				'settings' => 'spacious[header_text_color_heading]',
				'priority' => 16,
			)
		)
	);

	$wp_customize->get_control( 'header_textcolor' )->section  = 'title_tagline';
	$wp_customize->get_control( 'header_textcolor' )->priority = 20;

	// Header media options.
	$wp_customize->get_section( 'header_image' )->panel    = 'spacious_header_options';
	$wp_customize->get_section( 'header_image' )->priority = 2;

	// Header image position title heading.
	$wp_customize->add_setting(
		'spacious[header_image_position_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'header_image_position_heading',
			array(
				'label'    => esc_html__( 'Header Image Position', 'spacious' ),
				'section'  => 'header_image',
				'settings' => 'spacious[header_image_position_heading]',
				'priority' => 20,
			)
		)
	);

	$wp_customize->add_setting(
		$spacious_themename . '[spacious_header_image_position]',
		array(
			'default'           => 'above',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_select_sanitize',
		)
	);

	$wp_customize->add_control(
		$spacious_themename . '[spacious_header_image_position]',
		array(
			'priority' => 20,
			'type'     => 'radio',
			'label'    => esc_html__( 'Choose top header image display position.', 'spacious' ),
			'section'  => 'header_image',
			'choices'  => array(
				'above' => esc_html__( 'Position Above (Default): Display the Header image just above the site title and main menu part.', 'spacious' ),
				'below' => esc_html__( 'Position Below: Display the Header image just below the site title and main menu part.', 'spacious' ),
			),
		)
	);

	// Header Top bar activate option
	$wp_customize->add_section(
		'spacious_header_top_bar',
		array(
			'priority' => 2,
			'title'    => esc_html__( 'Top Bar', 'spacious' ),
			'panel'    => 'spacious_header_options',
		)
	);

	// Heading for Activate top bar.
	$wp_customize->add_setting(
		'spacious[header_top_bar_active_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'header_top_bar_active_heading',
			array(
				'label'    => esc_html__( 'Activate Header Top Bar', 'spacious' ),
				'section'  => 'spacious_header_top_bar',
				'settings' => 'spacious[header_top_bar_active_heading]',
			)
		)
	);

	$wp_customize->add_setting( $spacious_themename . '[spacious_activate_top_header_bar]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_activate_top_header_bar]', array(
		'type'     => 'checkbox',
		'label'    => esc_html__( 'Check to show top header bar. The top header bar includes social icons area, small text area and menu area.', 'spacious' ),
		'section'  => 'spacious_header_top_bar',
		'settings' => $spacious_themename . '[spacious_activate_top_header_bar]',
	) );

	// Heading for header info text.
	$wp_customize->add_setting(
		'spacious[header_info_text_heading]',
		array(
			'sanitize_callback' => false,
		)
	);

	$wp_customize->add_control(
		new Spacious_Heading_Control(
			$wp_customize,
			'header_info_text_heading',
			array(
				'label'    => esc_html__( 'Header Info Text', 'spacious' ),
				'section'  => 'spacious_header_top_bar',
				'settings' => 'spacious[header_info_text_heading]',
				'priority' => 10,
			)
		)
	);

	// Header area small text option.
	$wp_customize->add_setting(
		$spacious_themename . '[spacious_header_info_text]',
		array(
			'default'           => '',
			'type'              => 'option',
			'transport'         => $customizer_selective_refresh,
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_editor_sanitize',
		)
	);

	$wp_customize->add_control(
		new Spacious_Editor_Custom_Control(
			$wp_customize, $spacious_themename . '[spacious_header_info_text]',
			array(
				'label'   => esc_html__( 'You can add phone numbers, other contact info here as you like. This box also accepts shortcodes.', 'spacious' ),
				'section' => 'spacious_header_top_bar',
				'setting' => $spacious_themename . '[spacious_header_info_text]',
			)
		)
	);

	// Register `SPACIOUS_Upsell_Section` type section.
	$wp_customize->register_section_type( 'SPACIOUS_Upsell_Section' );

	// Add `SPACIOUS_Upsell_Section` to display pro link.
	$wp_customize->add_section(
		new SPACIOUS_Upsell_Section( $wp_customize, 'spacious_upsell_section',
			array(
				'title'      => esc_html__( 'View PRO version', 'spacious' ),
				'url'        => 'https://themegrill.com/spacious-pricing/?utm_source=spacious-customizer&utm_medium=view-pricing-link&utm_campaign=upgrade',
				'capability' => 'edit_theme_options',
				'priority'   => 1,
			)
		)
	);
	// Selective refresh for header information text
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( $spacious_themename . '[spacious_header_info_text]', array(
			'selector'        => '.small-info-text p',
			'render_callback' => 'spacious_header_info_text',
		) );
	}

	// Header display type option
	$wp_customize->add_section( 'spacious_header_display_type_option', array(
		'priority' => 2,
		'title'    => __( 'Header Display Type', 'spacious' ),
		'panel'    => 'spacious_header_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_header_display_type]', array(
		'default'           => 'one',
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_radio_select_sanitize',
	) );

	$wp_customize->add_control( new Spacious_Image_Radio_Control( $wp_customize, $spacious_themename . '[spacious_header_display_type]', array(
		'type'     => 'radio',
		'label'    => __( 'Choose the header display type that you want.', 'spacious' ),
		'section'  => 'spacious_header_display_type_option',
		'settings' => $spacious_themename . '[spacious_header_display_type]',
		'choices'  => array(
			'one'  => SPACIOUS_ADMIN_IMAGES_URL . '/header-left.png',
			'four' => SPACIOUS_ADMIN_IMAGES_URL . '/menu-bottom.png',
		),
	) ) );

	// Header Button option.
	$wp_customize->add_section( 'spacious_header_button_one', array(
		'priority' => 4,
		'title'    => __( 'Header Button One', 'spacious' ),
		'panel'    => 'spacious_header_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_header_button_one_setting]', array(
		'default'           => '',
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_header_button_one_setting]', array(
		'label'   => __( 'Button Text', 'spacious' ),
		'section' => 'spacious_header_button_one',
		'setting' => $spacious_themename . '[spacious_header_button_one_setting]',
	) );

	// Header button link.
	$wp_customize->add_setting( $spacious_themename . '[spacious_header_button_one_link]', array(
		'default'           => '',
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_header_button_one_link]', array(
		'label'   => __( 'Button Link', 'spacious' ),
		'section' => 'spacious_header_button_one',
		'setting' => $spacious_themename . '[spacious_header_button_one_link]',
	) );

	// Header button link in new tab.
	$wp_customize->add_setting( $spacious_themename . '[spacious_header_button_one_tab]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_header_button_one_tab]', array(
		'type'    => 'checkbox',
		'label'   => __( 'Check to show in new tab', 'spacious' ),
		'section' => 'spacious_header_button_one',
		'setting' => $spacious_themename . '[spacious_header_button_one_tab]',
	) );

	// Display menu in one line.
	$wp_customize->add_section( 'spacious_one_line_menu_section', array(
		'priority' => 3,
		'title'    => __( 'Menu Display', 'spacious' ),
		'panel'    => 'spacious_header_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_one_line_menu_setting]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_one_line_menu_setting]', array(
		'type'    => 'checkbox',
		'label'   => __( 'Display menu in one line', 'spacious' ),
		'section' => 'spacious_one_line_menu_section',
		'setting' => $spacious_themename . '[spacious_one_line_menu_setting]',
	) );

	// Responsive collapse menu
	$wp_customize->add_section( 'spacious_new_menu', array(
		'priority' => 4,
		'title'    => __( 'Responsive Menu Style', 'spacious' ),
		'panel'    => 'spacious_header_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_new_menu]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_new_menu]', array(
		'type'    => 'checkbox',
		'label'   => __( 'Switch to new responsive menu.', 'spacious' ),
		'section' => 'spacious_new_menu',
	) );

	// Search icon.
	$wp_customize->add_section( 'spacious_header_search_icon', array(
		'priority' => 9,
		'title'    => __( 'Search icon', 'spacious' ),
		'panel'    => 'spacious_header_options',
	) );
	$wp_customize->add_setting( $spacious_themename . '[spacious_header_search_icon]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_header_search_icon]', array(
		'type'    => 'checkbox',
		'label'   => __( 'Show search icon in header.', 'spacious' ),
		'section' => 'spacious_header_search_icon',
	) );

	/**
	 * Title header options
	 */
	$wp_customize->add_section( 'spacious_header_title', array(
		'priority' => 10,
		'title'    => __( 'Header Title', 'spacious' ),
		'panel'    => 'spacious_header_options',
	) );
	$wp_customize->add_setting( $spacious_themename . '[spacious_header_title_hide]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_header_title_hide]', array(
		'type'    => 'checkbox',
		'label'   => __( 'Hide page/post header title', 'spacious' ),
		'section' => 'spacious_header_title',
	) );

	// End of Header Options

	/*************************************Start of the Social Links Options*************************************/

	$wp_customize->add_panel( 'spacious_social_links_options', array(
		'capabitity' => 'edit_theme_options',
		'priority'   => 510,
		'title'      => __( 'Social Links', 'spacious' ),
	) );

	// Social links activate option
	$wp_customize->add_section( 'spacious_social_links_setting', array(
		'priority' => 1,
		'title'    => __( 'Activate social links area', 'spacious' ),
		'panel'    => 'spacious_social_links_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_activate_social_links]', array(
		'default'           => 0,
		'type'              => 'option',
		'transport'         => $customizer_selective_refresh,
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_activate_social_links]', array(
		'type'     => 'checkbox',
		'label'    => __( 'Check to activate social links area. You also need to activate the header top bar section in Header options to show this social links area', 'spacious' ),
		'section'  => 'spacious_social_links_setting',
		'settings' => $spacious_themename . '[spacious_activate_social_links]',
	) );

	// Selective refresh for social links enable
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( $spacious_themename . '[spacious_activate_social_links]', array(
			'selector'        => '.social-links',
			'render_callback' => '',
		) );
	}

	$spacious_social_links = array(
		'spacious_social_facebook'  => __( 'Facebook', 'spacious' ),
		'spacious_social_twitter'   => __( 'Twitter', 'spacious' ),
		'spacious_social_instagram' => __( 'Instagram', 'spacious' ),
		'spacious_social_linkedin'  => __( 'LinkedIn', 'spacious' ),
	);

	$i = 1;
	foreach ( $spacious_social_links as $key => $value ) {

		$wp_customize->add_section( 'spacious_social_sites_section' . $i, array(
			'priority' => 2,
			'title'    => $value,
			'panel'    => 'spacious_social_links_options',
		) );

		// adding social sites link
		$wp_customize->add_setting( $spacious_themename . '[' . $key . ']', array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( $spacious_themename . '[' . $key . ']', array(
			'label'   => sprintf( __( 'Add link for %1$s', 'spacious' ), $value ),
			'section' => 'spacious_social_sites_section' . $i,
			'setting' => $spacious_themename . '[' . $key . ']',
		) );

		// adding social open in new page tab setting
		$wp_customize->add_setting( $spacious_themename . '[' . $key . 'new_tab]', array(
			'default'           => 0,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_checkbox_sanitize',
		) );

		$wp_customize->add_control( $spacious_themename . '[' . $key . 'new_tab]', array(
			'type'    => 'checkbox',
			'label'   => __( 'Check to show in new tab', 'spacious' ),
			'section' => 'spacious_social_sites_section' . $i,
			'setting' => $spacious_themename . '[' . $key . 'new_tab]',
		) );

		$i++;

	}

	/****************************************Start of the Design Options****************************************/
	$wp_customize->add_panel( 'spacious_design_options', array(
		'capabitity' => 'edit_theme_options',
		'priority'   => 505,
		'title'      => __( 'Design', 'spacious' ),
	) );

	// blog posts display type setting
	$wp_customize->add_section( 'spacious_blog_posts_display_type_setting', array(
		'priority' => 5,
		'title'    => __( 'Blog Posts display type', 'spacious' ),
		'panel'    => 'spacious_design_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_archive_display_type]', array(
		'default'           => 'blog_large',
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_radio_select_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_archive_display_type]', array(
		'type'    => 'radio',
		'label'   => __( 'Choose the display type for the latests posts view or posts page view (static front page).', 'spacious' ),
		'choices' => array(
			'blog_large'            => __( 'Blog Image Large', 'spacious' ),
			'blog_medium'           => __( 'Blog Image Medium', 'spacious' ),
			'blog_medium_alternate' => __( 'Blog Image Alternate Medium', 'spacious' ),
			'blog_full_content'     => __( 'Blog Full Content', 'spacious' ),
		),
		'section' => 'spacious_blog_posts_display_type_setting',
	) );

	if ( ! function_exists( 'wp_update_custom_css_post' ) ) {
		// Custom CSS setting
		$wp_customize->add_section( 'spacious_custom_css_setting', array(
			'priority' => 8,
			'title'    => __( 'Custom CSS', 'spacious' ),
			'panel'    => 'spacious_design_options',
		) );

		$wp_customize->add_setting( $spacious_themename . '[spacious_custom_css]', array(
			'default'              => '',
			'type'                 => 'option',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => 'wp_filter_nohtml_kses',
			'sanitize_js_callback' => 'wp_filter_nohtml_kses',
		) );

		$wp_customize->add_control( new spacious_Custom_CSS_Control( $wp_customize, $spacious_themename . '[spacious_custom_css]', array(
			'label'    => __( 'Write your Custom CSS.', 'spacious' ),
			'section'  => 'spacious_custom_css_setting',
			'settings' => $spacious_themename . '[spacious_custom_css]',
		) ) );
	}
	// End of Design Options

	/****************************************Start of the Additional Options****************************************/
	$wp_customize->add_panel( 'spacious_additional_options', array(
		'capabitity' => 'edit_theme_options',
		'priority'   => 510,
		'title'      => __( 'Additional', 'spacious' ),
	) );

	//Related post
	$wp_customize->add_section( 'spacious_related_posts_section', array(
		'priority' => 5,
		'title'    => esc_html__( 'Related Posts', 'spacious' ),
		'panel'    => 'spacious_additional_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_related_posts_activate]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_related_posts_activate]', array(
		'type'     => 'checkbox',
		'label'    => esc_html__( 'Check to activate the related posts', 'spacious' ),
		'section'  => 'spacious_related_posts_section',
		'settings' => $spacious_themename . '[spacious_related_posts_activate]',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_related_posts]', array(
		'default'           => 'categories',
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_radio_select_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_related_posts]', array(
		'type'     => 'radio',
		'label'    => __( 'Related Posts Must Be Shown As:', 'spacious' ),
		'section'  => 'spacious_related_posts_section',
		'settings' => $spacious_themename . '[spacious_related_posts]',
		'choices'  => array(
			'categories' => esc_html__( 'Related Posts By Categories', 'spacious' ),
			'tags'       => esc_html__( 'Related Posts By Tags', 'spacious' ),
		),
	) );

	// Featured image in single post page activate option
	$wp_customize->add_section( 'spacious_featured_image_single_post_page_section', array(
		'priority' => 6,
		'title'    => __( 'Featured Image In Single Post Page', 'spacious' ),
		'panel'    => 'spacious_additional_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_featured_image_single_post_page]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_featured_image_single_post_page]', array(
		'type'     => 'checkbox',
		'label'    => __( 'Check to enable the featured image in single post page.', 'spacious' ),
		'section'  => 'spacious_featured_image_single_post_page_section',
		'settings' => $spacious_themename . '[spacious_featured_image_single_post_page]',
	) );

	// Featured image in single page activate option
	$wp_customize->add_section( 'spacious_featured_image_single_page_section', array(
		'priority' => 6,
		'title'    => __( 'Featured Image In Single Page', 'spacious' ),
		'panel'    => 'spacious_additional_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_featured_image_single_page]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_featured_image_single_page]', array(
		'type'     => 'checkbox',
		'label'    => __( 'Check to enable the featured image in single page.', 'spacious' ),
		'section'  => 'spacious_featured_image_single_page_section',
		'settings' => $spacious_themename . '[spacious_featured_image_single_page]',
	) );

	// Author bio option.
	$wp_customize->add_section( 'spacious_author_bio_section', array(
		'priority' => 5,
		'title'    => __( 'Author Bio', 'spacious' ),
		'panel'    => 'spacious_additional_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_author_bio]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_author_bio]', array(
		'type'     => 'checkbox',
		'label'    => __( 'Check to enable the author bio section just below the post.', 'spacious' ),
		'section'  => 'spacious_author_bio_section',
		'settings' => $spacious_themename . '[spacious_author_bio]',
	) );

	/****************************************Start of the Slider Options****************************************/
	$wp_customize->add_panel( 'spacious_slider_options', array(
		'capabitity' => 'edit_theme_options',
		'priority'   => 515,
		'title'      => __( 'Slider', 'spacious' ),
	) );

	// Slider activate option
	$wp_customize->add_section( 'spacious_slider_activate_section', array(
		'priority' => 1,
		'title'    => __( 'Activate slider', 'spacious' ),
		'panel'    => 'spacious_slider_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_activate_slider]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_activate_slider]', array(
		'type'     => 'checkbox',
		'label'    => __( 'Check to activate slider.', 'spacious' ),
		'section'  => 'spacious_slider_activate_section',
		'settings' => $spacious_themename . '[spacious_activate_slider]',
	) );

	// Selective refresh for slider activate
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( $spacious_themename . '[spacious_activate_slider]', array(
			'selector'        => '#featured-slider',
			'render_callback' => '',
		) );
	}

	// Disable slider in blog page
	$wp_customize->add_section( 'spacious_disable_slider_blog_page_section', array(
		'priority' => 2,
		'title'    => __( 'Disable slider in Posts page', 'spacious' ),
		'panel'    => 'spacious_slider_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_blog_slider]', array(
		'default'           => 0,
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_checkbox_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_blog_slider]', array(
		'type'     => 'checkbox',
		'label'    => __( 'Check to disable slider in Posts Page', 'spacious' ),
		'section'  => 'spacious_disable_slider_blog_page_section',
		'settings' => $spacious_themename . '[spacious_blog_slider]',
	) );

	for ( $i = 1; $i <= 5; $i++ ) {
		// adding slider section
		$wp_customize->add_section( 'spacious_slider_number_section' . $i, array(
			'priority' => 10,
			'title'    => sprintf( __( 'Image Upload #%1$s', 'spacious' ), $i ),
			'panel'    => 'spacious_slider_options',
		) );

		// adding slider image url
		$wp_customize->add_setting( $spacious_themename . '[spacious_slider_image' . $i . ']', array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $spacious_themename . '[spacious_slider_image' . $i . ']', array(
			'label'   => __( 'Upload slider image.', 'spacious' ),
			'section' => 'spacious_slider_number_section' . $i,
			'setting' => $spacious_themename . '[spacious_slider_image' . $i . ']',
		) ) );

		// adding slider title
		$wp_customize->add_setting( $spacious_themename . '[spacious_slider_title' . $i . ']', array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		) );

		$wp_customize->add_control( $spacious_themename . '[spacious_slider_title' . $i . ']', array(
			'label'   => __( 'Enter title for your slider.', 'spacious' ),
			'section' => 'spacious_slider_number_section' . $i,
			'setting' => $spacious_themename . '[spacious_slider_title' . $i . ']',
		) );

		// adding slider description
		$wp_customize->add_setting( $spacious_themename . '[spacious_slider_text' . $i . ']', array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_text_sanitize',
		) );

		$wp_customize->add_control( new Spacious_Text_Area_Control( $wp_customize, $spacious_themename . '[spacious_slider_text' . $i . ']', array(
			'label'   => __( 'Enter your slider description.', 'spacious' ),
			'section' => 'spacious_slider_number_section' . $i,
			'setting' => $spacious_themename . '[spacious_slider_text' . $i . ']',
		) ) );

		// adding slider button text
		$wp_customize->add_setting( $spacious_themename . '[spacious_slider_button_text' . $i . ']', array(
			'default'           => __( 'Read more', 'spacious' ),
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'wp_filter_nohtml_kses',
		) );

		$wp_customize->add_control( $spacious_themename . '[spacious_slider_button_text' . $i . ']', array(
			'label'   => __( 'Enter the button text. Default is "Read more"', 'spacious' ),
			'section' => 'spacious_slider_number_section' . $i,
			'setting' => $spacious_themename . '[spacious_slider_button_text' . $i . ']',
		) );

		// adding button url
		$wp_customize->add_setting( $spacious_themename . '[spacious_slider_link' . $i . ']', array(
			'default'           => '',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		) );

		$wp_customize->add_control( $spacious_themename . '[spacious_slider_link' . $i . ']', array(
			'label'   => __( 'Enter link to redirect slider when clicked', 'spacious' ),
			'section' => 'spacious_slider_number_section' . $i,
			'setting' => $spacious_themename . '[spacious_slider_link' . $i . ']',
		) );
	}
	// End of Slider Options

	/****************************************Start of the Footer Options****************************************/

	$wp_customize->add_panel( 'spacious_footer_options', array(
		'capabitity' => 'edit_theme_options',
		'priority'   => 545,
		'title'      => __( 'Footer', 'spacious' ),
	) );

	// Footer widgets select type
	$wp_customize->add_section( 'spacious_footer_column_select_section', array(
		'priority' => 5,
		'title'    => __( 'Footer Widgets Column', 'spacious' ),
		'panel'    => 'spacious_footer_options',
	) );

	$wp_customize->add_setting( $spacious_themename . '[spacious_footer_widget_column_select_type]', array(
		'default'           => 'four',
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'spacious_radio_sanitize',
	) );

	$wp_customize->add_control( $spacious_themename . '[spacious_footer_widget_column_select_type]', array(
		'type'    => 'select',
		'label'   => __( 'Choose the number of column for the footer widgetized areas.', 'spacious' ),
		'choices' => array(
			'one'   => __( 'One Column', 'spacious' ),
			'two'   => __( 'Two Column', 'spacious' ),
			'three' => __( 'Three Column', 'spacious' ),
			'four'  => __( 'Four Column', 'spacious' ),
		),
		'section' => 'spacious_footer_column_select_section',
	) );

	// End of footer options.

	/**************************************Start of the WooCommerce Options*************************************/
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

		$wp_customize->add_panel( 'spacious_woocommerce_options', array(
			'priority'   => 570,
			'title'      => __( 'WooCommerce', 'spacious' ),
			'capability' => 'edit_theme_options',
		) );

		// woocommerce archive page layout
		$wp_customize->add_section( 'spacious_woocommerce_archive_page_layout_setting', array(
			'priority' => 1,
			'title'    => __( 'Archive Page Layout', 'spacious' ),
			'panel'    => 'spacious_woocommerce_options',
		) );

		$wp_customize->add_setting( $spacious_themename . '[spacious_woo_archive_layout]', array(
			'default'           => 'no_sidebar_full_width',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_sanitize',
		) );

		$wp_customize->add_control( new Spacious_Image_Radio_Control( $wp_customize, $spacious_themename . '[spacious_woo_archive_layout]', array(
			'type'     => 'radio',
			'label'    => __( 'This layout will be reflected in woocommerce archive page only.', 'spacious' ),
			'section'  => 'spacious_woocommerce_archive_page_layout_setting',
			'settings' => $spacious_themename . '[spacious_woo_archive_layout]',
			'choices'  => array(
				'right_sidebar'               => SPACIOUS_ADMIN_IMAGES_URL . '/right-sidebar.png',
				'left_sidebar'                => SPACIOUS_ADMIN_IMAGES_URL . '/left-sidebar.png',
				'no_sidebar_full_width'       => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
				'no_sidebar_content_centered' => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png',
			),
		) ) );

		// WooCommerce product page layout
		$wp_customize->add_section( 'spacious_woocommerce_product_page_layout_setting', array(
			'priority' => 2,
			'title'    => __( 'Product Page Layout', 'spacious' ),
			'panel'    => 'spacious_woocommerce_options',
		) );

		$wp_customize->add_setting( $spacious_themename . '[spacious_woo_product_layout]', array(
			'default'           => 'no_sidebar_full_width',
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_radio_sanitize',
		) );

		$wp_customize->add_control( new Spacious_Image_Radio_Control( $wp_customize, $spacious_themename . '[spacious_woo_product_layout]', array(
			'type'     => 'radio',
			'label'    => __( 'This layout will be reflected in woocommerce Product page.', 'spacious' ),
			'section'  => 'spacious_woocommerce_product_page_layout_setting',
			'settings' => $spacious_themename . '[spacious_woo_product_layout]',
			'choices'  => array(
				'right_sidebar'               => SPACIOUS_ADMIN_IMAGES_URL . '/right-sidebar.png',
				'left_sidebar'                => SPACIOUS_ADMIN_IMAGES_URL . '/left-sidebar.png',
				'no_sidebar_full_width'       => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-full-width-layout.png',
				'no_sidebar_content_centered' => SPACIOUS_ADMIN_IMAGES_URL . '/no-sidebar-content-centered-layout.png',
			),
		) ) );

		// Section: WooCommerce additional options.
		$wp_customize->add_section( 'spacious_woocommerce_additional', array(
			'priority' => 3,
			'title'    => __( 'Additional', 'spacious' ),
			'panel'    => 'spacious_woocommerce_options',
		) );

		// Setting: WooCommerce cart icon.
		$wp_customize->add_setting( $spacious_themename . '[spacious_cart_icon]', array(
			'default'           => 0,
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'spacious_checkbox_sanitize',
		) );

		$wp_customize->add_control( $spacious_themename . '[spacious_cart_icon]', array(
			'type'     => 'checkbox',
			'label'    => __( 'Check to show WooCommerce cart icon on menu bar', 'spacious' ),
			'section'  => 'spacious_woocommerce_additional',
			'settings' => $spacious_themename . '[spacious_cart_icon]',
		) );

	}
	// End of the WooCommerce Options.

	/****************************************Start of the data sanitization****************************************/
	// radio/select sanitization
	function spacious_radio_select_sanitize( $input, $setting ) {
		// Ensuring that the input is a slug.
		$input = sanitize_key( $input );
		// Get the list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it, else, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	// checkbox sanitize
	function spacious_checkbox_sanitize( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return '';
		}
	}

	// Google Font Sanitization
	function spacious_font_sanitize( $input ) {
		$spacious_standard_fonts_array = spacious_standard_fonts_array();
		$spacious_google_fonts         = spacious_google_fonts();
		$valid_keys                    = array_merge( $spacious_standard_fonts_array, $spacious_google_fonts );

		if ( array_key_exists( $input, $valid_keys ) ) {
			return $input;
		} else {
			return '';
		}
	}

	// editor sanitization
	function spacious_editor_sanitize( $input ) {
		if ( isset( $input ) ) {
			$input = stripslashes( wp_filter_post_kses( addslashes( $input ) ) );
		}

		return $input;
	}

	// Radio and Select Sanitization
	function spacious_radio_sanitize( $input, $setting ) {
		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	// text-area sanitize
	function spacious_text_sanitize( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}

	// color sanitization
	function spacious_color_option_hex_sanitize( $color ) {
		if ( $unhashed = sanitize_hex_color_no_hash( $color ) ) {
			return '#' . $unhashed;
		}

		return $color;
	}

	// Active Callback for Retina Logo.
	function spacious_retina_logo_option() {
		if ( spacious_options( 'spacious_different_retina_logo', 0 ) == 1 ) {
			return true;
		}

		return false;
	}

	function spacious_color_escaping_option_sanitize( $input ) {
		$input = esc_attr( $input );

		return $input;
	}

	function spacious_false_sanitize() {
		return false;
	}
}

add_action( 'customize_register', 'spacious_customize_register' );

/*****************************************************************************************/

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function spacious_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function spacious_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Spacious 1.4.9
 */
function spacious_customize_preview_js() {
	wp_enqueue_script( 'spacious-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), false, true );
}

add_action( 'customize_preview_init', 'spacious_customize_preview_js' );

/**
 * Enqueue customize controls scripts.
 */
function spacious_enqueue_customize_controls() {

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/**
	 * Enqueue required Customize Controls CSS files.
	 */
	// Main CSS file.
	wp_enqueue_style(
		'spacious-customize-controls',
		get_template_directory_uri() . '/css/customize-controls' . $suffix . '.css',
		array(),
		false
	);

}

add_action( 'customize_controls_enqueue_scripts', 'spacious_enqueue_customize_controls' );


/*****************************************************************************************/

if ( ! function_exists( 'spacious_standard_fonts_array' ) ) :

	/**
	 * Standard Fonts array
	 *
	 * @return array of Standarad Fonts
	 */
	function spacious_standard_fonts_array() {
		$spacious_standard_fonts = array(
			'Georgia,Times,"Times New Roman",serif'                                                                                                 => 'serif',
			'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif' => 'sans-serif',
			'Monaco,"Lucida Sans Typewriter","Lucida Typewriter","Courier New",Courier,monospace'                                                   => 'monospace',
		);

		return $spacious_standard_fonts;
	}

endif;

if ( ! function_exists( 'spacious_google_fonts' ) ) :

	/**
	 * Google Fonts array
	 *
	 * @return array of Google Fonts
	 */
	function spacious_google_fonts() {
		$spacious_google_font = array(
			'Roboto'           => 'Roboto',
			'Lato'             => 'Lato',
			'Open Sans'        => 'Open Sans',
			'Noto Sans'        => 'Noto Sans',
			'Noto Serif'       => 'Noto Serif',
			'PT Sans'          => 'PT Sans',
			'Playfair Display' => 'Playfair Display',
			'Muli'             => 'Muli',
			'Montserrat'       => 'Montserrat',
			'Poppins'          => 'Poppins',
			'Raleway'          => 'Raleway',
			'Oswald'           => 'Oswald',
			'Ubuntu'           => 'Ubuntu',
			'Nunito'           => 'Nunito',
		);

		return $spacious_google_font;
	}

endif;

/* * ************************************************************************************** */

/*
 * Custom Scripts
 */
add_action( 'customize_controls_print_footer_scripts', 'spacious_customizer_custom_scripts' );

function spacious_customizer_custom_scripts() {
	?>
	<style>
		/* Theme Instructions Panel CSS */
		li#accordion-section-spacious_upsell_section h3.accordion-section-title {
			background-color: #0FBE7C !important;
			border-left-color: #04a267;
			color: #fff !important;
			padding: 0;
		}

		#accordion-section-spacious_upsell_section h3 a:after {
			content: '\f345';
			color: #fff;
			position: absolute;
			top: 12px;
			right: 10px;
			z-index: 1;
			font: 400 20px/1 dashicons;
			speak: none;
			display: block;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			text-decoration: none !important;
		}

		li#accordion-section-spacious_upsell_section h3.accordion-section-title a {
			color: #fff;
			display: block;
			text-decoration: none;
			padding: 12px 15px 15px;
		}

		li#accordion-section-spacious_upsell_section h3.accordion-section-title a:focus {
			box-shadow: none;
		}

		li#accordion-section-spacious_upsell_section h3.accordion-section-title:hover {
			background-color: #09ad6f !important;
			border-left-color: #04a267 !important;
			color: #fff !important;
		}

		li#accordion-section-spacious_upsell_section h3.accordion-section-title:after {
			color: #fff !important;
		}
	</style>

	<script>
		(
			function ( $, api ) {
				api.sectionConstructor[ 'spacious-upsell-section' ] = api.Section.extend( {

					// No events for this type of section.
					attachEvents : function () {
					},

					// Always make the section active.
					isContextuallyActive : function () {
						return true;
					}
				} );
			}
		)( jQuery, wp.customize );

	</script>
	<?php
}
