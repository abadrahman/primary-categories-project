<?php

/**
 * Taxonomy class 
 */

namespace MAR\PrimaryCategory\Taxonomy;

/**
 * Taxonomy class manages all taxonomy and terms
 */
class Taxonomy
{
    /**
     * The post object
     */
    private $post;

    /**
     * All valid terms
     */
    private $terms;

    /**
     * Constructor
     *
     * @param WP_Post $post
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get all valid terms
     *
     * @return array
     */
    public function get_all_terms()
    {
        $taxonomies = $this->filter_heirachical_taxonomies(get_post_taxonomies($this->post));

        foreach ($taxonomies as $taxonomy) {
            $this->terms[$taxonomy] = get_the_terms($this->post, $taxonomy);
        }

        return $this->terms;
    }

    /**
     * Filter taxonomy that are capable of having multiples
     *
     * @param array $taxonomies
     * @return array
     */
    private function filter_heirachical_taxonomies($taxonomies)
    {
        return array_filter($taxonomies, function ($taxonomy) {
            $obj = get_taxonomy($taxonomy);
            return $obj->hierarchical;
        });
    }
}
