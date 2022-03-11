<?php

// This file is part of Moodle - http://moodle.org/
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
 * Library.
 *
 * @category    blocks
 * @author      Bilal Sardar (bilal@3ilogic.com)
 * @copyright   2020 onwards 3i Logic (Private) Limited (http://www.3ilogic.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once dirname(__FILE__) . '/../../../config.php'; // Creates $PAGE.
require_once dirname(__FILE__) . '/lib.php';


/* Course Drop Down */
if($_POST['function'] == 'get_all_courses') {

    $data = sanitize_data($_POST);

    $allcourses = get_all_courses();

    $options = [];

    $options[] = '<option value="">Select Course</option>';
    foreach($allcourses as $value) {
        $options[] = '<option value="'.$value->id.'">'.$value->fullname.'</option>';
    }

    $optionsstr = implode('',$options);
    echo json_encode($optionsstr);
    
}

/* Quiz Drop Down */
if($_POST['function'] == 'get_course_quiz') {

    $data = sanitize_data($_POST);

    $allquiz = get_course_quiz($data['cid']);

    $options = [];

    $options[] = '<option value="">Select Quiz</option>';
    foreach($allquiz as $value) {
        $options[] = '<option value="'.$value->qid.'">'.$value->quizname.'</option>';
    }

    $optionsstr = implode('',$options);
    echo json_encode($optionsstr);
    
}

/* Student Drop Down */
if($_POST['function'] == 'get_course_enrollments') {

    $data = sanitize_data($_POST);

    $allenrolments = get_users_enrolled_in_course($data['cid'],5);

    $options = [];

    $options[] = '<option value="">Select Student</option>';
    foreach($allenrolments as $value) {
        $options[] = '<option value="'.$value->userid.'">'.$value->username.'</option>';
    }

    $optionsstr = implode('',$options);
    echo json_encode($optionsstr);
    
}


// Individual Dash View POST
if($_POST['function'] == 'individual_dash_view') {

    $data = sanitize_data($_POST);

    $response = [];

    // Get Employe Info 
    
    $userdata = get_user_with_extrafeilds($data['uid']);

    $userinfo = [];
    $userinfo['username'] = $userdata->firstname . " " . $userdata->lastname;
    $userinfo['designation'] = $userdata->designation;
    $userinfo['team'] = $userdata->team;
    $userinfo['location'] = $userdata->city;
    $userinfo['department'] = $userdata->department;
    $userinfo['manager'] = $userdata->manager;

    $response['userinfo'] = $userinfo;

    // Marks Overview

    // Section wise marks overview

    // Individual and team averages

    // Questions overview

    // QUiz Comparison

    echo json_encode($response);

}