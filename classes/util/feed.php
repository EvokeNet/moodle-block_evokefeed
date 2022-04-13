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
use block_evokefeed\datasource\portfolio;

defined('MOODLE_INTERNAL') || die();

class feed {
    public function get_data_from_sources($params) {
        $portfoliosource = new portfolio();
        $badgesource = new badge();

        $comments = $portfoliosource->get_user_course_comment_feed($params['courseid'], $params['limitcomments']);
        $likes = $portfoliosource->get_user_course_like_feed($params['courseid'], $params['limitlikes']);
        $badges = $badgesource->get_user_course_badge_feed($params['courseid'], $params['limitbadges']);

        $data = array_merge($comments, $likes, $badges);

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