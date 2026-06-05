<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_VERSION', '3.4.9' );
define( 'EHP_THEME_SLUG', 'hello-elementor' );

define( 'HELLO_THEME_PATH', get_template_directory() );
define( 'HELLO_THEME_URL', get_template_directory_uri() );
define( 'HELLO_THEME_ASSETS_PATH', HELLO_THEME_PATH . '/assets/' );
define( 'HELLO_THEME_ASSETS_URL', HELLO_THEME_URL . '/assets/' );
define( 'HELLO_THEME_SCRIPTS_PATH', HELLO_THEME_ASSETS_PATH . 'js/' );
define( 'HELLO_THEME_SCRIPTS_URL', HELLO_THEME_ASSETS_URL . 'js/' );
define( 'HELLO_THEME_STYLE_PATH', HELLO_THEME_ASSETS_PATH . 'css/' );
define( 'HELLO_THEME_STYLE_URL', HELLO_THEME_ASSETS_URL . 'css/' );
define( 'HELLO_THEME_IMAGES_PATH', HELLO_THEME_ASSETS_PATH . 'images/' );
define( 'HELLO_THEME_IMAGES_URL', HELLO_THEME_ASSETS_URL . 'images/' );

if ( ! isset( $content_width ) ) {
	$content_width = 800; // Pixels.
}

if ( ! function_exists( 'hello_elementor_setup' ) ) {
	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	function hello_elementor_setup() {
		if ( is_admin() ) {
			hello_maybe_update_theme_version_in_db();
		}

		if ( apply_filters( 'hello_elementor_register_menus', true ) ) {
			register_nav_menus( [ 'menu-1' => esc_html__( 'Header', 'hello-elementor' ) ] );
			register_nav_menus( [ 'menu-2' => esc_html__( 'Footer', 'hello-elementor' ) ] );
		}

		if ( apply_filters( 'hello_elementor_post_type_support', true ) ) {
			add_post_type_support( 'page', 'excerpt' );
		}

		if ( apply_filters( 'hello_elementor_add_theme_support', true ) ) {
			add_theme_support( 'post-thumbnails' );
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support(
				'html5',
				[
					'search-form',
					'comment-form',
					'comment-list',
					'gallery',
					'caption',
					'script',
					'style',
					'navigation-widgets',
				]
			);
			add_theme_support(
				'custom-logo',
				[
					'height'      => 100,
					'width'       => 350,
					'flex-height' => true,
					'flex-width'  => true,
				]
			);
			add_theme_support( 'align-wide' );
			add_theme_support( 'responsive-embeds' );

			/*
			 * Editor Styles
			 */
			add_theme_support( 'editor-styles' );
			add_editor_style( 'assets/css/editor-styles.css' );

			/*
			 * WooCommerce.
			 */
			if ( apply_filters( 'hello_elementor_add_woocommerce_support', true ) ) {
				// WooCommerce in general.
				add_theme_support( 'woocommerce' );
				// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
				// zoom.
				add_theme_support( 'wc-product-gallery-zoom' );
				// lightbox.
				add_theme_support( 'wc-product-gallery-lightbox' );
				// swipe.
				add_theme_support( 'wc-product-gallery-slider' );
			}
		}
	}
}
add_action( 'after_setup_theme', 'hello_elementor_setup' );

function hello_maybe_update_theme_version_in_db() {
	$theme_version_option_name = 'hello_theme_version';
	// The theme version saved in the database.
	$hello_theme_db_version = get_option( $theme_version_option_name );

	// If the 'hello_theme_version' option does not exist in the DB, or the version needs to be updated, do the update.
	if ( ! $hello_theme_db_version || version_compare( $hello_theme_db_version, HELLO_ELEMENTOR_VERSION, '<' ) ) {
		update_option( $theme_version_option_name, HELLO_ELEMENTOR_VERSION );
	}
}

if ( ! function_exists( 'hello_elementor_display_header_footer' ) ) {
	/**
	 * Check whether to display header footer.
	 *
	 * @return bool
	 */
	function hello_elementor_display_header_footer() {
		$hello_elementor_header_footer = true;

		return apply_filters( 'hello_elementor_header_footer', $hello_elementor_header_footer );
	}
}

if ( ! function_exists( 'hello_elementor_scripts_styles' ) ) {
	/**
	 * Theme Scripts & Styles.
	 *
	 * @return void
	 */
	function hello_elementor_scripts_styles() {
		if ( apply_filters( 'hello_elementor_enqueue_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor',
				HELLO_THEME_STYLE_URL . 'reset.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( apply_filters( 'hello_elementor_enqueue_theme_style', true ) ) {
			wp_enqueue_style(
				'hello-elementor-theme-style',
				HELLO_THEME_STYLE_URL . 'theme.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}

		if ( hello_elementor_display_header_footer() ) {
			wp_enqueue_style(
				'hello-elementor-header-footer',
				HELLO_THEME_STYLE_URL . 'header-footer.css',
				[],
				HELLO_ELEMENTOR_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_scripts_styles' );

if ( ! function_exists( 'hello_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function hello_elementor_register_elementor_locations( $elementor_theme_manager ) {
		if ( apply_filters( 'hello_elementor_register_elementor_locations', true ) ) {
			$elementor_theme_manager->register_all_core_location();
		}
	}
}
add_action( 'elementor/theme/register_locations', 'hello_elementor_register_elementor_locations' );

if ( ! function_exists( 'hello_elementor_content_width' ) ) {
	/**
	 * Set default content width.
	 *
	 * @return void
	 */
	function hello_elementor_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'hello_elementor_content_width', 800 );
	}
}
add_action( 'after_setup_theme', 'hello_elementor_content_width', 0 );

if ( ! function_exists( 'hello_elementor_add_description_meta_tag' ) ) {
	/**
	 * Add description meta tag with excerpt text.
	 *
	 * @return void
	 */
	function hello_elementor_add_description_meta_tag() {
		if ( ! apply_filters( 'hello_elementor_description_meta_tag', true ) ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_queried_object();
		if ( empty( $post->post_excerpt ) ) {
			return;
		}

		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $post->post_excerpt ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'hello_elementor_add_description_meta_tag' );

// Settings page
require get_template_directory() . '/includes/settings-functions.php';

// Header & footer styling option, inside Elementor
require get_template_directory() . '/includes/elementor-functions.php';

if ( ! function_exists( 'hello_elementor_customizer' ) ) {
	// Customizer controls
	function hello_elementor_customizer() {
		if ( ! is_customize_preview() ) {
			return;
		}

		if ( ! hello_elementor_display_header_footer() ) {
			return;
		}

		require get_template_directory() . '/includes/customizer-functions.php';
	}
}
add_action( 'init', 'hello_elementor_customizer' );

if ( ! function_exists( 'hello_elementor_check_hide_title' ) ) {
	/**
	 * Check whether to display the page title.
	 *
	 * @param bool $val default value.
	 *
	 * @return bool
	 */
	function hello_elementor_check_hide_title( $val ) {
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$current_doc = Elementor\Plugin::instance()->documents->get( get_the_ID() );
			if ( $current_doc && 'yes' === $current_doc->get_settings( 'hide_title' ) ) {
				$val = false;
			}
		}
		return $val;
	}
}
add_filter( 'hello_elementor_page_title', 'hello_elementor_check_hide_title' );

/**
 * BC:
 * In v2.7.0 the theme removed the `hello_elementor_body_open()` from `header.php` replacing it with `wp_body_open()`.
 * The following code prevents fatal errors in child themes that still use this function.
 */
if ( ! function_exists( 'hello_elementor_body_open' ) ) {
	function hello_elementor_body_open() {
		wp_body_open();
	}
}

require HELLO_THEME_PATH . '/theme.php';

HelloTheme\Theme::instance();


function renderizar_landing_sweetbite() {
    ob_start();
    ?>
    <!-- Fuentes y Tipografías -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- VARIABLES DE DISEÑO (Paleta de colores oficial) --- */
        :root {
            --bg-cream: #FDFBF7;
            --bg-light-gray: #F7F5F0;
            --bg-pink: #FCE4E6;
            --primary-dark: #3D1314;
            --accent-red: #731A24;
            --text-dark: #2C2623;
            --text-muted: #6E6661;
            --white: #FFFFFF;
            --font-heading: 'Playfair Display', serif !important;
            --font-body: 'Plus Jakarta Sans', sans-serif !important;
        }

        /* --- ESTILOS GENERALES --- */
        .sweetbite-body {
            font-family: var(--font-body);
            background-color: var(--bg-cream);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .sweetbite-body h1, .sweetbite-body h2, .sweetbite-body h3, .sweetbite-body .logo, .sweetbite-body .section-title {
            font-family: var(--font-heading);
        }

        .sweetbite-body a {
            text-decoration: none;
            color: inherit;
        }

        .sweetbite-body ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sweetbite-body img {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }

        .sweetbite-body .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* --- BOTONES --- */
        .sweetbite-body .btn-primary {
            display: inline-block;
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sweetbite-body .btn-primary:hover {
            background-color: var(--accent-red);
            color: var(--white);
        }

        .sweetbite-body .btn-quick-add {
            width: 100%;
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.3s;
            margin-top: auto;
        }

        .sweetbite-body .btn-quick-add:hover {
            background-color: var(--accent-red);
        }

        /* --- HEADER / NAV --- */
        .sweetbite-body header {
            background-color: var(--bg-cream);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .sweetbite-body .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
        }

        .sweetbite-body .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .sweetbite-body .nav-menu {
            display: flex;
            gap: 32px;
        }

        .sweetbite-body .nav-menu a {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        .sweetbite-body .nav-menu a:hover, .sweetbite-body .nav-menu a.active {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .sweetbite-body .nav-icons {
            display: flex;
            gap: 20px;
            font-size: 18px;
            color: var(--primary-dark);
            cursor: pointer;
        }

        /* --- SECCIÓN HERO --- */
        .sweetbite-body .hero {
            padding: 60px 0;
        }

        .sweetbite-body .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 48px;
        }

        .sweetbite-body .hero-content h1 {
            font-size: 48px;
            color: var(--primary-dark);
            line-height: 1.2;
            margin-bottom: 24px;
        }

        .sweetbite-body .hero-content p {
            color: var(--text-muted);
            font-size: 16px;
            margin-bottom: 32px;
            max-width: 450px;
        }

        .sweetbite-body .hero-image img {
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            width: 100%;
            height: 450px;
            object-fit: cover;
        }

        /* --- BEST SELLERS / FAVORITOS --- */
        .sweetbite-body .best-sellers {
            background-color: var(--bg-light-gray);
            padding: 80px 0;
        }

        .sweetbite-body .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 40px;
        }

        .sweetbite-body .section-subtitle {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--text-muted);
            margin-bottom: 8px;
            font-weight: 700;
        }

        .sweetbite-body .section-title {
            font-size: 32px;
            color: var(--primary-dark);
            margin: 0;
        }

        .sweetbite-body .see-all-link {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-dark);
            border-bottom: 1px solid var(--primary-dark);
            padding-bottom: 4px;
        }

        .sweetbite-body .product-grid-3, .sweetbite-body .product-grid-catalog {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .sweetbite-body .product-card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .sweetbite-body .product-img-wrapper {
            position: relative;
            background-color: #EFECE6;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .sweetbite-body .product-img-wrapper img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .sweetbite-body .product-card:hover .product-img-wrapper img {
            transform: scale(1.05);
        }

        .sweetbite-body .badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background-color: var(--white);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .sweetbite-body .badge.pink { background-color: #FCE4E6; color: #731A24;}
        .sweetbite-body .badge.green { background-color: #E2F0D9; color: #385723;}
        .sweetbite-body .badge.purple { background-color: #E8E1F5; color: #4A2E80;}

        .sweetbite-body .heart-icon {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: var(--white);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--text-muted);
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            z-index: 10;
            transition: color 0.3s;
        }
        
        .sweetbite-body .heart-icon:hover {
            color: var(--accent-red);
        }

        .sweetbite-body .product-info-meta {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
        }

        .sweetbite-body .product-title {
            font-size: 18px;
            color: var(--primary-dark);
            margin: 0;
            font-weight: 700;
        }

        .sweetbite-body .product-price {
            font-weight: 700;
            font-size: 16px;
            color: var(--accent-red);
        }

        .sweetbite-body .product-desc {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 20px;
            min-height: 40px;
        }

        .sweetbite-body .product-unit {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        /* --- SECCIÓN CALIDAD --- */
        .sweetbite-body .quality {
            padding: 100px 0;
        }

        .sweetbite-body .quality-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .sweetbite-body .quality-images {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .sweetbite-body .quality-images img:nth-child(1) {
            width: 45%;
            margin-top: 40px;
        }

        .sweetbite-body .quality-images img:nth-child(2) {
            width: 55%;
        }

        .sweetbite-body .quality-content h2 {
            font-size: 36px;
            color: var(--primary-dark);
            margin-bottom: 20px;
        }

        .sweetbite-body .quality-content p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 30px;
        }

        .sweetbite-body .checklist li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        .sweetbite-body .checklist i {
            color: var(--accent-red);
        }

        /* --- CATALOGO --- */
        .sweetbite-body .collection-section {
            padding: 80px 0;
            background-color: var(--bg-cream);
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        .sweetbite-body .collection-intro {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .sweetbite-body .collection-intro h2 {
            font-size: 40px;
            color: var(--primary-dark);
            margin-bottom: 16px;
        }

        .sweetbite-body .collection-intro p {
            color: var(--text-muted);
            font-size: 15px;
        }

        .sweetbite-body .filter-sort-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .sweetbite-body .filter-tags {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .sweetbite-body .tag {
            background-color: var(--bg-light-gray);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            color: var(--text-muted);
            transition: all 0.3s;
        }

        .sweetbite-body .tag.active, .sweetbite-body .tag:hover {
            background-color: var(--primary-dark);
            color: var(--white);
        }

        .sweetbite-body .tag.special-tag {
            background-color: #FCE4E6;
            color: #731A24;
        }
        .sweetbite-body .tag.special-tag.active {
            background-color: var(--accent-red);
            color: var(--white);
        }

        .sweetbite-body .sort-select-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .sweetbite-body .sort-select {
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.1);
            background-color: var(--white);
            font-family: inherit;
            color: var(--text-dark);
            outline: none;
        }

        /* Paginación */
        .sweetbite-body .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 40px;
        }

        .sweetbite-body .page-btn {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.1);
            background-color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sweetbite-body .page-btn.active, .sweetbite-body .page-btn:hover {
            background-color: var(--primary-dark);
            color: var(--white);
            border-color: var(--primary-dark);
        }

        /* --- TESTIMONIOS --- */
        .sweetbite-body .testimonials {
            background-color: var(--bg-pink);
            padding: 80px 0;
            text-align: center;
        }

        .sweetbite-body .testimonials h2 {
            font-size: 32px;
            color: var(--primary-dark);
            margin-bottom: 40px;
        }

        .sweetbite-body .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .sweetbite-body .testimonial-card {
            background-color: var(--white);
            padding: 32px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sweetbite-body .stars {
            color: var(--primary-dark);
            font-size: 12px;
            margin-bottom: 16px;
        }

        .sweetbite-body .testimonial-text {
            font-size: 14px;
            color: var(--text-dark);
            font-style: italic;
            margin-bottom: 20px;
        }

        .sweetbite-body .testimonial-author {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* --- NEWSLETTER --- */
        .sweetbite-body .newsletter-section {
            padding: 60px 0;
        }

        .sweetbite-body .newsletter-box {
            background-color: var(--accent-red);
            background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.05) 0%, transparent 40%),
                              radial-gradient(circle at 80% 70%, rgba(255,255,255,0.05) 0%, transparent 40%);
            border-radius: 16px;
            padding: 60px 40px;
            text-align: center;
            color: var(--white);
        }

        .sweetbite-body .newsletter-box h2 {
            margin-top: 0;
            font-size: 32px;
            margin-bottom: 12px;
        }

        .sweetbite-body .newsletter-box p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 24px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .sweetbite-body .newsletter-form {
            display: flex;
            max-width: 500px;
            margin: 0 auto;
            gap: 12px;
            background-color: transparent;
        }

        .sweetbite-body .newsletter-form input {
            flex: 1;
            padding: 14px 20px;
            border-radius: 6px;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .sweetbite-body .newsletter-form button {
            background-color: var(--primary-dark);
            color: var(--white);
            border: none;
            padding: 0 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sweetbite-body .newsletter-form button:hover {
            background-color: #240a0b;
        }

        /* --- FOOTER --- */
        .sweetbite-body footer {
            background-color: var(--bg-light-gray);
            padding: 60px 0 30px;
            font-size: 13px;
            color: var(--text-muted);
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        .sweetbite-body .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .sweetbite-body .footer-brand h3 {
            font-size: 20px;
            color: var(--primary-dark);
            margin-bottom: 12px;
            margin-top: 0;
        }

        .sweetbite-body .footer-brand p {
            max-width: 250px;
        }

        .sweetbite-body .footer-column h4 {
            color: var(--primary-dark);
            font-weight: 600;
            margin-bottom: 16px;
            margin-top: 0;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sweetbite-body .footer-column ul li {
            margin-bottom: 10px;
        }

        .sweetbite-body .footer-column ul li a:hover {
            color: var(--primary-dark);
        }

        .sweetbite-body .footer-social-icons {
            display: flex;
            gap: 16px;
            font-size: 18px;
            color: var(--primary-dark);
        }

        .sweetbite-body .footer-bottom {
            border-top: 1px solid rgba(0,0,0,0.05);
            padding-top: 24px;
            text-align: left;
            font-size: 12px;
        }

        /* --- RESPONSIVE DESKTOP FIRST --- */
        @media (max-width: 992px) {
            .sweetbite-body .hero-grid, .sweetbite-body .quality-grid, .sweetbite-body .product-grid-3, .sweetbite-body .product-grid-catalog, .sweetbite-body .testimonial-grid {
                grid-template-columns: 1fr;
            }
            .sweetbite-body .hero-grid { text-align: center; }
            .sweetbite-body .hero-content p { margin: 0 auto 32px; }
            .sweetbite-body .quality-images { justify-content: center; }
            .sweetbite-body .footer-grid { grid-template-columns: 1fr 1fr; }
            .sweetbite-body .filter-sort-bar { flex-direction: column; align-items: flex-start; }
        }
    </style>

    <div class="sweetbite-body">
        <header>
            <div class="container navbar">
                <div class="logo">SweetBite</div>
                <nav class="nav-menu">
                    <a href="#" class="active">Inicio</a>
                    <a href="#catalog">Catálogo</a>
                    <a href="#quality">Nuestra Historia</a>
                    <a href="#newsletter">Suscripciones</a>
                </nav>
                <div class="nav-icons">
                    <i class="fa-solid fa-shopping-cart"></i>
                    <i class="fa-regular fa-user"></i>
                </div>
            </div>
        </header>

        <!-- SECCIÓN HERO -->
        <section class="hero">
            <div class="container hero-grid">
                <div class="hero-content">
                    <h1>Momentos dulces, horneados a mano</h1>
                    <p>Descubre la magia en cada bocado con nuestras galletas artesanales, creadas con pasión y los mejores ingredientes locales.</p>
                    <a href="#catalog" class="btn-primary">Explorar Colección</a>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80" alt="Galletas Horneadas de SweetBite">
                </div>
            </div>
        </section>

        <!-- BEST SELLERS -->
        <section class="best-sellers">
            <div class="container">
                <div class="section-header">
                    <div>
                        <div class="section-subtitle">Los Favoritos</div>
                        <h2 class="section-title">Best Sellers</h2>
                    </div>
                    <a href="#catalog" class="see-all-link">Ver todo el menú →</a>
                </div>

                <div class="product-grid-3">
                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1558961317-1943cbd213be?auto=format&fit=crop&w=600&q=80" alt="Classic Choc">
                        </div>
                        <div class="product-info-meta">
                            <h3 class="product-title">Classic Choc</h3>
                            <span class="product-price">$3.50</span>
                        </div>
                        <p class="product-desc">Nuestra receta original con chips de chocolate 70% cacao.</p>
                        <button class="btn-quick-add" onclick="addToCart('Classic Choc')"><i class="fa-solid fa-plus"></i> Quick Add</button>
                    </div>

                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1618923850107-d1a234d7a73a?auto=format&fit=crop&w=600&q=80" alt="Velvet Red">
                        </div>
                        <div class="product-info-meta">
                            <h3 class="product-title">Velvet Red</h3>
                            <span class="product-price">$3.75</span>
                        </div>
                        <p class="product-desc">Suave como el terciopelo con notas de cacao y chocolate blanco.</p>
                        <button class="btn-quick-add" onclick="addToCart('Velvet Red')"><i class="fa-solid fa-plus"></i> Quick Add</button>
                    </div>

                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <img src="https://images.unsplash.com/photo-1576618148400-f54bed99fcfd?auto=format&fit=crop&w=600&q=80" alt="Salted Caramel">
                        </div>
                        <div class="product-info-meta">
                            <h3 class="product-title">Salted Caramel</h3>
                            <span class="product-price">$3.95</span>
                        </div>
                        <p class="product-desc">Caramelo artesanal y un toque de sal marina de la costa.</p>
                        <button class="btn-quick-add" onclick="addToCart('Salted Caramel')"><i class="fa-solid fa-plus"></i> Quick Add</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- NUESTRA CALIDAD -->
        <section class="quality" id="quality">
            <div class="container quality-grid">
                <div class="quality-images">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=400&q=80" alt="Harina espolvoreada">
                    <img src="https://images.unsplash.com/photo-1549590143-d5855148a9d5?auto=format&fit=crop&w=500&q=80" alt="Amasando galletas">
                </div>
                <div class="quality-content">
                    <h2>Calidad que puedes saborear</h2>
                    <p>En SweetBite, creemos que lo extraordinario nace de lo simple. Por eso, solo utilizamos ingredientes naturales y locales. Nuestra harina es molida en piedra, nuestra mantequilla proviene de granjas cercanas y nuestro chocolate es de comercio justo. Cada galleta se hornea a mano en pequeños lotes para garantizar esa textura perfecta que tanto amas.</p>
                    
                    <ul class="checklist">
                        <li><i class="fa-regular fa-circle-check"></i> 100% Ingredientes Naturales</li>
                        <li><i class="fa-regular fa-circle-check"></i> Sin Conservantes Artificiales</li>
                        <li><i class="fa-regular fa-circle-check"></i> Horneado Diario con Amor</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- CATALOGO COMPLETO -->
        <section class="collection-section" id="catalog">
            <div class="container">
                <div class="collection-intro">
                    <h2>The Cookie Collection</h2>
                    <p>Discover our curated selection of artisanal cookies, baked fresh daily using the finest ingredients and traditional techniques.</p>
                </div>

                <div class="filter-sort-bar">
                    <div class="filter-tags">
                        <button class="tag active" onclick="filterCategory('all', this)">All Treats</button>
                        <button class="tag" onclick="filterCategory('clasicas', this)">Clásicas</button>
                        <button class="tag" onclick="filterCategory('rellenas', this)">Rellenas</button>
                        <button class="tag" onclick="filterCategory('veganas', this)">Veganas</button>
                        <button class="tag special-tag" onclick="filterCategory('limitada', this)">Edición Limitada</button>
                    </div>
                    <div class="sort-select-wrapper">
                        <span>Sort by:</span>
                        <select class="sort-select">
                            <option>Popularity</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <div class="product-grid-catalog" id="catalog-grid">
                    <!-- Producto 1 -->
                    <div class="product-card" data-category="clasicas">
                        <div class="product-img-wrapper">
                            <span class="badge pink">Top Seller</span>
                            <span class="heart-icon"><i class="fa-regular fa-heart"></i></span>
                            <img src="https://images.unsplash.com/photo-1607958996333-41aef7caefaa?auto=format&fit=crop&w=600&q=80" alt="Sea Salt Dark Chocolate">
                        </div>
                        <div class="product-unit">Per Dozen</div>
                        <div class="product-info-meta">
                            <h3 class="product-title">Sea Salt Dark Chocolate</h3>
                            <span class="product-price">$32.00</span>
                        </div>
                        <p class="product-desc">Our signature classic dough infused with 70% cacao Belgian chocolate chunks and Malton sea salt.</p>
                        <button class="btn-quick-add" onclick="addToCart('Sea Salt Dark Chocolate')">Add to Box</button>
                    </div>

                    <!-- Producto 2 -->
                    <div class="product-card" data-category="clasicas">
                        <div class="product-img-wrapper">
                            <span class="badge">New Arrival</span>
                            <span class="heart-icon"><i class="fa-regular fa-heart"></i></span>
                            <img src="https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=600&q=80" alt="Pistachio Dream">
                        </div>
                        <div class="product-unit">Per Dozen</div>
                        <div class="product-info-meta">
                            <h3 class="product-title">Pistachio Dream</h3>
                            <span class="product-price">$36.00</span>
                        </div>
                        <p class="product-desc">Toasted Iranian pistachios paired with velvet white chocolate and a hint of cardamom.</p>
                        <button class="btn-quick-add" onclick="addToCart('Pistachio Dream')">Add to Box</button>
                    </div>

                    <!-- Producto 3 -->
                    <div class="product-card" data-category="rellenas">
                        <div class="product-img-wrapper">
                            <span class="badge pink">Rellenas</span>
                            <span class="heart-icon"><i class="fa-regular fa-heart"></i></span>
                            <img src="https://images.unsplash.com/photo-1618923850107-d1a234d7a73a?auto=format&fit=crop&w=600&q=80" alt="Red Velvet Lava">
                        </div>
                        <div class="product-unit">Per Dozen</div>
                        <div class="product-info-meta">
                            <h3 class="product-title">Red Velvet Lava</h3>
                            <span class="product-price">$38.00</span>
                        </div>
                        <p class="product-desc">Deep cocoa red velvet dough stuffed with a tangy cream cheese frosting core.</p>
                        <button class="btn-quick-add" onclick="addToCart('Red Velvet Lava')">Add to Box</button>
                    </div>
                </div>

                <!-- Paginación -->
                <div class="pagination">
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </section>

        <!-- TESTIMONIOS -->
        <section class="testimonials">
            <div class="container">
                <h2>Lo que dicen nuestros SweetLovers</h2>
                <div class="testimonial-grid">
                    <div class="testimonial-card">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Las mejores galletas que he probado en mi vida. La de Salted Caramel es simplemente de otro mundo."</p>
                        <span class="testimonial-author">— María G.</span>
                    </div>
                    <div class="testimonial-card">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"El regalo perfecto. El packaging es tan cuidado como el sabor. ¡Repetiré seguro!"</p>
                        <span class="testimonial-author">— Carlos R.</span>
                    </div>
                    <div class="testimonial-card">
                        <div class="stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="testimonial-text">"Se nota que usan ingredientes de verdad. Me encanta que apoyen a los productores locales."</p>
                        <span class="testimonial-author">— Elena M.</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- NEWSLETTER -->
        <section class="newsletter-section" id="newsletter">
            <div class="container">
                <div class="newsletter-box">
                    <h2>¿Quieres un bocado gratis?</h2>
                    <p>Suscríbete a nuestra newsletter y recibe un 10% de descuento en tu primer pedido y noticias sobre nuevos sabores exclusivos.</p>
                    <form class="newsletter-form" onsubmit="event.preventDefault();">
                        <input type="email" placeholder="Tu correo electrónico">
                        <button type="submit">Suscribirme</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer>
            <div class="container footer-grid">
                <div class="footer-brand">
                    <h3>SweetBite</h3>
                    <p>© 2026 SweetBite Artisanal Cookies. Handcrafted with care using only the finest local ingredients.</p>
                </div>
                <div class="footer-column">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="#newsletter">Newsletter</a></li>
                        <li><a href="#quality">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Help</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Shipping Info</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Social</h4>
                    <div class="footer-social-icons">
                        <i class="fa-brands fa-instagram"></i>
                        <i class="fa-brands fa-facebook"></i>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <?php
    return ob_get_clean();
}
// Registro oficial del shortcode en WordPress
add_shortcode('mi_landing_completa', 'renderizar_landing_sweetbite');