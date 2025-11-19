<?php
if (!defined('ABSPATH')) exit;

class FGP_Search_Results_Widget extends \Elementor\Widget_Base
{
    public function get_name() {
        return 'fgp_search_results_widget';
    }

    public function get_title() {
        return 'FGP Search Results';
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['fgp-widgets'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_results',
            ['label' => 'Resultados']
        );

        $this->add_control(
            'no_results_text',
            [
                'label' => 'Texto si no hay resultados',
                'type'  => \Elementor\Controls_Manager::TEXT,
                'default' => 'No se encontraron resultados.',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $results_html = get_option('fgp_last_search_results');

        echo '<div class="fgp-search-results">';

        if ($results_html) {
            echo $results_html;
        } else {
            echo '<div class="fgp-search-item">' . esc_html($settings['no_results_text']) . '</div>';
        }

        echo '</div>';
    }
}
