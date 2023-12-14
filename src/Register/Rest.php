<?php

/**
 * Rest class
 */

namespace MAR\PrimaryCategory\Register;

use MAR\PrimaryCategory\Traits\PostTypes;

/**
 * Rest class manages itegrations with REST api
 */
class Rest
{

    use PostTypes;

    /**
     * Rest constructor
     */
    public function __construct()
    {
        add_action('init', array($this, 'setup_rest_filters'));
    }

    /**
     * Setup rest filters
     *
     * @return void
     */
    public function setup_rest_filters()
    {
        $post_types = $this->get_valid_post_types();

        foreach ($post_types as $post_type) {
            add_filter("rest_{$post_type}_query", array($this, 'post_meta_request_params'), 99, 2);
        }
    }

    /**
     * Add to request params
     *
     * @param array $args
     * @param array $request
     * @return array
     */
    public function post_meta_request_params($args, $request)
    {

        if ($this->is_valid_string($request['meta_key'])) {
            $args['meta_key'] = sanitize_text_field($request['meta_key']);
        }

        if (isset($request['meta_value'])) {
            $args['meta_value'] = $this->sanitize_meta_value($request['meta_value']);
        }

        if (isset($request['meta_query']) && is_array($request['meta_query'])) {
            $args['meta_query'] = $request['meta_query'];
        }

        return $args;
    }

    /**
     * Checks if a value is a valid non-empty string.
     *
     * @param mixed $value
     * @return boolean
     */
    private function is_valid_string($value)
    {
        return isset($value) && is_string($value) && !empty($value);
    }

    /**
     * Sanitizes a meta value.
     *
     * @param mixed $value
     * @return mixed
     */
    private function sanitize_meta_value($value)
    {
        return sanitize_text_field($value);
    }
}
