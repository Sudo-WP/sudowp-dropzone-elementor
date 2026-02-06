=== SudoWP DropZone for Elementor (Security Fork) ===
Contributors: SudoWP, WP Republic
Original Authors: WEB-SHOP-HOSTING
Tags: elementor, dropzone, upload, security-patch, fork, cve-2024-5153, honeypot
Requires at least: 5.6
Tested up to: 6.7
Stable tag: 1.7.17
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A secure, community-maintained fork of "Startklar Elementor Addons". Patches critical CVE-2024-5153, CSRF, SSRF, XSS, and File Upload vulnerabilities.

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
* **CSRF Protection:** Implemented nonce verification on all AJAX handlers to prevent cross-site request forgery.
* **Fixed SSRF:** Removed external HTTP requests to prevent server-side request forgery.
* **Blocked PHP Execution:** Enforced server-side checks to strictly forbid `.php`, `.phtml`, `.exe`, and other executable file types including double extensions.
* **XSS Protection:** Proper output escaping throughout the plugin to prevent cross-site scripting.
* **Hardened Permissions:** Temporary upload directories are now created with restricted permissions (0755 instead of 0777).
* **Enhanced Validation:** Added file size limits, path validation, and comprehensive security checks.

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

= 1.7.17 (SudoWP Security Hardened Edition) =
* **Security Enhancement:** Added CSRF protection with nonce verification on all AJAX handlers.
* **Security Enhancement:** Added missing AJAX handler registration for country selector.
* **Security Fix:** Fixed SSRF vulnerability by removing external HTTP requests.
* **Security Enhancement:** Expanded forbidden file extensions (PHP8, JSP, ASP, SVG, SWF, etc.).
* **Security Enhancement:** Added double extension validation to prevent file spoofing.
* **Security Enhancement:** Added file size validation against WordPress limits.
* **Security Enhancement:** Enhanced path validation to prevent directory traversal.
* **Security Enhancement:** Added XSS protection with proper output escaping.
* **Security Enhancement:** Added nonce verification to admin settings page.
* **Documentation:** Added comprehensive security audit report.

= 1.7.16 (SudoWP Edition) =
* **Security Fix:** Patched Critical Directory Traversal vulnerability (CVE-2024-5153) in `dropzone_form_field.php`.
* **Security Fix:** Patched Arbitrary File Upload vulnerability. Added strict deny-list for PHP/Executable extensions.
* **Hardening:** Changed temporary folder permissions from 0777 to 0755.
* **Maintenance:** Refactored codebase to use `sudowp-` naming convention to prevent conflicts.
* **Maintenance:** Updated author URI and branding to reflect SudoWP maintenance.
