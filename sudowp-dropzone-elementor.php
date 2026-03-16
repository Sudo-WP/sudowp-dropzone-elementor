<?php
/**
 * Plugin Name: SudoWP DropZone for Elementor (Security Fork)
 * Plugin URI:  https://github.com/Sudo-WP/sudowp-dropzone-elementor
 * Description: A secure, community-maintained fork of "Startklar Elementor Addons". Patches critical Directory Traversal (CVE-2024-5153), Arbitrary File Upload, CSRF, SSRF, and XSS vulnerabilities.
 * Version:     1.7.18
 * Author:      SudoWP (Maintained by WP Republic)
 * Author URI:  https://sudowp.com
 * Text Domain: sudowp-dropzone-elementor
 * License:     GPLv2 or later
 * * Original Plugin: Startklar Elementor Addons
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

namespace StartklarElmentorFormsExtWidgets;

use StartklarElmentorFormsExtWidgets\StartklarCountruySelectorFormField;

add_filter('plugin_row_meta', function ($plugin_meta, $plugin_file) {
    // UPDATED: Check for the new filename
    if (strpos($plugin_file, 'sudowp-dropzone-elementor.php') !== false) {
        $new_links = array(
            // UPDATED: New GitHub URL format
            '<a href="https://github.com/Sudo-WP/sudowp-dropzone-elementor/issues" target="_blank">Report Issue</a>',
            '<a href="https://sudowp.com" target="_blank">SudoWP Project</a>',
        );
        $plugin_meta = array_merge($plugin_meta, $new_links);
    }
    return $plugin_meta;
}, 10, 2);

define('STARTKLAR_ELEMENTOR_FORMS_EXTWIDGETS_PATH', plugin_dir_path(__FILE__));
define('STARTKLAR_ELEMENTOR_FORMS_EXTWIDGETS_URL', plugin_dir_url(__FILE__));

class StartklarElmentorFormsExtWidgets
{
    private $attachments_array = [];

    public function __construct()
    {
        add_action('elementor_pro/forms/process', [$this, 'init_form_email_attachments'], 11, 2);
    }

    /**
     * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
     * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
     */

    public function init_form_email_attachments($record, $ajax_handler)
    {
        $fields = $record->get_field("");
        $home_url = get_option('home');
        $home_path = get_home_path();
        foreach ($fields as $field) {
            if ($field["type"] == "drop_zone_form_field") {
                $t_array = explode(", ", $field["value"]);
                foreach ($t_array as $item) {
                    $item = str_replace($home_url, $home_path, $item);
                    $item = str_replace("//", "/", $item);
                    $this->attachments_array[] = $item;
                }
            }
        }
        if (0 < count($this->attachments_array)) {
            add_filter('wp_mail', [$this, 'wp_mail']);
            add_action('elementor_pro/forms/new_record', [$this, 'remove_wp_mail_filter'], 5);
        }
    }

    public function remove_wp_mail_filter()
    {
        remove_filter('wp_mail', [$this, 'wp_mail']);
    }

    public function wp_mail($args)
    {
        $args['attachments'] = $this->attachments_array;
        return $args;
    }
}

new StartklarElmentorFormsExtWidgets();

add_action('plugins_loaded', function () {
    require_once('widgets/country_code_form_field.php');
    require_once('widgets/dropzone_form_field.php');
    require_once('widgets/honeypot_handler.php');
    require_once('startklarDropZoneUploadProcess.php');
    require_once('startklarCountrySelectorProcess.php');

    $startklar_countruy_selector_form_field = new StartklarCountruySelectorFormField();
    $startklar_dropzone_form_field = new StartklarDropzoneFormField();
    new \StartklarElmentorFormsExtWidgets\HoneypotHandler();

    // Register Ajax action for Dropzone upload
    add_action('wp_ajax_startklar_drop_zone_upload_process', ['StartklarElmentorFormsExtWidgets\startklarDropZoneUploadProcess', 'process']);
    // Removed wp_ajax_nopriv to require authentication for file uploads

    // Register Ajax action for Country Selector (SECURITY FIX: Added missing registration)
    add_action('wp_ajax_startklar_country_selector_process', ['StartklarElmentorFormsExtWidgets\startklarCountrySelectorProcess', 'process']);
    add_action('wp_ajax_nopriv_startklar_country_selector_process', ['StartklarElmentorFormsExtWidgets\startklarCountrySelectorProcess', 'process']);

    // Admin menu settings
    if (is_admin()) {
        add_action('admin_menu', function () {
            // UPDATED: Menu Slug
            add_options_page('SudoWP DropZone', 'SudoWP DropZone', 'manage_options', 'sudowp-dropzone-elementor', function () {
                // Simple settings page check
                if (!current_user_can('manage_options')) {
                    return;
                }
                
                // Save settings with nonce verification (SECURITY FIX)
                if (isset($_POST['startklar_options_submit'])) {
                    // Verify nonce for CSRF protection
                    if (!isset($_POST['sudowp_settings_nonce']) || !wp_verify_nonce($_POST['sudowp_settings_nonce'], 'sudowp_dropzone_settings')) {
                        wp_die(__('Security check failed.', 'sudowp-dropzone-elementor'));
                    }
                    
                    $options = [
                        'blocking_php_file_upload' => isset($_POST['blocking_php_file_upload']) ? 'true' : 'false'
                    ];
                    update_option('startklar_options', $options);
                    echo '<div class="updated"><p>' . esc_html__('Settings saved.', 'sudowp-dropzone-elementor') . '</p></div>';
                }

                $options = get_option('startklar_options');
                $blocking_php = isset($options['blocking_php_file_upload']) && $options['blocking_php_file_upload'] == 'true' ? 'checked' : '';
                
                ?>
                <div class="wrap">
                    <h1><?php echo esc_html__('SudoWP DropZone for Elementor (Security Fork)', 'sudowp-dropzone-elementor'); ?></h1>
                    <p><?php echo wp_kses_post(__('This is a maintained fork by SudoWP. <a href="https://sudowp.com" target="_blank">Visit Project Page</a>', 'sudowp-dropzone-elementor')); ?></p>
                    <form method="post" action="">
                        <?php wp_nonce_field('sudowp_dropzone_settings', 'sudowp_settings_nonce'); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row"><?php echo esc_html__('Security Hardening', 'sudowp-dropzone-elementor'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="blocking_php_file_upload" value="true" <?php echo $blocking_php; ?> disabled checked />
                                        <?php echo esc_html__('Block PHP File Uploads (Enforced by SudoWP Security Patch)', 'sudowp-dropzone-elementor'); ?>
                                    </label>
                                    <p class="description"><?php echo esc_html__('This setting is now permanently enabled and enforced at the code level for your security.', 'sudowp-dropzone-elementor'); ?></p>
                                </td>
                            </tr>
                        </table>
                        <?php submit_button(esc_html__('Save Settings', 'sudowp-dropzone-elementor'), 'primary', 'startklar_options_submit'); ?>
                    </form>
                </div>
                <?php
            });
        });
    }
});