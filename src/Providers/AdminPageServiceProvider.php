<?php

namespace BookInformation\Providers;

use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Configula\ConfigValues;

class AdminPageServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    /**
     * @var ConfigValues
     */
    protected $config;

    /**
     * @var \Rabbit\Plugin
     */
    protected $plugin;

    /**
     * Constructor.
     *
     * @param ConfigValues   $config Configuration values.
     * @param \Rabbit\Plugin $plugin The plugin instance.
     */
    public function __construct(ConfigValues $config, $plugin)
    {
        $this->config = $config;
        $this->plugin = $plugin;
    }

    /**
     * Register services.
     */
    public function register()
    {
        // No services to register here
    }

    /**
     * Boot the plugin provider.
     */
    public function bootPlugin()
    {
        add_action('admin_menu', [$this, 'addAdminMenus']);
    }

    /**
     * Add admin menus.
     */
    public function addAdminMenus()
    {
        // Retrieve admin menu settings from configuration
        $menuConfig = $this->config->get('admin_menu', []);

        $page_title = $menuConfig['page_title'] ?? __('Books Info', 'book-information');
        $menu_title = $menuConfig['menu_title'] ?? __('Books Info', 'book-information');
        $capability = $menuConfig['capability'] ?? 'manage_options';
        $menu_slug  = $menuConfig['menu_slug'] ?? 'books_info';
        $icon_url   = $menuConfig['icon_url'] ?? 'dashicons-book';
        $position   = $menuConfig['position'] ?? 6;

        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            [$this, 'renderBooksInfoPage'],
            $icon_url,
            $position
        );
    }

    /**
     * Render the Books Info admin page.
     */
    public function renderBooksInfoPage()
    {
        echo '<div class="wrap"><h1>' . esc_html__('Books Information', 'book-information') . '</h1>';

        // Use the injected configuration and plugin instance
        $config = $this->config;
        $plugin = $this->plugin;

        // Instantiate the Books_List_Table with configuration and plugin
        $listTable = new \BookInformation\Admin\Books_List_Table($config, $plugin);

        $listTable->process_bulk_action(); // Process bulk actions
        $listTable->prepare_items();
        ?>
        <form method="post">
            <?php
            // Add a nonce field for security
            wp_nonce_field('book_information_list_table', '_book_information_nonce');

            // Display the list table
            $listTable->display();
            ?>
        </form>
        <?php
        echo '</div>';
    }
}