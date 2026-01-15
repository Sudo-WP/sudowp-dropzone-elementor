# SudoWP DropZone for Elementor

**Contributors:** SudoWP, WP Republic  
**Original Authors:** WEB-SHOP-HOSTING  
**Tags:** elementor, dropzone, upload, security-patch, fork, cve-2024-5153, honeypot  
**Requires at least:** 5.6  
**Tested up to:** 6.7  
**Stable tag:** 1.7.16  
**License:** GPLv2 or later  

## Security Notice
This is a **community-maintained fork** of the abandoned "Startklar Elementor Addons" plugin (v1.7.15). The original plugin is deprecated and contains unpatched security vulnerabilities.

**This version patches CVE-2024-5153 (Directory Traversal) and Arbitrary File Upload vulnerabilities** by implementing strict sanitization, enforcing file type allow-lists, and hardening server-side permissions.

---

## Description

**SudoWP DropZone for Elementor** restores the "DropZone" and "Country Code" functionality to Elementor Pro Forms, but with enterprise-grade security patches applied. It is designed for users who need these specific widgets but cannot risk the security flaws present in the original abandoned plugin.

**Key Features:**
* **Security Patched:** Fixes critical Directory Traversal and Remote Code Execution (RCE) flaws found in the original vendor version.
* **DropZone Field:** Drag & Drop multiple file upload support for Elementor Forms.
* **Country Code Selector:** Automatic IP-based geolocation and country flag display for phone fields.
* **Advanced Honeypot:** Enhanced spam protection hidden from legitimate users but visible to bots.
* **Rebranded:** Uses `sudowp-` naming convention to prevent conflicts or accidental overwrites from the abandoned source.

## Installation

1.  Download the plugin zip file (or clone this repo).
2.  **Important:** Deactivate and delete the original "Startklar Elementor Addons" plugin if installed (to avoid class conflicts).
3.  Upload the `sudowp-dropzone-elementor` folder to your `/wp-content/plugins/` directory.
4.  Activate the plugin through the 'Plugins' menu in WordPress.
5.  Ensure **Elementor** and **Elementor Pro** are installed and active.

## Frequently Asked Questions

**Is this compatible with the original Startklar plugin?** No. This is a standalone fork. You must deactivate and delete the original "Startklar Elementor Addons" to use this version, as they share similar class names but have different file structures and security logic.

**Why use this instead of the official plugin?** The official plugin has been abandoned and contains known security vulnerabilities that allow attackers to upload executable files (PHP) or manipulate server directories. This fork fixes those issues while keeping the functionality intact.

**Does it support PHP file uploads?** No. For security reasons, this fork **strictly enforces** a ban on PHP, EXE, and other executable file extensions, regardless of your Elementor or WordPress settings.

## Changelog

### Version 1.7.16 (SudoWP Edition)
* **Security Fix:** Patched Critical Directory Traversal vulnerability (CVE-2024-5153) in `dropzone_form_field.php`.
* **Security Fix:** Patched Arbitrary File Upload vulnerability. Added strict server-side deny-list for PHP/Executable extensions.
* **Hardening:** Changed temporary upload folder permissions from `0777` (world-writable) to `0755`.
* **Maintenance:** Refactored codebase to use `sudowp-` naming convention.
* **Maintenance:** Updated author URI and branding to reflect SudoWP maintenance.
