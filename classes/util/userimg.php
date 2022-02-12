<?php

/**
 * User image utility class
 *
 * @package     block_evokefeed
 * @copyright   2022 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace block_evokefeed\util;

defined('MOODLE_INTERNAL') || die;

use local_evokegame\util\user;

class userimg {
    private static $instance;
    private static $users = [];

    private function __construct() {}

    private function __clone() {}

    private function __wakeup() {}

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function get_image($userid) {
        if (isset(self::$users[$userid])) {
            return self::$users[$userid]->userimg;
        }

        $user = self::get_user($userid);

        $userutil = new user();

        $userimg = $userutil->get_user_avatar_or_image($user);

        if (is_object($userimg)) {
            $userimg = $userimg->out();
        }

        self::$users[$userid]->userimg = $userimg;

        return $userimg;
    }

    public static function get_fullname($userid) {
        if (isset(self::$users[$userid])) {
            return fullname(self::$users[$userid]);
        }

        $user = self::get_user($userid);

        return fullname($user);
    }

    public static function get_user($userid) {
        global $DB;

        if (isset(self::$users[$userid])) {
            return self::$users[$userid];
        }

        $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);

        self::$users[$userid] = $user;

        return $user;
    }
}
