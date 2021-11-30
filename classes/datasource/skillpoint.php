<?php

/**
 * Evoke feed portfolio datasource.
 *
 * @package     block_evokefeed
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace block_evokefeed\datasource;

defined('MOODLE_INTERNAL') || die();

class skillpoint {
    public function get_user_course_points_feed($userid, $courseid, $limitfrom = 0, $limitnum = 5) {
        global $DB;

        $sql = 'SELECT id, points, timecreated
                FROM {evokegame_logs}
                WHERE userid = :userid AND courseid = :courseid
                ORDER BY id DESC';

        $records = $DB->get_records_sql($sql, ['userid' => $userid, 'courseid' => $courseid], $limitfrom, $limitnum);

        if (!$records) {
            return [];
        }

        $data = [];

        foreach ($records as $record) {
            $url = new \moodle_url('/local/evokegame/profile.php', ['id' => $courseid]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->timecreated,
                'icon' => 'fa-heart-o',
                'text' => get_string('portfolio_earnedpoints_string', 'block_evokefeed', (int)$record->points),
                'url' => $url
            ];
        }

        return $data;
    }
}