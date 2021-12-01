<?php

/**
 * Evokefeed services definition
 *
 * @package     block_evokefeed
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
    'block_evokefeed_load' => [
        'classname' => 'block_evokefeed\external\feed',
        'classpath' => 'blocks/evokefeed/classes/external/feed.php',
        'methodname' => 'load',
        'description' => 'Loads feed items using pagination',
        'type' => 'read',
        'ajax' => true
    ]
];
