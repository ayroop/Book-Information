<?php
/**
 * Plugin Name:     Book Information
 * Plugin URI:      https://ayrop.com/book-information
 * Plugin Prefix:   BI
 * Description:     A WordPress plugin to manage book information using the Rabbit Framework.
 * Version:         1.0.0
 * Author:          Pooriya
 * Author URI:      https://ayrop.com
 * Text Domain:     book-information
 * Domain Path:     /languages
 */

namespace BookInformation;

use Rabbit\Application;
use Rabbit\Plugin;
use Exception;
use Rabbit\Utils\Singleton;
use Configula\ConfigFactory;
use Configula\ConfigValues;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Load Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

class BookInformationPlugin extends Singleton
{
    /**
     * @var Plugin
     */
    private $application;

    /**
     * @var ConfigValues
     */
    private $config;

    public function __construct()
    {
        // Load configurations
        $this->loadConfigurations();

        // Initialize the application
        $this->application = Application::get()->loadPlugin(__DIR__, __FILE__, 'config');

        // Register service providers
        $this->registerServiceProviders();

        // Register activation and deactivation hooks
        $this->registerActivationHooks();

        // Initialize the plugin
        $this->init();
    }

    /**
     * Load configurations using Configula.
     */
    private function loadConfigurations()
    {
        // Use Configula to load configurations from the 'config' directory
        $this->config = ConfigFactory::loadPath(__DIR__ . '/config');
    }

    /**
     * Register activation and deactivation hooks.
     */
    // book-information.php

    private function registerActivationHooks()
    {
        // Activation hook
        $this->application->onActivation(function () {
            /** @var \Illuminate\Database\Capsule\Manager $capsule */
            $capsule = $this->application->get('db');
            $schema = $capsule::schema();

            if (!$schema->hasTable('books_info')) {
                $schema->create('books_info', function ($table) {
                    $table->increments('ID');
                    $table->unsignedBigInteger('post_id');
                    $table->string('isbn');
                    // ... other table configurations ...
                });
            }

            // Register post types and taxonomies
            $postTypeProvider = new \BookInformation\Providers\PostTypeServiceProvider($this->config, $this->application);
            $postTypeProvider->registerPostType();
            $postTypeProvider->registerTaxonomies();

            // Flush rewrite rules
            flush_rewrite_rules();
        });

        // Deactivation hook (optional)
        $this->application->onDeactivation(function () {
            // We may also flush rewrite rules upon deactivation if needed
            flush_rewrite_rules();
        });
    }

    /**
     * Initialize the plugin.
     */
    public function init()
    {
        try {
            // Boot the plugin
            $this->application->boot(function (Plugin $plugin) {
                // Load the plugin text domain for translations
                $plugin->loadPluginTextDomain();

                // Initialize the main plugin functionality
                new \BookInformation\Plugin($plugin, $this->config);
            });

        } catch (Exception $e) {
            // Handle exceptions by displaying an admin notice
            add_action('admin_notices', function () use ($e) {
                \Rabbit\Redirects\AdminNotice::permanent(['type' => 'error', 'message' => $e->getMessage()]);
            });

            // Optionally log the error if the logger is available
            add_action('init', function () use ($e) {
                if ($this->application->has('logger')) {
                    $this->application->get('logger')->error($e->getMessage());
                }
            });
        }
    }

    /**
     * Register all necessary service providers.
     */
    private function registerServiceProviders()
    {
        // Load Rabbit Framework service providers
        $this->application->addServiceProvider(new \Rabbit\Redirects\RedirectServiceProvider());
        $this->application->addServiceProvider(new \Rabbit\Templates\TemplatesServiceProvider());
        // Add a custom logger service provider
        $this->application->addServiceProvider(new \BookInformation\Providers\CustomLoggerServiceProvider($this->application));

        // Load plugin-specific service providers, passing the configuration and plugin instance where needed
        $this->application->addServiceProvider(new \BookInformation\Providers\DatabaseServiceProvider($this->config));
        $this->application->addServiceProvider(new \BookInformation\Providers\PostTypeServiceProvider($this->config, $this->application));
        $this->application->addServiceProvider(new \BookInformation\Providers\AdminPageServiceProvider($this->config, $this->application));
    }

    /**
     * Get the application instance.
     *
     * @return Plugin
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Get the loaded configuration.
     *
     * @return ConfigValues
     */
    public function getConfig()
    {
        return $this->config;
    }
}

/**
 * Returns the main instance of BookInformationPlugin.
 *
 * @return BookInformationPlugin
 */
function BookInformationPlugin()
{
    return BookInformationPlugin::get();
}

// Initialize the plugin
BookInformationPlugin();