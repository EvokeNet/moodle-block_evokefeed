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
use block_evokefeed\datasource\portfoliobuilder;

defined('MOODLE_INTERNAL') || die();

class feed {
    public function get_data_from_sources($params) {
        $usersutil = new \block_evokefeed\util\users();

        if ($params['type'] == 'team') {
            $users = $usersutil->get_user_groups_users($params['courseid']);
        }

        if (!isset($users) || $params['type'] == 'network') {
            $users = [];
        }

        $portfoliosource = new portfoliobuilder();
        $badgesource = new badge();

        $badges = $badgesource->get_users_course_badge_feed($params['courseid'], $users, $params['limitbadges']);
        $comments = $portfoliosource->get_users_course_comment_feed($params['courseid'], $users, $params['limitcomments']);
        $likes = $portfoliosource->get_users_course_like_feed($params['courseid'], $users, $params['limitlikes']);
        $submissions = $portfoliosource->get_users_course_submission_feed($params['courseid'], $users, $params['limitsubmissions']);

        $data = array_merge($badges, $comments, $likes, $submissions);

        if (!$data) {
            return [];
        }

        $sorteddata = $this->sort_data($data);

        $output = [];
        foreach ($sorteddata as $item) {
            $output[] = $item['output'];
        }

        return $output;
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
