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

class badge {
    public function get_user_course_badge_feed($userid, $courseid, $limitfrom = 0, $limitnum = 5) {
        global $DB;

        $sql = "SELECT bi.id, bi.badgeid, bi.userid, bi.dateissued, bi.uniquehash
                FROM {badge_issued} bi
                INNER JOIN {badge} b ON bi.badgeid = b.id
                WHERE bi.userid = :userid AND b.courseid = :courseid
                ORDER BY bi.id DESC";

        $records = $DB->get_records_sql($sql, ['userid' => $userid, 'courseid' => $courseid], $limitfrom, $limitnum);

        if (!$records) {
            return [];
        }

        $data = [];

        foreach ($records as $record) {
            $url = new \moodle_url('/badges/badge.php', ['hash' => $record->uniquehash]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->dateissued,
                'icon' => 'fa-certificate',
                'text' => get_string('portfolio_earnedbadge_string', 'block_evokefeed'),
                'url' => $url
            ];
        }

        return $data;
    }
}