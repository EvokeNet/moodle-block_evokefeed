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

class portfolio {
    public function get_user_course_comment_feed($userid, $courseid, $limitfrom = 0, $limitnum = 5) {
        global $DB;

        $sql = 'SELECT c.id, c.timecreated, p.course, u.firstname, u.lastname, p.id as portfolioid, se.id as sectionid
                FROM {evokeportfolio_comments} c
                INNER JOIN {evokeportfolio_submissions} su ON su.id = c.submissionid
                INNER JOIN {evokeportfolio_sections} se ON su.sectionid = se.id
                INNER JOIN {evokeportfolio} p ON p.id = se.portfolioid
                INNER JOIN {user} u ON u.id = c.userid
                WHERE su.postedby = :userid AND p.course = :courseid
                ORDER BY c.id DESC
                LIMIT ' . $limitnum;

        $params = [
            'userid' => $userid,
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

        $data = [];

        foreach ($records as $record) {
            $coursemodule = get_coursemodule_from_instance('evokeportfolio', $record->portfolioid, $record->course);

            $url = new \moodle_url('/mod/evokeportfolio/section.php', ['id' => $coursemodule->id, 'sectionid' => $record->sectionid, 'userid' => $userid]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->timecreated,
                'icon' => 'fa-commenting-o',
                'text' => get_string('portfolio_comment_string', 'block_evokefeed', $record->firstname),
                'url' => $url->out()
            ];
        }

        return $data;
    }

    public function get_user_course_like_feed($userid, $courseid, $limitfrom = 0, $limitnum = 5) {
        global $DB;

        $sql = 'SELECT r.id, r.timecreated, p.course, u.firstname, u.lastname, p.id as portfolioid, se.id as sectionid
                FROM {evokeportfolio_reactions} r
                INNER JOIN {evokeportfolio_submissions} su ON su.id = r.submissionid
                INNER JOIN {evokeportfolio_sections} se ON su.sectionid = se.id
                INNER JOIN {evokeportfolio} p ON p.id = se.portfolioid
                INNER JOIN {user} u ON u.id = r.userid
                WHERE su.postedby = :userid AND p.course = :courseid
                ORDER BY r.id DESC
                LIMIT ' . $limitnum;

        $params = [
            'userid' => $userid,
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

        $data = [];

        foreach ($records as $record) {
            $coursemodule = get_coursemodule_from_instance('evokeportfolio', $record->portfolioid, $record->course);

            $url = new \moodle_url('/mod/evokeportfolio/section.php', ['id' => $coursemodule->id, 'sectionid' => $record->sectionid, 'userid' => $userid]);

            $data[] = [
                'id' => $record->id,
                'timecreated' => $record->timecreated,
                'icon' => 'fa-thumbs-o-up',
                'text' => get_string('portfolio_like_string', 'block_evokefeed', $record->firstname),
                'url' => $url->out()
            ];
        }

        return $data;
    }
}