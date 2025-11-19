<?php
if (!defined('ABSPATH')) exit;

class FGP_Gallery_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'fgp_gallery_widget';
    }

    public function get_title() {
        return 'FGP Gallery';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['fgp-widgets'];
    }

    public function get_style_depends() {
        return ['fgp-gallery-style'];
    }

    protected function register_controls() {

        // Título general
        $this->start_controls_section(
            'section_title',
            [ 'label' => 'Título General' ]
        );

        $this->add_control(
            'gallery_title',
            [
                'label' => 'Título',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Mi Galería',
            ]
        );

        $this->end_controls_section();

        // Repeater
        $this->start_controls_section(
            'section_gallery',
            [ 'label' => 'Imágenes con título' ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label' => 'Imagen',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
            ]
        );

        $repeater->add_control(
            'image_title',
            [
                'label' => 'Título de la imagen',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Título',
            ]
        );

        $this->add_control(
            'items',
            [
                'label'       => 'Ítems de galería',
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [],
                'title_field' => '{{{ image_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $title    = $settings['gallery_title'];
        $items    = $settings['items'];

        echo '<div class="fgp-gallery">';

        if ($title) {
            echo '<h3 class="fgp-gallery__title">' . esc_html($title) . '</h3>';
        }

        echo '<div class="fgp-gallery__grid">';

        if (!empty($items)) {
            foreach ($items as $item) {

                $img_url   = $item['image']['url'] ?? '';
                $img_title = $item['image_title'];

                echo '<div class="fgp-gallery__item">';

                if ($img_url) {
                    echo '<img src="' . esc_url($img_url) . '" class="fgp-gallery__img">';
                }

                if ($img_title) {
                    echo '<div class="fgp-gallery__caption">' . esc_html($img_title) . '</div>';
                }

                echo '</div>';
            }
        }

        echo '</div></div>';
    }
}
