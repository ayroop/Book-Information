<?php

namespace BookInformation\Providers;

use Rabbit\Contracts\BootablePluginProviderInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Configula\ConfigValues;
use Exception;

/**
 * Class PostTypeServiceProvider
 *
 * This service provider handles the registration of the custom post type 'book',
 * its associated taxonomies, meta boxes, and the saving of metadata.
 */
class PostTypeServiceProvider extends AbstractServiceProvider implements BootablePluginProviderInterface
{
    /**
     * Configuration values loaded from the plugin's configuration files.
     *
     * @var ConfigValues
     */
    protected $config;

    /**
     * The main plugin instance.
     *
     * @var \Rabbit\Plugin
     */
    protected $plugin;

    /**
     * PostTypeServiceProvider constructor.
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
     * Register the service provider.
     *
     * Required by AbstractServiceProvider, but not used in this context.
     */
    public function register()
    {
        // No services to register in this service provider.
    }

    /**
     * Boot the plugin provider.
     *
     * This method is called after all service providers have been registered.
     * It hooks into WordPress actions to set up the custom post type,
     * taxonomies, meta boxes, and save actions.
     */
    public function bootPlugin()
    {
        // Hook into WordPress 'init' action to register post type and taxonomies.
        add_action('init', [$this, 'registerPostType']);
        add_action('init', [$this, 'registerTaxonomies']);

        // Add meta boxes to the 'book' post type.
        add_action('add_meta_boxes', [$this, 'addIsbnMetaBox']);

        // Save the ISBN meta data when a 'book' post is saved.
        add_action('save_post_book', [$this, 'saveIsbnMeta'], 10, 3);
    }

    /**
     * Register the custom post type 'book'.
     *
     * Uses configuration values from 'plugin.php' under 'post_type'.
     *
     * @throws Exception If the post type configuration is missing.
     */
    public function registerPostType()
    {
        // Retrieve post type configuration from the plugin's configuration.
        $postTypeConfig = $this->config->get('post_type', []);

        if (!empty($postTypeConfig)) {
            // Get the slug, labels, and arguments from the configuration.
            $slug   = $postTypeConfig['slug'] ?? 'book';      // Slug for the custom post type.
            $labels = $postTypeConfig['labels'] ?? [];        // Labels for the admin UI.
            $args   = $postTypeConfig['args'] ?? [];          // Additional arguments.

            // Ensure labels are translated.
            $labels = $this->translateLabels($labels);

            // Assign labels to the arguments array.
            $args['labels'] = $labels;

            // Register the custom post type with WordPress.
            register_post_type($slug, $args);
        } else {
            // Handle missing configuration by throwing an exception.
            throw new Exception(
                /* translators: Error message when post type configuration is missing */
                __('Post type configuration is missing.', 'book-information')
            );
        }
    }

    /**
     * Register taxonomies for the custom post type 'book'.
     *
     * Uses configuration values from 'plugin.php' under 'taxonomies'.
     *
     * @throws Exception If taxonomies configuration is missing.
     */
    public function registerTaxonomies()
    {
        // Retrieve taxonomies configuration from the plugin's configuration.
        $taxonomiesConfig = $this->config->get('taxonomies', []);

        if (!empty($taxonomiesConfig)) {
            foreach ($taxonomiesConfig as $taxonomy => $config) {
                // Get labels and arguments from the configuration.
                $labels = $config['labels'] ?? [];              // Labels for the taxonomy.
                $args   = $config['args'] ?? [];                // Additional arguments.

                // Ensure labels are translated.
                $labels = $this->translateLabels($labels);

                // Assign labels and hierarchical setting to arguments.
                $args['labels']       = $labels;
                $args['hierarchical'] = $config['hierarchical'] ?? false;

                // Register the taxonomy and associate it with the 'book' post type.
                register_taxonomy($taxonomy, ['book'], $args);
            }
        } else {
            // Handle missing configuration by throwing an exception.
            throw new Exception(
                /* translators: Error message when taxonomies configuration is missing */
                __('Taxonomies configuration is missing.', 'book-information')
            );
        }
    }

    /**
     * Add the ISBN meta box to the 'book' custom post type.
     *
     * Uses configuration values from 'plugin.php' under 'isbn_meta_box'.
     */
    public function addIsbnMetaBox()
    {
        // Retrieve meta box configuration from the plugin's configuration.
        $metaBoxConfig = $this->config->get('isbn_meta_box', []);

        // Extract settings with default fallbacks.
        $box_id   = $metaBoxConfig['box_id'] ?? 'book_isbn';
        $title    = $metaBoxConfig['title'] ?? __('ISBN Number', 'book-information');
        $context  = $metaBoxConfig['context'] ?? 'side';
        $priority = $metaBoxConfig['priority'] ?? 'default';

        // Add the meta box to the 'book' post type.
        add_meta_box(
            $box_id,
            $title,
            [$this, 'renderIsbnMetaBox'],
            'book',
            $context,
            $priority
        );
    }

    /**
     * Render the ISBN meta box content.
     *
     * @param \WP_Post $post The current post object.
     */
    public function renderIsbnMetaBox($post)
    {
        // Retrieve meta box configuration from the plugin's configuration.
        $metaBoxConfig = $this->config->get('isbn_meta_box', []);

        // Extract nonce settings and meta key with default fallbacks.
        $nonce_action = $metaBoxConfig['nonce_action'] ?? 'save_isbn_meta';
        $nonce_name   = $metaBoxConfig['nonce_name'] ?? 'book_isbn_nonce';
        $meta_key     = $metaBoxConfig['meta_key'] ?? '_book_isbn';

        // Add a nonce field for security to verify upon saving.
        wp_nonce_field($nonce_action, $nonce_name);

        // Retrieve the current ISBN value from post meta.
        $isbn = get_post_meta($post->ID, $meta_key, true);

        // Output the label and input field for ISBN.
        echo '<label for="book_isbn">' . esc_html__('ISBN', 'book-information') . '</label>';
        echo '<input type="text" id="book_isbn" name="book_isbn" value="' . esc_attr($isbn) . '" style="width:100%;" />';
    }

    /**
     * Save the ISBN meta data when the post is saved.
     *
     * @param int      $post_id Post ID.
     * @param \WP_Post $post    Post object.
     * @param bool     $update  Whether this is an existing post being updated or not.
     */
    public function saveIsbnMeta($post_id, $post, $update)
    {
        // Retrieve meta box configuration from the plugin's configuration.
        $metaBoxConfig = $this->config->get('isbn_meta_box', []);

        // Extract nonce settings and meta key with default fallbacks.
        $nonce_action = $metaBoxConfig['nonce_action'] ?? 'save_isbn_meta';
        $nonce_name   = $metaBoxConfig['nonce_name'] ?? 'book_isbn_nonce';
        $meta_key     = $metaBoxConfig['meta_key'] ?? '_book_isbn';

        // Verify nonce for security.
        if (!isset($_POST[$nonce_name]) || !wp_verify_nonce($_POST[$nonce_name], $nonce_action)) {
            return; // Nonce verification failed.
        }

        // Check if the user has permission to edit the post.
        if (!current_user_can('edit_post', $post_id)) {
            return; // User does not have permission.
        }

        // Check if this is an autosave or a revision.
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return; // Do not proceed during autosave or revision.
        }

        // Sanitize and retrieve the ISBN value from the form input.
        $isbn = isset($_POST['book_isbn']) ? sanitize_text_field($_POST['book_isbn']) : '';

        // Update the ISBN in post meta.
        update_post_meta($post_id, $meta_key, $isbn);

        // Optionally save ISBN to a custom table if necessary.
        /** @var \BookInformation\Models\Book $bookModel */
        $bookModel = $this->plugin->get('book_model');

        // Update or create the book record in the custom table.
        $bookModel::updateOrCreate(
            ['post_id' => $post_id],
            ['isbn'    => $isbn]
        );
    }

    /**
     * Translate labels recursively.
     *
     * @param array $labels Array of labels to translate.
     *
     * @return array Translated labels.
     */
    private function translateLabels(array $labels)
    {
        foreach ($labels as $key => $label) {
            if (is_array($label)) {
                $labels[$key] = $this->translateLabels($label);
            } else {
                $labels[$key] = __($label, 'book-information');
            }
        }
        return $labels;
    }
}