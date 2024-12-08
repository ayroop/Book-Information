<?php

namespace BookInformation\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * The Book model represents a book record in the 'books_info' custom table.
 * It interacts with the custom table and provides methods to access related
 * WordPress post data and associated taxonomies like authors and publishers.
 */
class Book extends Model
{
    /**
     * The table associated with the model.
     * Specifies the custom table where book information is stored.
     *
     * @var string
     */
    protected $table = 'books_info';

    /**
     * The primary key associated with the table.
     * The primary key is 'ID' as defined in the table schema.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Indicates if the model should be timestamped.
     * Set to false as our custom table does not have 'created_at' and 'updated_at' columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * Allows mass assignment of these fields using methods like create() or fill().
     *
     * @var array
     */
    protected $fillable = ['post_id', 'isbn'];

    /**
     * Get the associated WordPress post.
     * Accessed via $book->post; returns the \WP_Post object related to this book.
     *
     * @return \WP_Post|null The WordPress post object or null if not found.
     */
    public function getPostAttribute()
    {
        // Use WordPress function get_post() to retrieve the post by post_id.
        return get_post($this->post_id);
    }

    /**
     * Get the book's title from the associated WordPress post.
     * Accessed via $book->title.
     *
     * @return string|null The post title or null if the post doesn't exist.
     */
    public function getTitleAttribute()
    {
        // Retrieve the associated post.
        $post = $this->post;

        // Return the post title if the post exists.
        return $post ? $post->post_title : null;
    }

    /**
     * Get the book's permalink (URL) from the associated WordPress post.
     * Accessed via $book->permalink.
     *
     * @return string|null The permalink URL or null if the post doesn't exist.
     */
    public function getPermalinkAttribute()
    {
        // Retrieve the associated post.
        $post = $this->post;

        // Return the permalink if the post exists.
        return $post ? get_permalink($post) : null;
    }

    /**
     * Get the authors associated with the book.
     * This retrieves the 'author' taxonomy terms linked to the book's post.
     * Accessed via $book->authors.
     *
     * @return array An array of \WP_Term objects or an empty array if none found.
     */
    public function getAuthorsAttribute()
    {
        // Use wp_get_post_terms() to get 'author' terms for the post.
        $terms = wp_get_post_terms($this->post_id, 'book_author');

        // Return the terms if no error occurred, otherwise return an empty array.
        return !is_wp_error($terms) ? $terms : [];
    }

    /**
     * Get the publishers associated with the book.
     * This retrieves the 'publisher' taxonomy terms linked to the book's post.
     * Accessed via $book->publishers.
     *
     * @return array An array of \WP_Term objects or an empty array if none found.
     */
    public function getPublishersAttribute()
    {
        // Use wp_get_post_terms() to get 'publisher' terms for the post.
        $terms = wp_get_post_terms($this->post_id, 'publisher');

        // Return the terms if no error occurred, otherwise return an empty array.
        return !is_wp_error($terms) ? $terms : [];
    }

    /**
     * Scope a query to only include books with a specific ISBN.
     * Allows for querying books by ISBN using Eloquent's query builder.
     *
     * Usage: Book::withIsbn('1234567890')->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $isbn The ISBN to filter by.
     * @return \Illuminate\Database\Eloquent\Builder The modified query builder.
     */
    public function scopeWithIsbn($query, $isbn)
    {
        // Add a where clause to filter by ISBN.
        return $query->where('isbn', $isbn);
    }

    /**
     * Accessor for the 'isbn' attribute.
     * Accessed via $book->isbn.
     *
     * @return string|null The ISBN of the book or null if not set.
     */
    public function getIsbnAttribute()
    {
        // Return the 'isbn' attribute from the attributes array.
        return $this->attributes['isbn'] ?? null;
    }

    /**
     * Mutator for the 'isbn' attribute.
     * Allows you to modify the value before it's saved to the database.
     *
     * @param string $value The ISBN value to be set.
     */
    public function setIsbnAttribute($value)
    {
        // Sanitize and set the 'isbn' attribute.
        $this->attributes['isbn'] = sanitize_text_field($value);
    }

    /**
     * Get a summary of the book.
     * This could be extended to retrieve an excerpt or custom field from the post.
     *
     * @return string|null The summary or null if not available.
     */
    public function getSummaryAttribute()
    {
        // Retrieve the associated post.
        $post = $this->post;

        // Return the post excerpt if the post exists.
        return $post ? $post->post_excerpt : null;
    }

    /**
     * Determine if the book has a specific author.
     *
     * @param int|string $author_id The term ID or slug of the author.
     * @return bool True if the book has the author, false otherwise.
     */
    public function hasAuthor($author_id)
    {
        // Get the authors associated with the book.
        $authors = $this->authors;

        // Check if any of the authors match the provided ID or slug.
        foreach ($authors as $author) {
            if ($author->term_id == $author_id || $author->slug == $author_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the book has a specific publisher.
     *
     * @param int|string $publisher_id The term ID or slug of the publisher.
     * @return bool True if the book has the publisher, false otherwise.
     */
    public function hasPublisher($publisher_id)
    {
        // Get the publishers associated with the book.
        $publishers = $this->publishers;

        // Check if any of the publishers match the provided ID or slug.
        foreach ($publishers as $publisher) {
            if ($publisher->term_id == $publisher_id || $publisher->slug == $publisher_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retrieve custom meta data associated with the book's post.
     * Allows access to any custom fields added to the post.
     *
     * @param string $key The meta key to retrieve.
     * @param bool $single Whether to return a single value.
     * @return mixed The meta value(s) or null if not found.
     */
    public function getPostMeta($key, $single = true)
    {
        // Use get_post_meta() to retrieve the meta data.
        return get_post_meta($this->post_id, $key, $single);
    }
}