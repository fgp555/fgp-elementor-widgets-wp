<?php
/**
 * Plugin Name: FGP Elementor Widgets
 * Description: Widgets personalizados para Elementor (Hello World + Gallery).
 * Version: 1.1.0
 * Author: Frank GP
 * Author URI: https://frankgp.com
 * Text Domain: fgp-elementor-widgets
 */

if (!defined('ABSPATH')) exit;

// Registrar widgets
add_action('elementor/widgets/register', function($widgets_manager) {

    require_once(__DIR__ . '/widgets/hello/class-fgp-hello-widget.php');
    $widgets_manager->register(new \FGP_Hello_Widget());

    require_once(__DIR__ . '/widgets/gallery/class-fgp-gallery-widget.php');
    $widgets_manager->register(new \FGP_Gallery_Widget());
});

// Registrar categoría personalizada
add_action('elementor/elements/categories_registered', function($elements_manager) {
    $elements_manager->add_category(
        'fgp-widgets',
        [
            'title' => 'FGP Widgets',
            'icon'  => 'fa fa-plug',
        ]
    );
});

// Cargar CSS global para live reload
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'fgp-elementor-widgets',
        plugin_dir_url(__FILE__) . 'assets/css/fgp-widgets.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'assets/css/fgp-widgets.css')
    );
});

// Registrar CSS por widget
add_action('wp_enqueue_scripts', function() {

    wp_register_style(
        'fgp-gallery-style',
        plugins_url('widgets/gallery/gallery.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/gallery/gallery.css')
    );

    wp_register_style(
        'fgp-hello-style',
        plugins_url('widgets/hello/hello.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/hello/hello.css')
    );
});
