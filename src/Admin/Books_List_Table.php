<?php

namespace BookInformation\Admin;

use WP_List_Table;
use Configula\ConfigValues;

class Books_List_Table extends WP_List_Table
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

        parent::__construct([
            'singular' => __('Book', 'book-information'),
            'plural'   => __('Books', 'book-information'),
            'ajax'     => false,
        ]);
    }

    /**
     * Get a list of columns.
     *
     * @return array
     */
    public function get_columns()
    {
        return [
            'cb'      => '<input type="checkbox" />',
            'ID'      => __('ID', 'book-information'),
            'post_id' => __('Book Title', 'book-information'),
            'isbn'    => __('ISBN', 'book-information'),
        ];
    }

    /**
     * Default column rendering.
     *
     * @param array  $item        The item being rendered.
     * @param string $column_name The name of the column.
     *
     * @return string
     */
    protected function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'ID':
                return esc_html($item['ID']);
            case 'post_id':
                $post_title = get_the_title($item['post_id']);
                $edit_link  = get_edit_post_link($item['post_id']);

                return sprintf(
                    '<a href="%s">%s</a>',
                    esc_url($edit_link),
                    esc_html($post_title)
                );
            case 'isbn':
                return esc_html($item['isbn']);
            default:
                return print_r($item, true);
        }
    }

    /**
     * Checkbox column.
     *
     * @param array $item The item being rendered.
     *
     * @return string
     */
    protected function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />',
            esc_attr($item['ID'])
        );
    }

    /**
     * Get bulk actions.
     *
     * @return array
     */
    protected function get_bulk_actions()
    {
        return [
            'delete' => __('Delete', 'book-information'),
        ];
    }

    /**
     * Process bulk actions.
     */
    public function process_bulk_action()
    {
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['book']) ? $_REQUEST['book'] : [];
            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $ids = array_map('intval', $ids);

            /** @var \BookInformation\Models\Book $bookModel */
            $bookModel = $this->plugin->get('book_model');
            $bookModel::destroy($ids);
        }
    }

    /**
     * Prepare the items for display.
     */
    public function prepare_items()
    {
        /** @var \BookInformation\Models\Book $bookModel */
        $bookModel = $this->plugin->get('book_model');

        // Get 'per_page' from configuration, default to 20 if not set
        $per_page = $this->config->get('list_table.per_page', 20);

        $current_page = $this->get_pagenum();
        $total_items  = $bookModel::count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $data = $bookModel::orderBy('ID', 'DESC')
            ->offset(($current_page - 1) * $per_page)
            ->limit($per_page)
            ->get()
            ->toArray();

        $this->items = $data;
    }
}