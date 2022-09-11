<?php

namespace block_evokefeed\external;

use block_evokefeed\util\feed as feedutil;
use external_api;
use external_value;
use external_single_structure;
use external_function_parameters;

/**
 * Section external api class.
 *
 * @package     mod_evokeportfolio
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class feed extends external_api {
    /**
     * Create chapter parameters
     *
     * @return external_function_parameters
     */
    public static function load_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'The block course id'),
            'type' => new external_value(PARAM_ALPHAEXT, 'Timeline type, group or network'),
            'limitcomments' => new external_value(PARAM_INT, 'The limit valur for comments'),
            'limitlikes' => new external_value(PARAM_INT, 'The limit valur for likes'),
            'limitskilpoints' => new external_value(PARAM_INT, 'The limit valur for skilpoints'),
            'limitevocoins' => new external_value(PARAM_INT, 'The limit valur for evocoins'),
            'limitbadges' => new external_value(PARAM_INT, 'The limit valur for badges'),
            'limitsubmissions' => new external_value(PARAM_INT, 'The limit valur for portfolio submissions'),
            'hasmoreitems' => new external_value(PARAM_BOOL, 'Load more items control')
        ]);
    }

    /**
     * Create chapter method
     *
     * @param int $courseid
     * @param string $type
     * @param int $limitcomments
     * @param int $limitlikes
     * @param int $limitskilpoints
     * @param int $limitevocoins
     * @param int $limitbadges
     * @param int $limitsubmissions
     * @param bool $hasmoreitems
     *
     * @return array
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public static function load($courseid, $type, $limitcomments, $limitlikes, $limitskilpoints, $limitevocoins, $limitbadges, $limitsubmissions, $hasmoreitems) {
        global $PAGE;

        // We always must pass webservice params through validate_parameters.
        $params = self::validate_parameters(self::load_parameters(), [
            'courseid' => $courseid,
            'type' => $type,
            'limitcomments' => $limitcomments,
            'limitlikes' => $limitlikes,
            'limitskilpoints' => $limitskilpoints,
            'limitevocoins' => $limitevocoins,
            'limitbadges' => $limitbadges,
            'limitsubmissions' => $limitsubmissions,
            'hasmoreitems' => $hasmoreitems
        ]);

        $context = \context_course::instance($courseid);
        $PAGE->set_context($context);

        $feedutil = new feedutil();

        $sourcesdata = $feedutil->get_data_from_sources($params);

        if (!$sourcesdata) {
            $hasmoreitems = false;
        }

        $returndata = [
            'hasmoreitems' => $hasmoreitems,
            'items' => $sourcesdata
        ];

        return [
            'status' => 'ok',
            'data' => json_encode($returndata)
        ];
    }

    /**
     * Create chapter return fields
     *
     * @return external_single_structure
     */
    public static function load_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_TEXT, 'Operation status'),
                'data' => new external_value(PARAM_RAW, 'Return data')
            )
        );
    }
}
