<?php

/**
 * Evoke feed portfolio datasource.
 *
 * @package     block_evokefeed
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace block_evokefeed\datasource;

use block_evokefeed\util\userimg;

defined('MOODLE_INTERNAL') || die();

class skillpoint {
    public function get_user_course_points_feed($courseid, $limitfrom = 0, $limitnum = 5) {
        global $DB;

        $sql = 'SELECT id, points, userid, timecreated
                FROM {evokegame_logs}
                WHERE courseid = :courseid
                ORDER BY id DESC
                LIMIT ' . $limitnum;

        $params = [
            'courseid' => $courseid,
        ];

        if ($limitfrom) {
            $offset = $limitfrom * $limitnum;

            $sql .= ' OFFSET ' . $offset;
        }

        $records = $DB->get_records_sql($sql, $params);

        if (!$records) {
            return [];
        }

        $userimg = userimg::get_instance();

        $data = [];

        foreach ($records as $record) {
            $url = new \moodle_url('/local/evokegame/profile.php', ['id' => $courseid]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->timecreated,
                'icon' => 'fa-heart-o',
                'userimg' => $userimg::get_image($record->userid),
                'userfullname' => $userimg::get_fullname($record->userid),
                'text' => get_string('portfolio_earnedpoints_string', 'block_evokefeed', (int)$record->points),
                'url' => $url->out()
            ];
        }

        return $data;
    }
}
