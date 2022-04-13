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

class badge {
    public function get_user_course_badge_feed($courseid, $limitfrom = 0, $limitnum = 5) {
        global $DB, $USER;

        $sql = 'SELECT bi.id, bi.badgeid, bi.userid, bi.dateissued, bi.uniquehash, b.name
                FROM {badge_issued} bi
                INNER JOIN {badge} b ON bi.badgeid = b.id
                WHERE b.courseid = :courseid AND bi.userid = :userid
                ORDER BY bi.id DESC
                LIMIT ' . $limitnum;

        $params = [
            'courseid' => $courseid,
            'userid' => $USER->id
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
            $url = new \moodle_url('/badges/badge.php', ['hash' => $record->uniquehash]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->dateissued,
                'icon' => 'fa-certificate',
                'userimg' => $userimg::get_image($record->userid),
                'userfullname' => $userimg::get_fullname($record->userid),
                'text' => get_string('portfolio_earnedbadge_string', 'block_evokefeed', $record->name),
                'url' => $url->out()
            ];
        }

        return $data;
    }
}