<?php

/**
 * Evoke feed block renderer.
 *
 * @package     block_evokefeed
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */

namespace block_evokefeed\output;

defined('MOODLE_INTERNAL') || die();

use block_evokefeed\datasource\badge;
use block_evokefeed\datasource\evocoin;
use block_evokefeed\datasource\portfolio;
use block_evokefeed\datasource\skillpoint;
use renderable;
use templatable;
use renderer_base;

/**
 * Evoke feed block renderable class.
 *
 * @package    block_evokefeed
 * @copyright   2021 World Bank Group <https://worldbank.org>
 * @author      Willian Mano <willianmanoaraujo@gmail.com>
 */
class block implements renderable, templatable {

    protected $user;
    protected $course;
    public function __construct($user, $course) {
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Export the data.
     *
     * @param renderer_base $output
     *
     * @return array|\stdClass
     *
     * @throws \coding_exception
     *
     * @throws \dml_exception
     */
    public function export_for_template(renderer_base $output) {
        return [
            'data' => $this->get_data_from_sources()
        ];
    }

    private function get_data_from_sources() {
        $portfoliosource = new portfolio();
        $skillpointsource = new skillpoint();
        $evocoinsource = new evocoin();
        $badgesource = new badge();

        $comments = $portfoliosource->get_user_course_comment_feed($this->user->id, $this->course->id);
        $likes = $portfoliosource->get_user_course_like_feed($this->user->id, $this->course->id);
        $skilpoints = $skillpointsource->get_user_course_points_feed($this->user->id, $this->course->id);
        $evocoins = $evocoinsource->get_user_course_coins_feed($this->user->id, $this->course->id);
        $badges = $badgesource->get_user_course_badge_feed($this->user->id, $this->course->id);

        $data = array_merge($comments, $likes, $skilpoints, $evocoins, $badges);

        if (!$data) {
            return [];
        }

        usort($data, function($a, $b) {
            if ($a['timecreated'] == $b['timecreated']) {
                return 0;
            }

            return ($a['timecreated'] > $b['timecreated']) ? -1 : 1;
        });

        return $data;
    }
}