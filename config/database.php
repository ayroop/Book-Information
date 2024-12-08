<?php

/**
 * Database configuration file for the Book Information plugin.
 *
 * This configuration file returns an array containing the database settings
 * required to establish a connection using the Illuminate Database component (Eloquent ORM).
 *
 * The configuration values utilize WordPress constants and global variables to ensure
 * compatibility with the WordPress installation.
 */

return [
    'database' => [
        'default' => 'mysql',

        'connections' => [
            'mysql' => [
                'driver'    => 'mysql',
                'host'      => defined( 'DB_HOST' ) ? DB_HOST : 'localhost',
                'database'  => defined( 'DB_NAME' ) ? DB_NAME : 'wordpress',
                'username'  => defined( 'DB_USER' ) ? DB_USER : 'root',
                'password'  => defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '',
                'charset'   => 'utf8', // Changed from 'utf8mb4' to 'utf8'
                'collation' => 'utf8_unicode_ci', // Changed from 'utf8mb4_unicode_ci' to 'utf8_unicode_ci'
                'prefix'    => isset( $GLOBALS['wpdb']->prefix ) ? $GLOBALS['wpdb']->prefix : 'wp_',
                'strict'    => false,
                'engine'    => null,
            ],
        ],

        // Additional Eloquent options (if needed)
        'migrations' => 'migrations',
        'redis' => [
            // Redis configuration (if using Redis)
        ],
    ],
];