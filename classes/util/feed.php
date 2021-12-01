<?php

/**
 * Evoke feed utility class.
 *
 * @package     block_evokefeed
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace block_evokefeed\util;

use block_evokefeed\datasource\badge;
use block_evokefeed\datasource\evocoin;
use block_evokefeed\datasource\portfolio;
use block_evokefeed\datasource\skillpoint;

defined('MOODLE_INTERNAL') || die();

class feed {
    public function get_data_from_sources($userid, $courseid) {
        $portfoliosource = new portfolio();
        $skillpointsource = new skillpoint();
        $evocoinsource = new evocoin();
        $badgesource = new badge();

        $comments = $portfoliosource->get_user_course_comment_feed($userid, $courseid);
        $likes = $portfoliosource->get_user_course_like_feed($userid, $courseid);
        $skilpoints = $skillpointsource->get_user_course_points_feed($userid, $courseid);
        $evocoins = $evocoinsource->get_user_course_coins_feed($userid, $courseid);
        $badges = $badgesource->get_user_course_badge_feed($userid, $courseid);

        $data = array_merge($comments, $likes, $skilpoints, $evocoins, $badges);

        if (!$data) {
            return [];
        }

        return $this->sort_data($data);
    }

    public function sort_data($data) {
        usort($data, function($a, $b) {
            if ($a['timecreated'] == $b['timecreated']) {
                return 0;
            }

            return ($a['timecreated'] > $b['timecreated']) ? -1 : 1;
        });

        return $data;
    }
}