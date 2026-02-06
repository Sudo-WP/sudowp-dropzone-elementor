<?php
namespace StartklarElmentorFormsExtWidgets;
use  TP_MaxMind\Db\Reader;

class startklarCountrySelectorProcess {
    static public function process(){
        // SECURITY: Verify nonce for CSRF protection
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'startklar_country_selector')) {
            wp_send_json_error(['message' => __('Security check failed.', 'sudowp-dropzone-elementor')], 403);
            exit;
        }

        require_once(__DIR__ . "/lib/GeoLocator/src/autoload.php");
        $ret_arr = [];

        $remote_addr = $_SERVER["REMOTE_ADDR"];

        // SECURITY FIX: Remove SSRF vulnerability - don't make external requests
        // For localhost/development environments, use a safe default instead
        if ($remote_addr == "::1" || $remote_addr == "127.0.0.1" || strpos($remote_addr, '192.168.') === 0 || strpos($remote_addr, '10.') === 0) {
            // For local development, return empty result instead of making external request
            echo json_encode([]);
            exit;
        }

        if (!empty($remote_addr) && preg_match("/\d+.\d+.\d+.\d+/ism", $remote_addr, $matches)) {
            $reader = new Reader(__DIR__ . "/lib/GeoLocator/src/GeoLite2-Country/GeoLite2-Country.mmdb");
            $test = $reader->get($remote_addr);
            $country_names_en = $test["country"]["names"]["en"];

            if (!empty($country_names_en)) {
                $ret_arr = ["country" => $test["country"]["names"]["en"]];
            }
        }

        echo json_encode($ret_arr);
        exit;
    }
}