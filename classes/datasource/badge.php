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
    public function get_users_course_badge_feed($courseid, $users = [], $limitfrom = 0, $limitnum = 5) {
        global $DB;

        $sql = 'SELECT bi.id, bi.badgeid, bi.userid, bi.dateissued, bi.uniquehash, b.name
                FROM {badge_issued} bi
                INNER JOIN {badge} b ON bi.badgeid = b.id
                WHERE b.courseid = :courseid ';

        if (!empty($users)) {
            list($insql, $inparams) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);

            $sql .= ' AND bi.userid ' . $insql;
        }

        $sql .= ' ORDER BY bi.id DESC LIMIT ' . $limitnum;

        $inparams['courseid'] = $courseid;

        if ($limitfrom) {
            $offset = $limitfrom * $limitnum;

            $sql .= ' OFFSET ' . $offset;
        }

        $records = $DB->get_records_sql($sql, $inparams);

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
