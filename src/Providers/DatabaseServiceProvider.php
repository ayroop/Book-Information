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

    public function __construct(ConfigValues $config)
    {
        $this->config = $config;
    }

    public function register()
    {
        $container = $this->getContainer();

        // Access database configurations
        $dbConfig = $this->config->get('database.connections.mysql', []);

        if (empty($dbConfig)) {
            throw new Exception('Database configuration is missing.');
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

    public function bootPlugin()
    {
        // No additional booting required here
    }
}