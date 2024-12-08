<?php

namespace BookInformation\Providers;

use Psr\Log\NullLogger;
use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use LoggerWp\Logger;

class CustomLoggerServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    protected $provides = [
        'logger',
    ];

    /**
     * @var \Rabbit\Plugin
     */
    protected $plugin;

    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    public function register()
    {
        $container = $this->getContainer();

        // Retrieve configuration values
        $logsPath = $this->plugin->config('logs_path');
        $logsDays = $this->plugin->config('logs_days');
        $enableLogging = $this->plugin->config('options.enable_logging', false);

        // Always register the 'logger' service
        if ($enableLogging && $logsPath && $logsDays) {
            $container->add('logger', Logger::class)
                ->addArgument([
                    'dir_name'  => $logsPath,
                    'channel'   => defined('WP_DEBUG') && WP_DEBUG ? 'development' : 'production',
                    'logs_days' => $logsDays,
                ]);
        } else {
            // Register a NullLogger if logging is disabled or configurations are missing
            $container->add('logger', NullLogger::class);
        }
    }

    public function bootPlugin()
    {
        $instance = $this;

        $this->getContainer()::macro('logger', function () use ($instance) {
            return $instance->getContainer()->get('logger');
        });

        add_action('init', function () use ($instance) {
            $instance->getContainer()->get('logger');
        });
    }
}