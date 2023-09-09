<?php

namespace block_evokefeed\util;

class users {
    public function get_user_groups_users($courseid, $userid = null) {
        global $USER;

        if (!$userid) {
            $userid = $USER->id;
        }

        $groups = $this->get_user_groups($courseid, $userid);

        if (!$groups) {
            return false;
        }

        return $this->get_groups_members_ids($groups, \core\context\course::instance($courseid));
    }

    public function get_course_enrolled_users($courseid) {
        return [];
    }

    private function get_user_groups($courseid, $userid = null) {
        global $DB, $USER;

        if (!$userid) {
            $userid = $USER->id;
        }

        $sql = "SELECT g.id, g.name, g.picture
                FROM {groups} g
                JOIN {groups_members} gm ON gm.groupid = g.id
                WHERE gm.userid = :userid AND g.courseid = :courseid";

        $groups = $DB->get_records_sql($sql, ['courseid' => $courseid, 'userid' => $userid]);

        if (!$groups) {
            return false;
        }

        return $groups;
    }

    private function get_groups_members($groups, $contexttofilter = false) {
        global $DB;

        $ids = [];
        foreach ($groups as $group) {
            $ids[] = $group->id;
        }

        list($groupsids, $groupsparams) = $DB->get_in_or_equal($ids, SQL_PARAMS_NAMED, 'group');

        $sql = "SELECT u.*
                FROM {groups_members} gm
                INNER JOIN {user} u ON u.id = gm.userid
                WHERE gm.groupid " . $groupsids;

        $groupsmembers = $DB->get_records_sql($sql, $groupsparams);

        if (!$groupsmembers) {
            return false;
        }

        // Remove any person who have access to grade students. Teachers, mentors...
        if ($contexttofilter) {
            foreach ($groupsmembers as $key => $groupmember) {
                if (has_capability('mod/evokeportfolio:grade', $contexttofilter, $groupmember->id)) {
                    unset($groupsmembers[$key]);
                }
            }
        }

        return array_values($groupsmembers);
    }

    private function get_groups_members_ids($groups, $contexttofilter = false) {
        $users = $this->get_groups_members($groups, $contexttofilter);

        if (!$users) {
            return false;
        }

        $ids = [];
        foreach ($users as $user) {
            $ids[] = $user->id;
        }

        return $ids;
    }
}
