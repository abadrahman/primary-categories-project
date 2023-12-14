<?php

namespace MAR\PrimaryCategory\Traits;

/**
 * Trait PostTypes
 */
trait PostTypes
{

    /**
     * Get Valid Post Types for Primary Categories
     *
     * @return array
     */
    public function get_valid_post_types()
    {
        $post_types = get_post_types(array(
            'public'   => true,
        ));

        $valid_post_types = array_filter($post_types, function ($type) {
            return !empty(get_object_taxonomies($type));
        });

        return $valid_post_types;
    }
}
