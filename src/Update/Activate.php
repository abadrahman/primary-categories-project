<?php

/**
 * Activate class
 */

namespace MAR\PrimaryCategory\Update;

/**
 * Activate class to handle all activation requirements
 */
class Activate
{
    /**
     * Handle activate
     *
     * @return void
     */
    public static function activate()
    {
        update_option('pcp_version', PCP_VERSION);
    }
}
