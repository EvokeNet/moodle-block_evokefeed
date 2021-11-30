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

class evocoin {
    public function get_user_course_coins_feed($userid, $courseid, $limitfrom = 0, $limitnum = 5) {
        global $DB;

        $sql = "SELECT id, coins, timecreated
                FROM {evokegame_evcs_transactions}
                WHERE userid = :userid AND courseid = :courseid AND action = 'in'
                ORDER BY id DESC";

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
                'icon' => 'fa-bitcoin',
                'text' => get_string('portfolio_wonevocoins_string', 'block_evokefeed', (int)$record->coins),
                'url' => $url
            ];
        }

        return $data;
    }
}