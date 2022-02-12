<?php

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     block_evokefeed
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'block_evokefeed';
$plugin->release = '0.3.0';
$plugin->version = 2022021100;
$plugin->requires = 2021051700;
$plugin->maturity = MATURITY_BETA;
$plugin->dependencies = [
    'mod_evokeportfolio' => 2021112500,
    'local_evokegame' => 2022021000
];
