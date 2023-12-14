<?php

/**
 * Meta Boxes class
 */

namespace MAR\PrimaryCategory\Admin;


use MAR\PrimaryCategory\Traits\PostTypes;
use MAR\PrimaryCategory\Taxonomy\Taxonomy;

/**
 * Meta box classes manages the custom meta box
 */
class Metabox
{
    use PostTypes;
    /**
     * Constant for the meta key used for primary category
     */
    private const META_KEY = '_mar_primary_cat';

    /**
     * Constructor 
     */
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_primary_category_meta_box'));
        add_action('save_post', array($this, 'save_primary_category_meta_box'), 10, 3);
        add_action('init', array($this, 'register_meta_key'));
    }

    /**
     * Register the meta key for api
     *
     * @return void
     */
    public function register_meta_key()
    {
    
        register_post_meta('', self::META_KEY, array(
            'show_in_rest' => true,
            'type' => 'string',
            'single' => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            }
        ));
    }

    /**
     * Add the meta box to all valid post types
     *
     * @return void
     */
    public function add_primary_category_meta_box()
    {

        $post_types = $this->get_valid_post_types();
        
        add_meta_box(
            'primary_category_meta_box',
            'Primary Category',
            array($this, 'display_primary_category_meta_box'),
            $post_types,
            'side',
            'default'
        );
    }

    /**
     * Render the metabox content
     *
     * @param WP_Post $post
     * @return void
     */
    public function display_primary_category_meta_box($post)
    {

        $primary_category = get_post_meta($post->ID, self::META_KEY, true);

        $taxonomies = (new Taxonomy($post))->get_all_terms();


        echo '<label for="primary_category">Select Primary Category:</label>';
        echo '<select name="primary_category" id="primary_category">';
        echo '<option value="">Select</option>';

        foreach ($taxonomies as $taxonomy => $terms) {

            foreach ($terms as $term) {
                $selected = selected($primary_category, $term->term_id, false);
                echo "<option value='{$term->term_id}' $selected>{$term->name}</option>";
            }
        }

        echo '</select>';
    }

    /**
     * Handle saving meta box data
     *
     * @param int $post_id
     * @param WP_Post $post
     * @param bool $update
     * @return void
     */
    public function save_primary_category_meta_box($post_id, $post, $update)
    {
        //TODO use nonce verification
        if ($_POST['primary_category'] ?? null) {
            update_post_meta($post_id, self::META_KEY, sanitize_text_field($_POST['primary_category']));
        }
    }
}
