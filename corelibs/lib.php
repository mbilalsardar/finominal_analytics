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
 * Library.
 *
 * @category    blocks
 * @author      Bilal Sardar (bilal@3ilogic.com)
 * @copyright   2020 onwards 3i Logic (Private) Limited (http://www.3ilogic.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once dirname(__FILE__) . '/../../../config.php'; // Creates $PAGE.
require_once $CFG->dirroot . '/grade/lib.php';
require_once $CFG->dirroot . '/grade/querylib.php';


/* utility Func */
function dd($a, $exit)
{
    echo '<pre>';
    print_r($a);
    echo '</pre>';

    if (true == $exit) {
        exit();
    }
}

/**
 * sanitize_data
 *
 * @param  array $data post data from ajax
 * @return array $data return sanitized array
 */
function sanitize_data($data)
{
    if (is_array($data)) {
        $data = array_map("trim", $data);
        $data = array_map("strip_tags", $data);
        $data = array_map("stripslashes", $data);
        $data = array_map("htmlspecialchars", $data);
        return $data;
    } else {
        $data = trim($data);
        $data = strip_tags($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}



/**
 * get_all_courses
 *
 * @param  int $catid
 * @return void
 */
function get_all_courses()
{
    global $DB;

    $query = "SELECT id,fullname FROM mdl_course WHERE visible=1";

    $allRecords = $DB->get_records_sql($query);

    return $allRecords;
}



function get_course_quiz($cid)
{
    global $DB;

    $query = "SELECT 
    q.id AS 'qid',
    q.name AS 'quizname',
    c.id AS 'courseid',
    c.fullname AS 'coursename'
    FROM mdl_quiz q
    INNER JOIN mdl_course c on c.id=q.course 
    WHERE c.id = ?";

    $allRecords = $DB->get_records_sql($query,[$cid]);

    return $allRecords;
}



/**
 * get_users_enrolled_in_course
 *
 * @param  int $courseid course id
 * @param  int $usertype role type, 5 for student, 3 for teacher
 * @param  mixed $range    this is for slecting enrollments between specific dates
 * @return array $res returns a user array 
 */
function get_users_enrolled_in_course($courseid, $usertype, $range = '')
{
    global $DB;

    if ('' == $courseid && '' == $usertype) {
        return 0;
    }

    $query = "SELECT
            DISTINCT CONCAT(u.id,'',c.id) AS customid,
            u.id AS userid, 
            CONCAT(u.firstname,' ',u.lastname) AS username,
            c.id AS courseid ,
            c.fullname AS coursename,
            cat.id AS categoryid,
            cat.name AS categoryname
        FROM mdl_course AS c 
        JOIN mdl_course_categories AS cat ON c.category=cat.id
        LEFT JOIN mdl_context AS ctx ON c.id = ctx.instanceid
        JOIN mdl_role_assignments  AS lra ON lra.contextid = ctx.id
        JOIN mdl_enrol AS en ON en.courseid = c.id
        JOIN mdl_user_enrolments mue ON mue.enrolid = en.id 
        JOIN mdl_user AS u ON lra.userid = u.id
        WHERE lra.roleid=?";


    if ('' != $courseid) {
        $query .= " AND c.id='{$courseid}'";
    }

    if ($range !== '') {
        $query .= " AND mue.timecreated " . $range;
    }

    $query .= ' ORDER BY u.id,cat.id';

    $res = $DB->get_records_sql($query, [$usertype]);

    return $res;
}



/**
 * get_user_with_extrafeilds
 *
 * @param  mixed $userid
 * @return array $result returns user data with extraprofile fields in array
 */
function get_user_with_extrafeilds($userid)
{

    /*
        1	pno
        2	1stline_manager
        4	team
        5	1stline_manager_email
        6	1stline_manager_basetown
        7	2ndline_manager
        8	2ndline_manager_email
        9	business_unit
        10	3rdline_manager
        11	3rdline_manager_email
        12	zone_of_responsibility
        13	designation

    */

    global $DB;

    $query = "SELECT u.id,
    u.firstname,
    u.lastname,
    u.email,
    u.city,
    ui_team.data AS 'team',
    ui_department.data AS 'department',
    ui_designation.data AS 'designation',
    ui_manager.data AS 'manager',
    ui_manager_email.data AS 'manager_email'
    FROM mdl_user u
    LEFT JOIN mdl_user_info_data ui_team ON (ui_team.userid = u.id AND ui_team.fieldid = 1)
    LEFT JOIN mdl_user_info_data ui_designation ON (ui_designation.userid = u.id AND ui_designation.fieldid = 2)
    LEFT JOIN mdl_user_info_data ui_department ON (ui_department.userid = u.id AND ui_department.fieldid = 3)
    LEFT JOIN mdl_user_info_data ui_manager ON (ui_manager.userid = u.id AND ui_manager.fieldid = 4)
    LEFT JOIN mdl_user_info_data ui_manager_email ON (ui_manager_email.userid = u.id AND ui_manager_email.fieldid = 5)
    WHERE u.id=?
    ";

    $result = $DB->get_record_sql($query, [$userid]);

    return $result;
}