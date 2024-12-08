<?php

/**
 * Plugin configuration file for the Book Information plugin.
 * 
 * This configuration file returns an array containing various settings
 * that control the behavior of the plugin.
 */

return [

    // Custom Post Type configuration
    'post_type' => [
        'slug'          => 'book',
        'labels'        => [
            'name'               => __('Books', 'book-information'),
            'singular_name'      => __('Book', 'book-information'),
            'menu_name'          => __('Books', 'book-information'),
            'name_admin_bar'     => __('Book', 'book-information'),
            'add_new'            => __('Add New', 'book-information'),
            'add_new_item'       => __('Add New Book', 'book-information'),
            'new_item'           => __('New Book', 'book-information'),
            'edit_item'          => __('Edit Book', 'book-information'),
            'view_item'          => __('View Book', 'book-information'),
            'all_items'          => __('All Books', 'book-information'),
            'search_items'       => __('Search Books', 'book-information'),
            'parent_item_colon'  => __('Parent Books:', 'book-information'),
            'not_found'          => __('No books found.', 'book-information'),
            'not_found_in_trash' => __('No books found in Trash.', 'book-information'),
        ],
        'args'          => [
            'public'             => true,
            'has_archive'        => true,
            'supports'           => ['title', 'editor', 'thumbnail'],
            'rewrite'            => ['slug' => 'books'],
            'show_in_rest'       => true,
            'taxonomies'         => ['publisher', 'author'],
        ],
    ],

    // Taxonomies configuration
    'taxonomies' => [
        'publisher' => [
            'hierarchical'      => true,
            'labels'            => [
                'name'              => __('Publishers', 'book-information'),
                'singular_name'     => __('Publisher', 'book-information'),
                'search_items'      => __('Search Publishers', 'book-information'),
                'all_items'         => __('All Publishers', 'book-information'),
                'parent_item'       => __('Parent Publisher', 'book-information'),
                'parent_item_colon' => __('Parent Publisher:', 'book-information'),
                'edit_item'         => __('Edit Publisher', 'book-information'),
                'update_item'       => __('Update Publisher', 'book-information'),
                'add_new_item'      => __('Add New Publisher', 'book-information'),
                'new_item_name'     => __('New Publisher Name', 'book-information'),
                'menu_name'         => __('Publisher', 'book-information'),
            ],
            'args'              => [
                'show_ui'           => true,
                'show_in_rest'      => true,
                'rewrite'           => ['slug' => 'publisher'],
            ],
        ],
        'book_author' => [
            'hierarchical'      => false,
            'labels'            => [
                'name'              => __('Authors', 'book-information'),
                'singular_name'     => __('Author', 'book-information'),
                'search_items'      => __('Search Authors', 'book-information'),
                'all_items'         => __('All Authors', 'book-information'),
                'parent_item'       => __('Parent Author', 'book-information'),
                'parent_item_colon' => __('Parent Author:', 'book-information'),
                'edit_item'         => __('Edit Author', 'book-information'),
                'update_item'       => __('Update Author', 'book-information'),
                'add_new_item'      => __('Add New Author', 'book-information'),
                'new_item_name'     => __('New Author Name', 'book-information'),
                'menu_name'         => __('Author', 'book-information'),
            ],
            'args'              => [
                'show_ui'           => true,
                'show_in_rest'      => true,
                'rewrite'           => ['slug' => 'book_author'],
            ],
        ],
    ],

    // Meta box configuration for ISBN
    'isbn_meta_box' => [
        'meta_key'      => '_book_isbn',
        'nonce_action'  => 'save_isbn_meta',
        'nonce_name'    => 'book_isbn_nonce',
        'box_id'        => 'book_isbn',
        'title'         => __('ISBN Number', 'book-information'),
        'context'       => 'side',
        'priority'      => 'default',
    ],

    // Admin menu configuration
    'admin_menu' => [
        'page_title'    => __('Books Info', 'book-information'),
        'menu_title'    => __('Books Info', 'book-information'),
        'capability'    => 'manage_options',
        'menu_slug'     => 'books_info',
        'icon_url'      => 'dashicons-book',
        'position'      => 6,
    ],

    // List table configuration
    'list_table' => [
        'per_page'      => 20,
    ],

    // General plugin options
    'options' => [
        'enable_logging'         => false, // Set to true to enable logging
        'default_publisher'      => '',    // Set a default publisher if needed
        'default_author'         => '',    // Set a default author if needed
    ],

];