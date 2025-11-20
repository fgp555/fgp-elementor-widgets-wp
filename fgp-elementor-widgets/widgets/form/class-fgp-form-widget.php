<?php
if (!defined('ABSPATH')) exit;

class FGP_Form_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'fgp_form_widget';
    }

    public function get_title()
    {
        return __('FGP Formulario', 'fgp-elementor-widgets');
    }

    public function get_icon()
    {
        return 'eicon-form-horizontal';
    }

    public function get_categories()
    {
        return ['fgp-widgets'];
    }

    public function get_style_depends()
    {
        return ['fgp-form-style'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_form',
            [
                'label' => __('Formulario', 'fgp-elementor-widgets'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Texto del botón', 'fgp-elementor-widgets'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Enviar',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
?>

        <form class="fgp-form" method="post">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="celular" placeholder="Celular" required>
            <textarea name="comentario" placeholder="Comentario" required></textarea>
            <button type="submit"><?php echo esc_html($settings['button_text']); ?></button>
        </form>

<?php
    }
}
