<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   block_clampmail
 * @copyright 2013 Collaborative Liberal Arts Moodle Project
 * @copyright 2012 Louisiana State University (original Quickmail block)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once('locallib.php');

$courseid    = required_param('courseid', PARAM_INT);
$course      = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

require_login($course);

// Check permissions.
$coursecontext = context_course::instance($course->id);
$usercontext = context_user::instance($USER->id);
// require_capability('local/starred_courses:canstar', $usercontext);

if ($courseid ===1 ) {
    $status = "Cannot star front page course";
}

$filterclass = 'block_filtered_course_list_starred_lib';

if ($filterclass::course_is_starred($USER->id, $courseid)) {
    $filterclass::unstar_course($USER->id, $courseid);
    $status = get_string('notify:course_unstarred', 'block_filtered_course_list', $course);
} else {
    $filterclass::star_course($USER->id, $courseid);
    $status = get_string('notify:course_starred', 'block_filtered_course_list', $course);
}

$blockname = "Starred Courses";
$header = $blockname;

$PAGE->set_context($coursecontext);
$PAGE->set_course($course);
$PAGE->navbar->add($blockname);
$PAGE->navbar->add($header);
$PAGE->set_title($blockname . ': ' . $header);
$PAGE->set_heading($blockname . ': ' . $header);
$PAGE->set_url('/blocks/filtered_course_list/toggle_starred.php', array('courseid' => $courseid));
$PAGE->set_pagetype($blockname);
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();
echo $OUTPUT->heading($header);

echo $status;

echo $OUTPUT->footer();
