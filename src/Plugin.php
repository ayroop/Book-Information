<?php

namespace BookInformation;

use Rabbit\Plugin as RabbitPlugin;
use Configula\ConfigValues;
use BookInformation\Providers\DatabaseServiceProvider;
use BookInformation\Providers\PostTypeServiceProvider;
use BookInformation\Providers\AdminPageServiceProvider;
use Rabbit\Redirects\AdminNotice;
use Exception;

class Plugin
{
    /**
     * @var RabbitPlugin
     */
    protected $plugin;

    /**
     * @var ConfigValues
     */
    protected $config;

    /**
     * Plugin constructor.
     *
     * @param RabbitPlugin   $plugin The Rabbit plugin instance.
     * @param ConfigValues   $config The configuration values.
     */
    public function __construct(RabbitPlugin $plugin, ConfigValues $config)
    {
        $this->plugin = $plugin;
        $this->config = $config;

        // Initialize the plugin.
        $this->init();
    }

    /**
     * Initialize the plugin functionality.
     */
    protected function init()
    {
        try {
            // Load configurations as needed.
            $this->loadConfigurations();

            // Register service providers.
            $this->registerServiceProviders();

            // Perform additional initialization tasks.
            $this->setupHooks();

        } catch (Exception $e) {
            // Handle exceptions by displaying an admin notice.
            add_action('admin_notices', function () use ($e) {
                AdminNotice::permanent(['type' => 'error', 'message' => $e->getMessage()]);
            });

            // Optionally log the error if logging is enabled.
            if ($this->plugin->has('logger')) {
                $logger = $this->plugin->get('logger');
                $logger->error($e->getMessage());
            }
        }
    }

    /**
     * Load additional configurations or perform configuration-related tasks.
     */
    protected function loadConfigurations()
    {
        // Example of accessing a configuration value.
        $optionOne = $this->config->get('options.option_one', 'default_value');

        // Use configuration values as needed.
        // ... (additional configuration handling if necessary)
    }

    /**
     * Register additional service providers if necessary.
     */
    protected function registerServiceProviders()
    {
        // Service providers are registered in the main plugin file (book-information.php).
        // Register service providers here
        // Example:
        // $this->plugin->addServiceProvider(new CustomServiceProvider($this->config));
    }

    /**
     * Set up WordPress hooks and filters.
     */
    protected function setupHooks()
    {
        // Add actions, filters, and other hooks.
        add_action('init', [$this, 'onInit']);
    }

    /**
     * Hook into WordPress 'init' action.
     */
    public function onInit()
    {
        // Perform actions during the 'init' phase.
        // This can include registering shortcodes, Gutenberg blocks, etc.
    }

    /**
     * Get the plugin instance.
     *
     * @return RabbitPlugin
     */
    public function getPlugin()
    {
        return $this->plugin;
    }

    /**
     * Get the configuration values.
     *
     * @return ConfigValues
     */
    public function getConfig()
    {
        return $this->config;
    }
}