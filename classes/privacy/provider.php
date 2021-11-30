<?php

namespace block_evokefeed\privacy;

/**
 * Privacy API implementation for the Evoke Feed plugin.
 *
 * @package     block_evokefeed
 * @category    privacy
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class provider implements \core_privacy\local\metadata\null_provider {

    /**
     * Returns stringid of a text explaining that this plugin stores no personal data.
     *
     * @return string
     */
    public static function get_reason() : string {
        return 'privacy:metadata';
    }
}
