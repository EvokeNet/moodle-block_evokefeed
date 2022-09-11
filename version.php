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
$plugin->release = '1.0.0';
$plugin->version = 20220901000;
$plugin->requires = 2021051700;
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = [
    'mod_evokeportfolio' => 2022032800,
    'local_evokegame' => 2022021000
];
