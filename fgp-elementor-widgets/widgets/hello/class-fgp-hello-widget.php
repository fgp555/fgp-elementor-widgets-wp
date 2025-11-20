<?php
if (!defined('ABSPATH')) exit;

class FGP_Hello_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'fgp_hello_widget';
    }

    public function get_title()
    {
        return 'FGP Hello World';
    }

    public function get_icon()
    {
        return 'eicon-text';
    }

    public function get_categories()
    {
        return ['fgp-widgets'];
    }

    public function get_style_depends()
    {
        return ['fgp-hello-style'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            ['label' => 'Contenido']
        );

        $this->add_control(
            'mensaje',
            [
                'label' => 'Mensaje',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Hello World desde FGP',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo '<h2 class="fgp-hello-text">' . esc_html($settings['mensaje']) . '</h2>';
    }
}
