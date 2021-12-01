<?php

namespace block_evokefeed\external;

use block_evokefeed\datasource\badge;
use block_evokefeed\datasource\evocoin;
use block_evokefeed\datasource\portfolio;
use block_evokefeed\datasource\skillpoint;
use context;
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
            'limitcomments' => new external_value(PARAM_INT, 'The limit valur for comments'),
            'limitlikes' => new external_value(PARAM_INT, 'The limit valur for likes'),
            'limitskilpoints' => new external_value(PARAM_INT, 'The limit valur for skilpoints'),
            'limitevocoins' => new external_value(PARAM_INT, 'The limit valur for evocoins'),
            'limitbadges' => new external_value(PARAM_INT, 'The limit valur for badges'),
            'hasmoreitems' => new external_value(PARAM_BOOL, 'Load more items control')
        ]);
    }

    /**
     * Create chapter method
     *
     * @param int $courseid
     * @param int $limitcomments
     * @param int $limitlikes
     * @param int $limitskilpoints
     * @param int $limitevocoins
     * @param int $limitbadges
     * @param bool $hasmoreitems
     *
     * @return array
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \invalid_parameter_exception
     * @throws \moodle_exception
     */
    public static function load($courseid, $limitcomments, $limitlikes, $limitskilpoints, $limitevocoins, $limitbadges, $hasmoreitems) {
        global $USER;

        // We always must pass webservice params through validate_parameters.
        $params = self::validate_parameters(self::load_parameters(), [
            'courseid' => $courseid,
            'limitcomments' => $limitcomments,
            'limitlikes' => $limitlikes,
            'limitskilpoints' => $limitskilpoints,
            'limitevocoins' => $limitevocoins,
            'limitbadges' => $limitbadges,
            'hasmoreitems' => $hasmoreitems
        ]);

        $portfoliosource = new portfolio();
        $skillpointsource = new skillpoint();
        $evocoinsource = new evocoin();
        $badgesource = new badge();

        $comments = $portfoliosource->get_user_course_comment_feed($USER->id, $courseid, $limitcomments);
        $likes = $portfoliosource->get_user_course_like_feed($USER->id, $courseid, $limitlikes);
        $skilpoints = $skillpointsource->get_user_course_points_feed($USER->id, $courseid, $limitskilpoints);
        $evocoins = $evocoinsource->get_user_course_coins_feed($USER->id, $courseid, $limitevocoins);
        $badges = $badgesource->get_user_course_badge_feed($USER->id, $courseid, $limitbadges);

        $sourcesdata = array_merge($comments, $likes, $skilpoints, $evocoins, $badges);

        if (!$sourcesdata) {
            $hasmoreitems = false;
        }

        $feedutil = new \block_evokefeed\util\feed();

        $returndata = [
            'hasmoreitems' => $hasmoreitems,
            'items' => $feedutil->sort_data($sourcesdata)
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