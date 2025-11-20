<?php
if (!defined('ABSPATH')) exit;

class FGP_Products_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'fgp_products_widget';
    }
    public function get_title()
    {
        return __('FGP Productos', 'fgp-elementor-widgets');
    }
    public function get_icon()
    {
        return 'eicon-products';
    }
    public function get_categories()
    {
        return ['fgp-widgets'];
    }
    public function get_style_depends()
    {
        return ['fgp-products-style'];
    }
    public function get_script_depends()
    {
        return ['fgp-products-js'];
    }

    protected function register_controls()
    {
        // ------------------------------
        // Configuración básica
        // ------------------------------
        $this->start_controls_section('section_settings', [
            'label' => __('Configuración de Productos', 'fgp-elementor-widgets')
        ]);

        $this->add_control('columns', [
            'label' => __('Columnas de productos', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min' => 1,
            'max' => 6,
        ]);

        $this->add_control('products_per_page', [
            'label' => __('Cantidad de productos', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min' => 1,
            'max' => 200,
        ]);

        $repeater = new \Elementor\Repeater();
        $repeater->add_control('cat_id', [
            'label' => __('Categoría', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::SELECT2,
            'options' => $this->get_product_categories(),
            'label_block' => true,
        ]);
        $repeater->add_control('cat_label', [
            'label' => __('Nombre a mostrar', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ]);

        $this->add_control('categories', [
            'label' => __('Categorías', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'title_field' => '{{cat_label}}',
        ]);

        $this->end_controls_section();

        // ------------------------------
        // Estilo de Productos
        // ------------------------------
        $this->start_controls_section('section_style_products', [
            'label' => __('Estilo de Productos', 'fgp-elementor-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        // Columnas productos
        $this->add_control('products_columns', [
            'label' => __('Columnas de productos', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min' => 1,
            'max' => 6,
            'selectors' => [
                '{{WRAPPER}} .fgp-products-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr); display: grid; gap: 20px;',
            ],
        ]);

        // Background productos
        $this->add_control('product_bg', [
            'label' => __('Color de fondo del producto', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fgp-product-item' => 'background-color: {{VALUE}};',
            ],
        ]);

        // Tipografía título
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'label' => __('Tipografía del título', 'fgp-elementor-widgets'),
            'selector' => '{{WRAPPER}} .fgp-product-item h3',
        ]);

        // Color título
        $this->add_control('title_color', [
            'label' => __('Color del título', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fgp-product-item h3' => 'color: {{VALUE}};',
            ],
        ]);

        // Tipografía precio
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'price_typography',
            'label' => __('Tipografía del precio', 'fgp-elementor-widgets'),
            'selector' => '{{WRAPPER}} .fgp-product-item .price',
        ]);

        // Color precio
        $this->add_control('price_color', [
            'label' => __('Color del precio', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fgp-product-item .price' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // ------------------------------
        // Estilo de Categorías
        // ------------------------------
        $this->start_controls_section('section_style_categories', [
            'label' => __('Estilo de Categorías', 'fgp-elementor-widgets'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        // Columnas categorías
        $this->add_control('categories_columns', [
            'label' => __('Columnas de categorías', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 4,
            'min' => 1,
            'max' => 6,
            'selectors' => [
                '{{WRAPPER}} .fgp-product-categories ul' => 'display: grid; grid-template-columns: repeat({{VALUE}}, 1fr); gap: 15px;',
            ],
        ]);

        // Background categoría
        $this->add_control('category_bg', [
            'label' => __('Color de fondo de la categoría', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fgp-product-categories li.fgp-cat' => 'background-color: {{VALUE}};',
            ],
        ]);

        // Tipografía nombre de categoría
        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'category_typography',
            'label' => __('Tipografía del nombre', 'fgp-elementor-widgets'),
            'selector' => '{{WRAPPER}} .fgp-product-categories li.fgp-cat span',
        ]);

        // Color nombre de categoría
        $this->add_control('category_color', [
            'label' => __('Color del nombre', 'fgp-elementor-widgets'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fgp-product-categories li.fgp-cat span' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }



    private function get_product_categories()
    {
        $cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
        $options = [];
        foreach ($cats as $cat) $options[$cat->term_id] = $cat->name;
        return $options;
    }

    protected function render()
    {
        if (!class_exists('WooCommerce')) {
            echo '<p>WooCommerce no está activo</p>';
            return;
        }

        $settings = $this->get_settings_for_display();

        // ---------------------------------------------
        // CONTENEDOR PRINCIPAL
        // ---------------------------------------------
        echo '<div class="fgp-products-widget">';

        // Detectar si estamos en una página de categoría WooCommerce
        $is_product_cat_page = function_exists('is_product_category') && is_product_category();

        // ---------------------------------------------
        // BUSCADOR (OCULTO EN PÁGINA DE CATEGORÍA)
        // ---------------------------------------------
        if (!$is_product_cat_page) {
            echo '<div class="fgp-product-search">';
            echo '<input type="text" class="fgp-search-input" placeholder="Buscar...">';
            echo '<button type="button" class="fgp-clear-search" id="fgp-clear-search">Borrar Filtro</button>';
            echo '</div>';
        }

        // ---------------------------------------------
        // CATEGORÍAS PERSONALIZADAS (OCULTO EN CATEGORÍA)
        // ---------------------------------------------
        if (!$is_product_cat_page) {
            echo '<div class="fgp-product-categories"><ul>';

            if (!empty($settings['categories'])) {
                foreach ($settings['categories'] as $cat) {
                    $cat_id = $cat['cat_id'];
                    $term = get_term($cat_id, 'product_cat');
                    if (!$term) continue;

                    $slug = $term->slug;
                    $label = $cat['cat_label'] ?: $term->name;

                    $thumbnail_id = get_term_meta($cat_id, 'thumbnail_id', true);
                    $img = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';

                    echo '<li class="fgp-cat" data-cat="' . esc_attr($slug) . '">';
                    if ($img) {
                        echo '<img src="' . esc_url($img) . '" alt="' . esc_attr($label) . '" class="fgp-cat-img" />';
                    }
                    echo '<span class="fgp-cat-label">' . esc_html($label) . '</span>';
                    echo '</li>';
                }
            }

            echo '</ul></div>';

            echo '<hr/>';
        }

        // ---------------------------------------------
        // DETECTAR CONTEXTO (TIENDA / CATEGORÍA)
        // ---------------------------------------------
        $taxonomy_filter = [];

        // Página de categoría WooCommerce
        if ($is_product_cat_page) {
            $term = get_queried_object();
            if ($term && isset($term->slug)) {
                $taxonomy_filter = [
                    [
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $term->slug
                    ]
                ];
            }
        }

        // ---------------------------------------------
        // QUERY DE PRODUCTOS
        // ---------------------------------------------
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        if (!empty($taxonomy_filter)) {
            $args['tax_query'] = $taxonomy_filter;
        }

        $products = new WP_Query($args);

        // ---------------------------------------------
        // GRID DE PRODUCTOS
        // ---------------------------------------------
        echo '<div class="fgp-products-grid columns-' . intval($settings['columns']) . '">';

        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                global $product;

                $cats = wp_get_post_terms(get_the_ID(), 'product_cat', ['fields' => 'slugs']);

                echo '<div class="fgp-product-item" data-cats="' . esc_attr(implode(' ', $cats)) . '">';

                echo '<a href="' . get_permalink() . '">';

                echo get_the_post_thumbnail(get_the_ID(), 'medium');

                echo '<h3>' . get_the_title() . '</h3>';

                if ($product) {
                    echo '<span class="price">' . $product->get_price_html() . '</span>';
                }

                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No hay productos disponibles.</p>';
        }

        echo '</div>'; // END GRID

        wp_reset_postdata();

        echo '</div>'; // END WIDGET
    }
}
