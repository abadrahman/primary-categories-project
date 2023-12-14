<?php

/**
 * Blocks class
 */

namespace MAR\PrimaryCategory\Register;

/**
 * Block class manages adding all necessary blocks
 */
class Blocks
{

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', array($this, 'register_blocks'), 0);
    }

    /**
     * Register blocks found in blocks directory
     *
     * @return void
     */
    public function register_blocks()
    {

        $blocks = $this->get_blocks_directories() ?? false;

        if (!$blocks) {
            return;
        }

        foreach ($blocks as $block) {
            register_block_type(PCP_PATH . 'dist/blocks/' . $block);
        }
    }

    /**
     * Get the blocks directories
     *
     * @return array|bool
     */
    public function get_blocks_directories()
    {

        $blocks = array_diff(scandir(PCP_PATH . "/resources/blocks"), array('.', '..'));

        if ($blocks ?? null) {
            return $blocks;
        }

        return false;
    }
}
