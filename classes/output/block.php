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

use renderable;
use templatable;
use renderer_base;
use block_evokefeed\util\feed;

/**
 * Evoke feed block renderable class.
 *
 * @package     block_evokefeed
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
        $feed = new feed();

        return [
            'courseid' => $this->course->id,
            'data' => $feed->get_data_from_sources($this->user->id, $this->course->id)
        ];
    }
}
