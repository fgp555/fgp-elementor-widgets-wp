<?php

/**
 * Plugin Name: .FGP Elementor Widgets
 * Description: Widgets personalizados para Elementor (Hello, Gallery, Form).
 * Version: 1.1.1
 * Author: Frank GP
 * Author URI: https://frankgp.com
 * Text Domain: fgp-elementor-widgets
 */

if (!defined('ABSPATH')) exit;

/* -------------------------------------------------------------------------
   REGISTRAR WIDGETS — SOLO UN HOOK
------------------------------------------------------------------------- */
add_action('elementor/widgets/register', function ($widgets_manager) {

    // Hello
    require_once __DIR__ . '/widgets/hello/class-fgp-hello-widget.php';
    $widgets_manager->register(new \FGP_Hello_Widget());

    // Gallery
    require_once __DIR__ . '/widgets/gallery/class-fgp-gallery-widget.php';
    $widgets_manager->register(new \FGP_Gallery_Widget());

    // Form
    require_once __DIR__ . '/widgets/form/class-fgp-form-widget.php';
    $widgets_manager->register(new \FGP_Form_Widget());

    // Productos
    require_once __DIR__ . '/widgets/products/class-fgp-products-widget.php';
    $widgets_manager->register(new \FGP_Products_Widget());
});

/* -------------------------------------------------------------------------
   CATEGORÍA PERSONALIZADA
------------------------------------------------------------------------- */
add_action('elementor/elements/categories_registered', function ($elements_manager) {

    $elements_manager->add_category(
        'fgp-widgets',
        [
            'title' => '.FGP Widgets',
            'icon'  => 'fa fa-plug',
        ],
        0
    );
});

/* -------------------------------------------------------------------------
   ESTILOS Y SCRIPTS
------------------------------------------------------------------------- */
add_action('wp_enqueue_scripts', function () {

    // Estilos globales del plugin
    if (file_exists(plugin_dir_path(__FILE__) . 'assets/css/fgp-widgets.css')) {
        wp_enqueue_style(
            'fgp-elementor-widgets',
            plugin_dir_url(__FILE__) . 'assets/css/fgp-widgets.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/fgp-widgets.css')
        );
    }

    // Gallery CSS
    wp_register_style(
        'fgp-gallery-style',
        plugins_url('widgets/gallery/gallery.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/gallery/gallery.css')
    );

    // Hello CSS
    wp_register_style(
        'fgp-hello-style',
        plugins_url('widgets/hello/hello.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/hello/hello.css')
    );

    // Form CSS
    wp_register_style(
        'fgp-form-style',
        plugins_url('widgets/form/form.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/form/form.css')
    );

    // CSS del widget productos
    wp_register_style(
        'fgp-products-style',
        plugins_url('widgets/products/products.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/products/products.css')
    );

    // JS del widget productos
    wp_register_script(
        'fgp-products-js',
        plugins_url('widgets/products/products.js', __FILE__),
        ['jquery'],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/products/products.js'),
        true
    );
});
