=== SudoWP DropZone for Elementor (Security Fork) ===
Contributors: SudoWP, WP Republic
Original Authors: WEB-SHOP-HOSTING
Tags: elementor, dropzone, upload, security-patch, fork, cve-2024-5153, honeypot
Requires at least: 5.6
Tested up to: 6.7
Stable tag: 1.7.16
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A secure, community-maintained fork of "Startklar Elementor Addons". Patches critical Directory Traversal and File Upload vulnerabilities.

== Description ==

This is **SudoWP DropZone**, a community-maintained and security-hardened fork of the abandoned "Startklar Elementor Addons" plugin (v1.7.15).

It restores functionality to the Elementor Pro Form widget while patching critical vulnerabilities found in the original version, specifically **CVE-2024-5153** (Directory Traversal) and **Arbitrary File Upload** flaws that could lead to Remote Code Execution (RCE).

**DISCLAIMER:** This plugin is NOT affiliated with, endorsed by, or associated with WEB-SHOP-HOSTING or the original "Startklar" developers. It is an independent fork maintained by the SudoWP security project.

**Key Features Preserved:**
* **DropZone Field:** Drag & Drop multiple file upload support for Elementor Forms.
* **Country Code Selector:** Automatic IP-based geolocation and country flag display for phone fields.
* **Advanced Honeypot:** Enhanced spam protection hidden from legitimate users but visible to bots.

**Security Improvements in SudoWP Edition:**
* **Patched CVE-2024-5153:** Strict sanitization of the `dropzone_hash` parameter to prevent directory traversal attacks.
* **Blocked PHP Execution:** Enforced server-side checks to strictly forbid `.php`, `.phtml`, `.exe`, and other executable file types from being uploaded, regardless of WordPress settings.
* **Hardened Permissions:** Temporary upload directories are now created with restricted permissions (0755 instead of 0777).

== Installation ==

1. Upload the `sudowp-dropzone-elementor` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Ensure **Elementor** and **Elementor Pro** are installed and active.
4. The new fields (DropZone, Country Code) will appear in your Elementor Form widget settings.

== Frequently Asked Questions ==

= Is this compatible with the original Startklar plugin? =
No. This is a standalone fork. You must deactivate and delete the original "Startklar Elementor Addons" to use this version, as they share similar class names but different file structures.

= Why use this fork instead of the original? =
The original plugin contains unpatched security vulnerabilities that allow attackers to manipulate files on your server. This fork fixes those issues and blocks executable file uploads.

== Changelog ==

= 1.7.16 (SudoWP Edition) =
* **Security Fix:** Patched Critical Directory Traversal vulnerability (CVE-2024-5153) in `dropzone_form_field.php`.
* **Security Fix:** Patched Arbitrary File Upload vulnerability. Added strict deny-list for PHP/Executable extensions.
* **Hardening:** Changed temporary folder permissions from 0777 to 0755.
* **Maintenance:** Refactored codebase to use `sudowp-` naming convention to prevent conflicts.
* **Maintenance:** Updated author URI and branding to reflect SudoWP maintenance.
