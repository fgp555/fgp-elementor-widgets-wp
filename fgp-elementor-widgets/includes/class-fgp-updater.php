<?php
if (!defined('ABSPATH')) exit;

class FGP_Remote_Updater
{

    private $plugin_slug = 'fgp-elementor-widgets';
    private $plugin_file = 'fgp-elementor-widgets/fgp-elementor-widgets.php';

    // URL que contiene el ZIP
    // private $remote_zip = 'https://live.fgp.one/fgp-elementor-widgets.zip';
    // private $remote_zip = 'https://github.com/fgp555/fgp-elementor-widgets-wp/raw/refs/heads/main/fgp-elementor-widgets.zip';
    private $remote_zip = 'http://gh.fgp.one/fgp-elementor-widgets-wp/fgp-elementor-widgets.zip';

    // URL donde guardas un JSON con la versión y notas
    // private $remote_version_url = 'https://live.fgp.one/fgp-version.json';
    // private $remote_version_url = 'https://raw.githubusercontent.com/fgp555/fgp-elementor-widgets-wp/refs/heads/main/fgp-version.json';
    private $remote_version_url = 'http://gh.fgp.one/fgp-elementor-widgets-wp/fgp-version.json';



    public function __construct()
    {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
    }

    // ----------------------------
    // VERIFICAR VERSIÓN REMOTA
    // ----------------------------
    public function get_remote_version()
    {
        $response = wp_remote_get($this->remote_version_url, ['timeout' => 10]);
        if (is_wp_error($response)) return false;

        $data = json_decode(wp_remote_retrieve_body($response));
        return $data;
    }

    // ----------------------------
    // CHEQUEAR SI HAY UPDATE
    // ----------------------------
    public function check_for_update($transient)
    {

        if (empty($transient->checked)) return $transient;

        $remote = $this->get_remote_version();
        if (!$remote) return $transient;

        $current_version = $transient->checked[$this->plugin_file];

        if (version_compare($current_version, $remote->version, '<')) {

            $obj = new stdClass();
            $obj->slug = $this->plugin_slug;
            $obj->plugin = $this->plugin_file;
            $obj->new_version = $remote->version;
            $obj->package = $this->remote_zip;
            $obj->tested = $remote->tested;
            $obj->requires = $remote->requires;

            $transient->response[$this->plugin_file] = $obj;
        }

        return $transient;
    }

    // ----------------------------
    // INFO DEL PLUGIN (POPUP)
    // ----------------------------
    public function plugin_info($false, $action, $args)
    {

        if ($args->slug !== $this->plugin_slug) return false;

        $remote = $this->get_remote_version();
        if (!$remote) return false;

        $obj = new stdClass();
        $obj->name = "FGP Elementor Widgets";
        $obj->slug = $this->plugin_slug;
        $obj->version = $remote->version;
        $obj->download_link = $this->remote_zip;
        $obj->tested = $remote->tested;
        $obj->requires = $remote->requires;
        $obj->last_updated = $remote->last_updated;
        $obj->sections = [
            'description' => $remote->description,
            'changelog' => $remote->changelog
        ];

        return $obj;
    }
}
