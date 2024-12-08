# Book Information Wordpress Plugin

**Contributors:** Pooriya  
**Requires at least:** 5.8  
**Tested up to:** 6.0  
**Stable tag:** 1.0.0  
**Requires PHP:** 7.2  
**License:** GPL-2.0-or-later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Manage book information using custom post types and taxonomies.

## Description

This plugin allows you to manage book information within your WordPress site. It creates a custom post type for books, adds custom taxonomies for authors and publishers, and stores ISBN numbers in a custom database table.

## Features

- Custom Post Type: Book
- Custom Taxonomies: Authors and Publishers
- ISBN Meta Box: Enter and save ISBN numbers
- Admin Page: View all books with ISBNs using `WP_List_Table`
- Internationalization: Ready for translation

## Installation

### Prerequisites

- **Composer:** Make sure you have [Composer](https://getcomposer.org/) installed on your system.
- **PHP Version:** Your server should be running PHP 8.0 or higher.

### Steps

1. **Download the Plugin**

   - Clone or download the `book-information` plugin to your WordPress `wp-content/plugins/` directory.
     - **Via Git:**  
       ```bash
       cd /path/to/wordpress/wp-content/plugins/
       git clone https://github.com/ayroop/book-information.git
       ```
     - **Manual Download:**  
       Download the ZIP file from the repository and extract it into the `wp-content/plugins/` directory.

2. **Install Dependencies**

   - Navigate to the plugin directory:
     ```bash
     cd /path/to/wordpress/wp-content/plugins/book-information-management
     ```
   - Run Composer to install the required dependencies:
     ```bash
     composer install
     ```
     This will install the Rabbit Framework and any other dependencies specified in the `composer.json` file.

3. **Activate the Plugin**

   - Log in to your WordPress admin dashboard.
   - Navigate to **Plugins** → **Installed Plugins**.
   - Locate **Book Information Management** and click **Activate**.

4. **Permalink Settings** (Optional but Recommended)

   - To ensure custom post types and taxonomies work correctly, go to **Settings** → **Permalinks**.
   - Click **Save Changes** to flush rewrite rules.

## Frequently Asked Questions

**Q:** How do I add a new book?  
**A:** Go to **Books** → **Add New** in the WordPress admin area.

**Q:** I get an error about missing dependencies. What should I do?  
**A:** Make sure you've run `composer install` in the plugin directory to install all required dependencies.

## Changelog

### 1.0.0

- Initial release.

## Upgrade Notice

N/A

## Development

This plugin uses the [Rabbit Framework](https://github.com/veronalabs/rabbit) for a modular and maintainable codebase.

## License

This plugin is licensed under the GPL v2 or later.
