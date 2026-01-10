<?php

namespace StartklarElmentorFormsExtWidgets;

class startklarDropZoneUploadProcess
{
    static function process()
    {
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
             
             // STRICT Deny List: Never allow these extensions, even if WP allows them.
             $forbidden_exts = ['php', 'php5', 'php7', 'phtml', 'phar', 'exe', 'sh', 'pl', 'py'];
             
             if ( in_array($ext, $forbidden_exts) || empty($ext) ) {
                 die(__("Security Violation: This file type is strictly prohibited.", "sudowp-dropzone-elementor"));
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

                $newFilepath = $uploads_dir_info['basedir'] . "/elementor/forms/" . $user_id . "/temp/" . $hash . "/" . sanitize_file_name($_FILES['file']['name']);
                $target_dir = dirname($newFilepath);

                if (!file_exists($target_dir)) {
                    // SUDOWP PATCH: Changed 0777 to 0755 for better security
                    mkdir($target_dir, 0755, true);
                }

                if (!copy($filepath, $newFilepath)) { // Copy the file, returns false if failed
                    die(__("Can't move file.", "sudowp-dropzone-elementor"));
                }
                unlink($filepath); // Delete the temp file
            }
        }
        die();
    }
}