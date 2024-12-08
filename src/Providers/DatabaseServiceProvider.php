<?php

namespace BookInformation\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;
use Configula\ConfigValues;
use Exception;
use Rabbit\Contracts\BootablePluginProviderInterface;

class DatabaseServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    protected $config;

    protected $provides = [
        'db',
        'book_model',
    ];

    /**
     * Constructor.
     *
     * @param ConfigValues $config Configuration values.
     */
    public function __construct(ConfigValues $config)
    {
        $this->config = $config;
    }

    /**
     * Register services.
     *
     * @throws Exception If the database configuration is missing.
     */
    public function register()
    {
        $container = $this->getContainer();

        // Access database configurations
        $dbConfig = $this->config->get('database.connections.mysql', []);

        if (empty($dbConfig)) {
            // Throw an exception if the database configuration is missing
            throw new Exception(
                /* translators: Error message when database configuration is missing */
                __('Database configuration is missing.', 'book-information')
            );
        }

        // Create Capsule instance
        $capsule = new Capsule;
        $capsule->addConnection($dbConfig);

        // Make this Capsule instance available globally via static methods
        $capsule->setAsGlobal();

        // Boot Eloquent ORM immediately
        $capsule->bootEloquent();

        // Bind Illuminate Database Capsule to the container
        $container->add('db', $capsule);

        // Bind the Book model to the container
        $container->add('book_model', \BookInformation\Models\Book::class);
    }

    /**
     * Boot the plugin provider.
     */
    public function bootPlugin()
    {
        // No additional booting required here
    }
}