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

class portfolio {
    public function get_users_course_comment_feed($courseid, $users = [], $limitfrom = 0, $limitnum = 5) {
        global $DB, $USER;

        $sql = 'SELECT c.id, c.timecreated, p.course, u.id as userid, u.firstname, u.lastname, p.id as portfolioid
                FROM {evokeportfolio_comments} c
                INNER JOIN {evokeportfolio_submissions} su ON (su.id = c.submissionid)
                INNER JOIN {evokeportfolio} p ON p.id = su.portfolioid
                INNER JOIN {user} u ON u.id = c.userid
                WHERE p.course = :courseid AND c.userid <> :userid';

        if (!empty($users)) {
            list($insql, $inparams) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);

            $sql .= ' AND c.userid ' . $insql;
        }

        $sql .= ' ORDER BY c.id DESC LIMIT ' . $limitnum;

        $inparams['courseid'] = $courseid;
        $inparams['userid'] = $USER->id;

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
            $coursemodule = get_coursemodule_from_instance('evokeportfolio', $record->portfolioid, $record->course);

            $url = new \moodle_url('/mod/evokeportfolio/viewsubmission.php', ['id' => $coursemodule->id, 'userid' => $USER->id]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->timecreated,
                'icon' => 'fa-commenting-o',
                'userimg' => $userimg::get_image($record->userid),
                'userfullname' => $userimg::get_fullname($record->userid),
                'text' => get_string('portfolio_comment_string', 'block_evokefeed'),
                'url' => $url->out()
            ];
        }

        return $data;
    }

    public function get_users_course_like_feed($courseid, $users = [], $limitfrom = 0, $limitnum = 5) {
        global $DB, $USER;

        $sql = 'SELECT r.id, r.timecreated, p.course, u.id as userid, u.firstname, u.lastname, p.id as portfolioid
                FROM {evokeportfolio_reactions} r
                INNER JOIN {evokeportfolio_submissions} su ON (su.id = r.submissionid)
                INNER JOIN {evokeportfolio} p ON p.id = su.portfolioid
                INNER JOIN {user} u ON u.id = r.userid
                WHERE p.course = :courseid AND r.userid <> :userid';

        if (!empty($users)) {
            list($insql, $inparams) = $DB->get_in_or_equal($users, SQL_PARAMS_NAMED);

            $sql .= ' AND r.userid ' . $insql;
        }

        $sql .= ' ORDER BY r.id DESC LIMIT ' . $limitnum;

        $inparams['courseid'] = $courseid;
        $inparams['userid'] = $USER->id;

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
            $coursemodule = get_coursemodule_from_instance('evokeportfolio', $record->portfolioid, $record->course);

            $url = new \moodle_url('/mod/evokeportfolio/viewsubmission.php', ['id' => $coursemodule->id, 'userid' => $USER->id]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->timecreated,
                'icon' => 'fa-thumbs-o-up',
                'userimg' => $userimg::get_image($record->userid),
                'userfullname' => $userimg::get_fullname($record->userid),
                'text' => get_string('portfolio_like_string', 'block_evokefeed'),
                'url' => $url->out()
            ];
        }

        return $data;
    }
}