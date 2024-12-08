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
            'name'                  => __( 'Books', 'book-information' ),
            'singular_name'         => __( 'Book', 'book-information' ),
            'menu_name'             => __( 'Books', 'book-information' ),
            'name_admin_bar'        => __( 'Book', 'book-information' ),
            'add_new'               => __( 'Add New', 'book-information' ),
            'add_new_item'          => __( 'Add New Book', 'book-information' ),
            'new_item'              => __( 'New Book', 'book-information' ),
            'edit_item'             => __( 'Edit Book', 'book-information' ),
            'view_item'             => __( 'View Book', 'book-information' ),
            'all_items'             => __( 'All Books', 'book-information' ),
            'search_items'          => __( 'Search Books', 'book-information' ),
            'parent_item'           => __( 'Parent Book', 'book-information' ),
            'parent_item_colon'     => __( 'Parent Book:', 'book-information' ),
            'not_found'             => __( 'No books found.', 'book-information' ),
            'not_found_in_trash'    => __( 'No books found in Trash.', 'book-information' ),
            'featured_image'        => __( 'Book Cover Image', 'book-information' ),
            'set_featured_image'    => __( 'Set cover image', 'book-information' ),
            'remove_featured_image' => __( 'Remove cover image', 'book-information' ),
            'use_featured_image'    => __( 'Use as cover image', 'book-information' ),
            'archives'              => __( 'Book Archives', 'book-information' ),
            'insert_into_item'      => __( 'Insert into book', 'book-information' ),
            'uploaded_to_this_item' => __( 'Uploaded to this book', 'book-information' ),
            'filter_items_list'     => __( 'Filter books list', 'book-information' ),
            'items_list_navigation' => __( 'Books list navigation', 'book-information' ),
            'items_list'            => __( 'Books list', 'book-information' ),
        ],
        'args'          => [
            'label'                 => __( 'Books', 'book-information' ),
            'public'                => true,
            'has_archive'           => true,
            'supports'              => [ 'title', 'editor', 'thumbnail' ],
            'rewrite'               => [ 'slug' => 'books' ],
            'show_in_rest'          => true,
            'taxonomies'            => [ 'publisher', 'book_author' ],
            'menu_icon'             => 'dashicons-book',
        ],
    ],

    // Taxonomies configuration
    'taxonomies' => [
        'publisher' => [
            'hierarchical'      => true,
            'labels'            => [
                'name'                       => __( 'Publishers', 'book-information' ),
                'singular_name'              => __( 'Publisher', 'book-information' ),
                'search_items'               => __( 'Search Publishers', 'book-information' ),
                'popular_items'              => __( 'Popular Publishers', 'book-information' ),
                'all_items'                  => __( 'All Publishers', 'book-information' ),
                'parent_item'                => __( 'Parent Publisher', 'book-information' ),
                'parent_item_colon'          => __( 'Parent Publisher:', 'book-information' ),
                'edit_item'                  => __( 'Edit Publisher', 'book-information' ),
                'view_item'                  => __( 'View Publisher', 'book-information' ),
                'update_item'                => __( 'Update Publisher', 'book-information' ),
                'add_new_item'               => __( 'Add New Publisher', 'book-information' ),
                'new_item_name'              => __( 'New Publisher Name', 'book-information' ),
                'separate_items_with_commas' => __( 'Separate publishers with commas', 'book-information' ),
                'add_or_remove_items'        => __( 'Add or remove publishers', 'book-information' ),
                'choose_from_most_used'      => __( 'Choose from the most used publishers', 'book-information' ),
                'not_found'                  => __( 'No publishers found.', 'book-information' ),
                'no_terms'                   => __( 'No publishers', 'book-information' ),
                'menu_name'                  => __( 'Publishers', 'book-information' ),
                'items_list_navigation'      => __( 'Publishers list navigation', 'book-information' ),
                'items_list'                 => __( 'Publishers list', 'book-information' ),
            ],
            'args'              => [
                'show_ui'           => true,
                'show_in_rest'      => true,
                'rewrite'           => [ 'slug' => 'publisher' ],
                'public'            => true,
            ],
        ],
        'book_author' => [
            'hierarchical'      => false,
            'labels'            => [
                'name'                       => __( 'Authors', 'book-information' ),
                'singular_name'              => __( 'Author', 'book-information' ),
                'search_items'               => __( 'Search Authors', 'book-information' ),
                'popular_items'              => __( 'Popular Authors', 'book-information' ),
                'all_items'                  => __( 'All Authors', 'book-information' ),
                'parent_item'                => __( 'Parent Author', 'book-information' ),
                'parent_item_colon'          => __( 'Parent Author:', 'book-information' ),
                'edit_item'                  => __( 'Edit Author', 'book-information' ),
                'view_item'                  => __( 'View Author', 'book-information' ),
                'update_item'                => __( 'Update Author', 'book-information' ),
                'add_new_item'               => __( 'Add New Author', 'book-information' ),
                'new_item_name'              => __( 'New Author Name', 'book-information' ),
                'separate_items_with_commas' => __( 'Separate authors with commas', 'book-information' ),
                'add_or_remove_items'        => __( 'Add or remove authors', 'book-information' ),
                'choose_from_most_used'      => __( 'Choose from the most used authors', 'book-information' ),
                'not_found'                  => __( 'No authors found.', 'book-information' ),
                'no_terms'                   => __( 'No authors', 'book-information' ),
                'menu_name'                  => __( 'Authors', 'book-information' ),
                'items_list_navigation'      => __( 'Authors list navigation', 'book-information' ),
                'items_list'                 => __( 'Authors list', 'book-information' ),
            ],
            'args'              => [
                'show_ui'           => true,
                'show_in_rest'      => true,
                'rewrite'           => [ 'slug' => 'book_author' ],
                'public'            => true,
            ],
        ],
    ],

    // Meta box configuration for ISBN
    'isbn_meta_box' => [
        'meta_key'      => '_book_isbn',
        'nonce_action'  => 'save_isbn_meta',
        'nonce_name'    => 'book_isbn_nonce',
        'box_id'        => 'book_isbn',
        'title'         => __( 'ISBN Number', 'book-information' ),
        'context'       => 'side',
        'priority'      => 'default',
    ],

    // Admin menu configuration
    'admin_menu' => [
        'page_title'    => __( 'Books Info', 'book-information' ),
        'menu_title'    => __( 'Books Info', 'book-information' ),
        'capability'    => 'manage_options',
        'menu_slug'     => 'books_info',
        'icon_url'      => 'dashicons-book',
        'position'      => 6,
    ],

    // List table configuration
    'list_table' => [
        'per_page'      => 20,
        'columns'       => [
            'cb'            => '<input type="checkbox" />',
            'title'         => __( 'Title', 'book-information' ),
            'isbn'          => __( 'ISBN', 'book-information' ),
            'author'        => __( 'Author', 'book-information' ),
            'publisher'     => __( 'Publisher', 'book-information' ),
            'date'          => __( 'Date', 'book-information' ),
        ],
    ],

    // General plugin options
    'options' => [
        'enable_logging'         => false, // Set to true to enable logging
        'default_publisher'      => '',    // Set a default publisher if needed
        'default_author'         => '',    // Set a default author if needed
    ],

];