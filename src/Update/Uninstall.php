<?php

/**
 * Uninstall class
 */

namespace MAR\PrimaryCategory\Update;

/**
 * Uninstall class to perform any cleanup
 */
class Uninstall
{
    /**
     * Handle uninstall
     *
     * @return void
     */
    public static function uninstall()
    {
        delete_option('swytch_version');
    }
}
