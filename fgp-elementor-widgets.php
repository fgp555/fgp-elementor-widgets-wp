<?php

/**
 * Plugin Name: .FGP Elementor Widgets
 * Description: Widgets personalizados para Elementor (Hello World + Gallery).
 * Version: 1.1.0
 * Author: Frank GP
 * Author URI: https://frankgp.com
 * Text Domain: fgp-elementor-widgets
 */

if (!defined('ABSPATH')) exit;

// Registrar widgets
add_action('elementor/widgets/register', function ($widgets_manager) {

    require_once(__DIR__ . '/widgets/hello/class-fgp-hello-widget.php');
    $widgets_manager->register(new \FGP_Hello_Widget());

    require_once(__DIR__ . '/widgets/gallery/class-fgp-gallery-widget.php');
    $widgets_manager->register(new \FGP_Gallery_Widget());

    require_once(__DIR__ . '/widgets/search/class-fgp-search-widget.php');
    $widgets_manager->register(new \FGP_Search_Widget());

    require_once(__DIR__ . '/widgets/search-results/class-fgp-search-results-widget.php');
    $widgets_manager->register(new \FGP_Search_Results_Widget());
});


// Registrar categoría personalizada *al inicio*
add_action('elementor/elements/categories_registered', function ($elements_manager) {

    // Agregar tu categoría primero
    $elements_manager->add_category(
        'fgp-widgets',
        [
            'title' => '.FGP Widgets',
            'icon'  => 'fa fa-plug',
        ],
        0 // ← prioridad más alta (primero)
    );
}, 0); // ← prioridad del hook en WordPress


// Cargar CSS global para live reload
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'fgp-elementor-widgets',
        plugin_dir_url(__FILE__) . 'assets/css/fgp-widgets.css',
        [],
        filemtime(plugin_dir_path(__FILE__) . 'assets/css/fgp-widgets.css')
    );
});

// Registrar CSS por widget
add_action('wp_enqueue_scripts', function () {

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

    wp_register_style(
        'fgp-search-style',
        plugins_url('widgets/search/search.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/search/search.css')
    );

    wp_register_script(
        'fgp-search-js',
        plugins_url('widgets/search/search.js', __FILE__),
        ['jquery'],
        filemtime(plugin_dir_path(__FILE__) . 'widgets/search/search.js'),
        true
    );

    wp_localize_script('fgp-search-js', 'fgp_search', [
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
});


add_action('wp_ajax_fgp_live_search', 'fgp_live_search_handler');
add_action('wp_ajax_nopriv_fgp_live_search', 'fgp_live_search_handler');

function fgp_live_search_handler()
{
    $keyword   = sanitize_text_field($_POST['keyword'] ?? '');
    $post_type = sanitize_text_field($_POST['post_type'] ?? 'post');

    $query = new WP_Query([
        'post_type'      => $post_type,
        's'              => $keyword,
        'posts_per_page' => 10
    ]);

    ob_start();

    if (!$query->have_posts()) {
        echo '<div class="fgp-search-item">No hay resultados.</div>';
    } else {
        while ($query->have_posts()) {
            $query->the_post();

            echo '<div class="fgp-search-item">';
            echo '<a href="' . get_permalink() . '">';
            echo '<strong>' . get_the_title() . '</strong><br>';
            echo '<span>' . wp_trim_words(get_the_excerpt(), 15) . '</span>';
            echo '</a>';
            echo '</div>';
        }
    }

    $html = ob_get_clean();

    // Guardar en base de datos (visible para el widget)
    update_option('fgp_last_search_results', $html);

    echo 'success';
    wp_die();
}



add_action('elementor/widgets/register', function ($widgets_manager) {

    require_once(__DIR__ . '/widgets/hello/class-fgp-hello-widget.php');
    $widgets_manager->register(new \FGP_Hello_Widget());

    require_once(__DIR__ . '/widgets/gallery/class-fgp-gallery-widget.php');
    $widgets_manager->register(new \FGP_Gallery_Widget());

    require_once(__DIR__ . '/widgets/search/class-fgp-search-widget.php');
    $widgets_manager->register(new \FGP_Search_Widget());

    // 🟩 AÑADE AQUÍ el nuevo widget — DENTRO DEL HOOK
    require_once(__DIR__ . '/widgets/search-results/class-fgp-search-results-widget.php');
    $widgets_manager->register(new \FGP_Search_Results_Widget());
});
