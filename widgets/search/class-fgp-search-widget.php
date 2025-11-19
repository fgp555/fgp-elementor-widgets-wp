<?php
if (!defined('ABSPATH')) exit;

class FGP_Search_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'fgp_search_widget';
    }

    public function get_title()
    {
        return 'FGP Search';
    }

    public function get_icon()
    {
        return 'eicon-search';
    }

    public function get_categories()
    {
        return ['fgp-widgets'];
    }

    public function get_style_depends()
    {
        return ['fgp-search-style'];
    }

    public function get_script_depends()
    {
        return ['fgp-search-js'];
    }


    protected function register_controls()
    {
        $this->start_controls_section('section_settings', [
            'label' => 'Configuración de Búsqueda'
        ]);

        $this->add_control('placeholder', [
            'label' => 'Placeholder',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Buscar...',
        ]);

        $this->add_control('post_type', [
            'label' => 'Tipo de contenido',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'post',
            'options' => [
                'post'    => 'Posts',
                'page'    => 'Páginas',
                'product' => 'Productos (WooCommerce)',
            ],
        ]);

        // Botón de búsqueda
        $this->add_control('button_text', [
            'label' => 'Texto del botón',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Buscar',
        ]);

        $this->add_control('button_icon', [
            'label' => 'Icono (opcional)',
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value'   => 'eicon-search',
                'library' => 'elementor',
            ],
        ]);

        // Activar o no búsqueda en vivo
        $this->add_control('live_search', [
            'label' => 'Búsqueda en vivo (al escribir)',
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
            'label_on' => 'Sí',
            'label_off' => 'No',
        ]);

        $this->end_controls_section();
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo '<div class="fgp-search" 
                data-type="' . esc_attr($settings['post_type']) . '" 
                data-live="' . esc_attr($settings['live_search']) . '">';

        echo '<input type="text" 
                class="fgp-search__input" 
                placeholder="' . esc_attr($settings['placeholder']) . '">';

        // BOTÓN
        echo '<button class="fgp-search__button">';

        if (!empty($settings['button_text'])) {
            echo esc_html($settings['button_text']);
        }

        if (!empty($settings['button_icon']['value'])) {
            \Elementor\Icons_Manager::render_icon(
                $settings['button_icon'],
                ['aria-hidden' => 'true']
            );
        }

        echo '</button>';

        echo '<div class="fgp-search__results"></div>';
        echo '</div>';
    }
}
