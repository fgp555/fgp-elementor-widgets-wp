<?php
if (!defined('ABSPATH')) exit;

class FGP_Gallery_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'fgp_gallery_widget';
    }

    public function get_title()
    {
        return 'FGP Gallery';
    }

    public function get_icon()
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories()
    {
        return ['fgp-widgets'];
    }

    public function get_style_depends()
    {
        return ['fgp-gallery-style'];
    }

    protected function register_controls()
    {
        /**
         * SECCIÓN: TÍTULO GENERAL
         */
        $this->start_controls_section(
            'section_title',
            ['label' => 'Título General']
        );

        // Mostrar u ocultar título
        $this->add_control(
            'show_title',
            [
                'label'        => 'Mostrar título',
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => 'Sí',
                'label_off'    => 'No',
                'default'      => 'yes',
            ]
        );

        // Texto del título
        $this->add_control(
            'gallery_title',
            [
                'label'   => 'Título',
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'Mi Galería',
                'condition' => ['show_title' => 'yes']
            ]
        );

        // TIPOGRAFÍA DEL TÍTULO GENERAL
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => 'Tipografía del título',
                'selector' => '{{WRAPPER}} .fgp-gallery__title',
                'condition' => ['show_title' => 'yes']
            ]
        );

        $this->end_controls_section();

        /**
         * SECCIÓN: REPEATER
         */
        $this->start_controls_section(
            'section_gallery',
            ['label' => 'Imágenes con título']
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => 'Imagen',
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => ['url' => \Elementor\Utils::get_placeholder_image_src()],
            ]
        );

        $repeater->add_control(
            'image_title',
            [
                'label'   => 'Título de la imagen',
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => 'Título',
            ]
        );

        $repeater->add_control(
            'image_link',
            [
                'label' => 'Enlace',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://tusitio.com',
                'show_external' => true,
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

        // TIPOGRAFÍA DE CAPTIONS
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'caption_typography',
                'label'    => 'Tipografía de los títulos de imágenes',
                'selector' => '{{WRAPPER}} .fgp-gallery__caption',
            ]
        );

        // COLOR DE CAPTIONS
        $this->add_control(
            'caption_color',
            [
                'label'     => 'Color del título de imagen',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fgp-gallery__caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * RENDER HTML
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo '<div class="fgp-gallery">';

        if (!empty($settings['show_title']) && $settings['show_title'] === 'yes' && !empty($settings['gallery_title'])) {
            echo '<h3 class="fgp-gallery__title">' . esc_html($settings['gallery_title']) . '</h3>';
        }

        echo '<div class="fgp-gallery__grid">';

        if (!empty($settings['items'])) {
            foreach ($settings['items'] as $item) {

                $img_url   = $item['image']['url'] ?? '';
                $img_title = $item['image_title'] ?? '';
                $link_data = $item['image_link'] ?? [];

                $link     = $link_data['url'] ?? '';
                $target   = !empty($link_data['is_external']) ? ' target="_blank"' : '';
                $rel_arr  = [];

                if (!empty($link_data['nofollow'])) $rel_arr[] = 'nofollow';
                if (!empty($link_data['is_external'])) $rel_arr[] = 'noopener';

                $rel = !empty($rel_arr) ? ' rel="' . esc_attr(implode(' ', $rel_arr)) . '"' : '';

                echo '<div class="fgp-gallery__item">';

                if ($link) echo '<a href="' . esc_url($link) . '"' . $target . $rel . '>';

                if ($img_url) {
                    echo '<img src="' . esc_url($img_url) . '" class="fgp-gallery__img" alt="' . esc_attr($img_title) . '">';
                }

                if ($img_title) {
                    echo '<div class="fgp-gallery__caption">' . esc_html($img_title) . '</div>';
                }

                if ($link) echo '</a>';

                echo '</div>';
            }
        }

        echo '</div></div>';
    }
}
