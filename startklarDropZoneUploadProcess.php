<?php
if (!defined('ABSPATH')) { exit; }

namespace StartklarElmentorFormsExtWidgets;

class startklarDropZoneUploadProcess
{
    static function process()
    {
        // SECURITY: Verify nonce for CSRF protection
        check_ajax_referer('startklar_dropzone_upload', 'nonce');

        $uploads_dir_info = wp_upload_dir();
        $user = wp_get_current_user();
        $user_id = (!isset($user) || !is_object($user) || !is_a($user, 'WP_User')) ? 0 : $user->ID;

        if (!isset($_FILES["file"]) && !isset($_POST["mode"])) {
            die(__("There is no file to upload.", "sudowp-dropzone-elementor"));
        }

        // --- SUDOWP PATCH START ---
        // Enhanced File Validation to block PHP execution completely
        if (isset($_FILES['file'])) {
             $file_info = wp_check_filetype_and_ext( $_FILES['file']['tmp_name'], $_FILES['file']['name'] );
             $ext = strtolower($file_info['ext']);
             $type = strtolower($file_info['type']);
             // SECURITY: Sanitize filename before use
             $filename = sanitize_file_name($_FILES['file']['name']);
             
             // STRICT Allow List: Only allow these safe extensions.
             $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'zip'];

             if ( empty($ext) || !in_array($ext, $allowed_extensions) ) {
                 die(__("Security Violation: This file type is not allowed.", "sudowp-dropzone-elementor"));
             }
             
             // Double check MIME type for PHP
             if ( strpos($type, 'php') !== false || strpos($type, 'application/x-httpd-php') !== false ) {
                 die(__("Security Violation: PHP detected.", "sudowp-dropzone-elementor"));
             }
        }
        // --- SUDOWP PATCH END ---

        foreach ($_POST as $key => $value) {
            if (strpos($key, 'hash') !== false) {
                // SUDOWP PATCH: Sanitize hash to prevent traversal
                $hash = sanitize_key($value);

                if (empty($hash)) {
                    die(__("No HASH code match.", "sudowp-dropzone-elementor"));
                }

                if (isset($_POST["mode"]) && $_POST["mode"] == "remove" && isset($_POST["fileName"])) {
                    $fileName = sanitize_file_name($_POST["fileName"]);
                    $newFilepath = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/" . $hash . "/" . $fileName;
                    
                    // SECURITY: Validate the path to prevent directory traversal on delete
                    $expected_base = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/";
                    $real_path = realpath(dirname($newFilepath));
                    if ($real_path === false || strpos($real_path, realpath($expected_base)) !== 0) {
                        die(__("Invalid file path.", "sudowp-dropzone-elementor"));
                    }

                    if (file_exists($newFilepath)) {
                        unlink($newFilepath);
                    }

                    die();
                }

                $filepath = $_FILES['file']['tmp_name'];
                $fileSize = filesize($filepath);

                if ($fileSize === 0) {
                    die(__("The file is empty.", "sudowp-dropzone-elementor"));
                }
                
                // SECURITY: Validate file size against WordPress max upload size
                $max_size = wp_max_upload_size();
                if ($fileSize > $max_size) {
                    die(__("File size exceeds the maximum upload limit.", "sudowp-dropzone-elementor"));
                }

                $newFilepath = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/" . $hash . "/" . sanitize_file_name($_FILES['file']['name']);
                $target_dir = dirname($newFilepath);
                
                // SECURITY: Validate the target directory to prevent directory traversal
                $expected_base = $uploads_dir_info['basedir'] . "/elementor/forms/";
                if (strpos($target_dir, $expected_base) !== 0) {
                    die(__("Invalid upload path.", "sudowp-dropzone-elementor"));
                }

                if (!file_exists($target_dir)) {
                    // SUDOWP PATCH: Changed 0777 to 0755 for better security
                    mkdir($target_dir, 0755, true);
                }

                if (!move_uploaded_file($filepath, $newFilepath)) {
                    die(__("Can't move file.", "sudowp-dropzone-elementor"));
                }
            }
        }
        die();
    }
}