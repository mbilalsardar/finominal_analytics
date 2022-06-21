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
 * @copyright   2021 onwards 3i Logic (Private) Limited (http://www.3ilogic.com)
 * @license     Private
 */

require_once dirname(__FILE__) . '/../../../config.php'; // Creates $PAGE.
require_once $CFG->dirroot . '/grade/lib.php';
require_once $CFG->dirroot . '/grade/querylib.php';



/**
 * dd
 *
 * @param  mixed $a
 * @param  boolean $exit
 * @return void
 * 
 * used for printing logs
 */
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
 * get_user_role
 *
 * @param  int $uid
 * @return array 
 */
function get_user_role($uid) {

    global $DB;

    $query = "SELECT
    u.id,
    u.username,
    r.shortname AS 'role',
    CASE ctx.contextlevel 
      WHEN 10 THEN 'system'
      WHEN 20 THEN 'personal'
      WHEN 30 THEN 'user'
      WHEN 40 THEN 'course_category'
      WHEN 50 THEN 'course'
      WHEN 60 THEN 'group'
      WHEN 70 THEN 'course_module'
      WHEN 80 THEN 'block'
     ELSE CONCAT('unknown context: ',ctx.contextlevel)
    END AS 'context_level',
    ctx.instanceid AS 'context_instance_id'
    FROM mdl_role_assignments ra
    JOIN mdl_user u ON u.id = ra.userid
    JOIN mdl_role r ON r.id = ra.roleid
    JOIN mdl_context ctx ON ctx.id = ra.contextid
    WHERE u.id=?
    GROUP BY u.id
    ORDER BY u.username ";

    $result=$DB->get_record_sql($query,[$uid]);

    return $result;
}


/**
 * get_all_courses
 * 
 * this function just resutls all visible courses
 * 
 * @return array $allRecords
 */
function get_all_courses()
{
    global $DB;
    $query = "SELECT id,fullname FROM mdl_course WHERE visible=1 AND category>0";
    $allRecords = $DB->get_records_sql($query);
    return $allRecords;
}



/**
 * get_course_quiz
 * 
 * This function is used to get all quizes in course.
 *
 * @param  int $cid Course Id
 * @return array $allRecords Returns array of all quizes in course    
 */
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
 * 
 * get_users_enrolled_in_course
 *
 * @param int $courseid course id
 * @param int $usertype role type, 5 for student, 3 for teacher
 * @param mixed $range this is for slecting enrollments between specific dates
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
            ch.id AS teamid,
            ch.name AS team,
            cat.name AS categoryname
        FROM mdl_course AS c 
        JOIN mdl_course_categories AS cat ON c.category=cat.id
        LEFT JOIN mdl_context AS ctx ON c.id = ctx.instanceid
        JOIN mdl_role_assignments  AS lra ON lra.contextid = ctx.id
        JOIN mdl_enrol AS en ON en.courseid = c.id
        JOIN mdl_user_enrolments mue ON mue.enrolid = en.id 
        JOIN mdl_user AS u ON lra.userid = u.id
        JOIN mdl_cohort_members AS chm ON chm.userid = u.id
        JOIN mdl_cohort AS ch ON chm.cohortid = ch.id
        WHERE lra.roleid=?";


    if ('' != $courseid) {
        $query .= " AND c.id='{$courseid}'";
    }

    if ($range != '') {
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
    cohort.name AS 'team',
    ui_pno.data AS 'pno',
    ui_department.data AS 'department',
    ui_designation.data AS 'designation',
    ui_manager.data AS 'manager',
    ui_manager_email.data AS 'manager_email'
    FROM mdl_user u
    LEFT JOIN mdl_user_info_data ui_pno ON (ui_pno.userid = u.id AND ui_pno.fieldid = 1)
    LEFT JOIN mdl_user_info_data ui_designation ON (ui_designation.userid = u.id AND ui_designation.fieldid = 2)
    LEFT JOIN mdl_user_info_data ui_department ON (ui_department.userid = u.id AND ui_department.fieldid = 3)
    LEFT JOIN mdl_user_info_data ui_manager ON (ui_manager.userid = u.id AND ui_manager.fieldid = 4)
    LEFT JOIN mdl_user_info_data ui_manager_email ON (ui_manager_email.userid = u.id AND ui_manager_email.fieldid = 5)
    LEFT JOIN mdl_cohort_members cohortmem ON cohortmem.userid = u.id 
    LEFT JOIN mdl_cohort cohort ON cohort.id = cohortmem.cohortid
    WHERE u.id=?
    ";

    $result = $DB->get_record_sql($query, [$userid]);

    return $result;
}


/**
 * quiz_grades
 *
 * Fetch Quiz grades by course for all or perticular user.
 * 
 * @param  int $qid
 * @param  int $cid
 * @param  int $uid if uid id -1 then all users data will be fetched.
 * @return array $result returns mixed array of quiz grade records
 */
function quiz_grades($qid,$cid,$uid=-1) {

    global $DB;

    $query = "SELECT
        u.id AS 'uid',
        CONCAT(u.firstname,' ',u.lastname) AS 'fullname',
        q.grade AS 'total_grade',
        gi.gradepass AS 'passinggrade',
        Format(qg.grade,2) AS 'obtained_grade'
        FROM mdl_user u
        LEFT JOIN mdl_quiz_grades qg ON (qg.userid = u.id)
        LEFT JOIN mdl_quiz q ON (qg.quiz = q.id AND q.course=?)
        LEFT JOIN mdl_grade_items gi ON (gi.courseid=? AND gi.iteminstance=q.id)
        WHERE q.id =?
        
    ";

    $params = [$cid,$cid,$qid];

    if($uid != -1) {
        $query .= " AND u.id=?";
        $params[] = $uid;
    }
    
    $query .= " ORDER BY gi.id  DESC LIMIT 1";
    $result = $DB->get_records_sql($query,$params);

    return $result;
}


function course_quiz_grades($uid) {

    global $DB;

    $query = "SELECT 
    q.id AS 'quizid',
    qg.userid AS 'userid',
    q.name as 'quizname',
    c.fullname as 'coursename',
    q.grade AS 'total_grade',
    CONCAT(Format(qg.grade,2),'%') AS 'obtained_grade'
    FROM mdl_quiz q
    INNER JOIN mdl_quiz_grades qg ON qg.quiz = q.id
    INNER JOIN mdl_course c ON c.id = q.course
    WHERE qg.userid =?
    ";
    $result = $DB->get_records_sql($query,[$uid]);

    return $result;

}
    

function course_quiz_sections($courseid, $quizid)
{
    global $DB;

    $query = "SELECT
    CONCAT(quiza.id,qa.slot,u.id) AS unique_id,
    que.id AS questionid,
    concat( u.firstname,' ', u.lastname ) AS student_name,
    u.id AS userid,
    quiza.userid AS quiz_userid,
    q.course,
    q.name,
    quiza.attempt,
    qa.slot,
    mqc.id 'section_id',
    mqc.name 'section_name',
    que.questiontext AS question,
    qa.rightanswer AS correct_answer,
    qa.responsesummary AS student_answer
    FROM mdl_quiz_attempts quiza
    JOIN mdl_quiz q ON q.id=quiza.quiz
    LEFT JOIN mdl_question_usages qu ON qu.id = quiza.uniqueid
    LEFT JOIN mdl_question_attempts qa ON qa.questionusageid = qu.id
    LEFT JOIN mdl_question que ON que.id = qa.questionid
	JOIN mdl_question_versions mqv on mqv.questionid = que.id 
	JOIN mdl_question_bank_entries mqbe on mqbe.id = mqv.questionbankentryid  
	JOIN mdl_question_categories mqc on mqbe.questioncategoryid = mqc.id 
    LEFT JOIN mdl_user u ON u.id = quiza.userid
    WHERE q.id = ?
    AND q.course = ?
    ORDER BY quiza.userid, quiza.attempt, qa.slot";

    $result = $DB->get_records_sql($query, [$quizid, $courseid]);
    return $result;

}


function quiz_section_question_attempts_by_user($qid, $secid, $userid, $courseid)
{
    global $DB;

    
    $query = "SELECT
    CONCAT(que.id,u.id) as uniqueid,
    que.id AS questionid,
    concat( u.firstname,' ', u.lastname ) AS student_name,
    u.id AS userid,
    quiza.userid AS quiz_userid,
    q.course,
    q.name,
    quiza.attempt,
    qa.slot,
    que.questiontext AS question,
    qa.rightanswer AS correct_answer,
    qa.responsesummary AS student_answer
    FROM mdl_quiz_attempts quiza
    JOIN mdl_quiz q ON q.id=quiza.quiz
    LEFT JOIN mdl_question_usages qu ON qu.id = quiza.uniqueid
    LEFT JOIN mdl_question_attempts qa ON qa.questionusageid = qu.id
    LEFT JOIN mdl_question que ON que.id = qa.questionid
	JOIN mdl_question_versions mqv on mqv.questionid = que.id 
	JOIN mdl_question_bank_entries mqbe on mqbe.id = mqv.questionbankentryid  
	JOIN mdl_question_categories mqc on mqbe.questioncategoryid = mqc.id 
    LEFT JOIN mdl_user u ON u.id = quiza.userid
    WHERE q.id = ?
    AND mqc.id = ?
    AND u.id = ?
    AND q.course = ?
    ORDER BY quiza.userid, quiza.attempt, qa.slot";

    $result = $DB->get_records_sql($query, [$qid, $secid, $userid, $courseid]);
    return $result;
}


function quiz_sections_result($quizid, $sectionid, $studentid, $courseid)
{
    $maindataarr = [];

    // foreach ($quizsections as $sections) {
    $temparr = []; // Saving Quiz Sections Grades data.
    $grades = quiz_section_question_attempts_by_user(
        $quizid,
        $sectionid,
        $studentid,
        $courseid,        
    );

    // [questionid] => 2,
    // [student name] => Joan Bower,
    // [userid] => 29,
    // [quiz_userid] => 29,
    // [course] => 10,
    // [name] => Quiz 1,
    // [attempt] => 1,
    // [slot] => 2,
    // [question] => Car has 4 wheels,
    // [correct_answer] => True,
    // [student_answer] => True

    $allcorrect = $allwrong = $allgaveup = $ttlsectionquestion = $sectionpercentage = 0;

    if (!empty($grades)) {
        $ttlsectionquestion = count($grades);
        foreach ($grades as $gradedata) {
            // Table data
            $currentquiz['name'] = $gradedata->name;
            $profiledata['username'] = $gradedata->student_name;
            $profiledata['userid'] = $gradedata->userid;

            if($gradedata->student_answer != "") { 
                if ($gradedata->correct_answer == $gradedata->student_answer) {
                    $allcorrect++;
                } else {
                    $allwrong++;
                }
            }
            else {
                $allgaveup++;
            }
        }
        $sectionpercentage = round(($allcorrect / $ttlsectionquestion) * 100, 2);
    }
    

    // $temparr['quiz_name'] = $sections->quiz_name;
    // $temparr['section_name'] = $sections->section_name;
    $temparr['total_questions'] = $ttlsectionquestion;
    $temparr['total_correct'] = $allcorrect;
    $temparr['total_wrong'] = $allwrong;
    $temparr['total_gaveup'] = $allgaveup;
    $temparr['percentage'] = $sectionpercentage;

    // $maindataarr[$sections->section_name] = $temparr;
    // }

    return $temparr;
}


function get_cohort_memebers($cohortid) {

    global $DB;

    $query = "SELECT u.id,
    u.firstname,
    u.lastname,
    u.email,
    u.city,
    cohort.name AS 'team',
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
    LEFT JOIN mdl_cohort_members cohortmem ON cohortmem.userid = u.id 
    LEFT JOIN mdl_cohort cohort ON cohort.id = cohortmem.cohortid
    WHERE cohort.id=?
    ";

    $result = $DB->get_records_sql($query,[$cohortid]);
    return $result;

}


/**
 * get_users_by_filters
 *
 * This function is mainly for implementing filters in team dashboard. by default it fetches all users of cohort in team id variable.
 * 
 * @param  int $teamid
 * @param  string $manageremail
 * @param  string $designation
 * @param  string $location
 * @param  string $department
 * @return array $result 
 */
function get_users_by_filters($teamid, $manageremail='', $designation='', $location='', $department='') {
    global $DB;

    $query = "SELECT u.id,
    u.firstname,
    u.lastname,
    u.email,
    u.city,
    cohort.name AS 'team',
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
    LEFT JOIN mdl_cohort_members cohortmem ON cohortmem.userid = u.id 
    LEFT JOIN mdl_cohort cohort ON cohort.id = cohortmem.cohortid
    WHERE cohort.id=?
    ";

    // WHERE cohort.id=2 
    // AND ui_designation.data = "Software Developer" 
    // AND u.city='Karachi' 
    // AND ui_department.data='IT' 
    // AND ui_manager_email.data='bilal@3ilogic.com'

    $params = [];
    $params[] = $teamid;

    if($manageremail!='') {
      $query .= " AND ui_manager_email.data=?";
      $params[] = $manageremail;
    }

    if($designation != '') {
        $query .= " AND ui_designation.data = ?";
        $params[] = $designation;
    }

    if($location != '') {
        $query .= " AND u.city=?"; 
        $params[] = $location;
    }

    if($department != '') {
        $query .= " AND ui_department.data=?"; 
        $params[] = $department;
    }

    $result = $DB->get_records_sql($query,$params);
    return $result;
}


/**
 * check_if_quiz_attempted
 * 
 * @param  int $cid
 * @param  int $qid
 * @param  int $uid
 * @return boolean true/false 
 */
function check_if_quiz_attempted($cid,$qid,$uid) {

    global $DB;

    /* Get all Attempts  */
    $query = "SELECT 
    qa.userid,
    q.id,
    q.name AS 'quiz',
    CONCAT(u.firstname,' ',u.lastname) AS 'fullname'
    FROM mdl_quiz_attempts AS qa
    JOIN mdl_quiz AS q ON qa.quiz = q.id
    JOIN mdl_course c ON q.course = c.id
    JOIN mdl_user u ON u.id=qa.userid
    WHERE c.id =? AND q.id=? AND u.id=?";

    $params = [$cid,$qid,$uid];

    if($DB->record_exists_sql($query,$params)) {
        return true;
    }
    else { return false; }
    
}



function stud_get_enrolled_courses($student_id)
{
    global $DB;

    $query = 'SELECT
    course.id AS cid,
    user2.firstname AS Firstname,
    user2.lastname AS Lastname,
    user2.email AS Email,
    user2.city AS City,
    course.fullname AS Course,
    cat.id AS catid,
    cat.name AS categoryname,
    cat.path AS allpath
    ,(SELECT shortname FROM mdl_role WHERE id=en.roleid) AS ROLE
    ,(SELECT name FROM mdl_role WHERE id=en.roleid) AS RoleName
    FROM mdl_course AS course
    JOIN mdl_course_categories AS cat ON course.category = cat.id
    JOIN mdl_enrol AS en ON en.courseid = course.id
    JOIN mdl_user_enrolments AS ue ON ue.enrolid = en.id
    JOIN mdl_user AS user2 ON ue.userid = user2.id
    WHERE user2.id = ? AND course.visible=1 AND en.status=0 AND user2.suspended=0';

    $resexist = $DB->record_exists_sql($query, [$student_id]);
    if ($resexist) {
        $res = $DB->get_records_sql($query, [$student_id]);

        return $res;
    }

    return 0;
}



function get_sub_managers_for_main_manager($firstlinemanageremail, $team = '')
{

    global $DB;

    $query = "SELECT u.id,
    u.firstname,
    u.lastname,
    u.email,
    u.city,
    uidp.data AS 'pno',
    uidt.data AS 'team',
    uidd.data AS 'designation',
    u_mangeremail.data AS '1_manager_email',
    u_2_linemanager_email.data AS '2_manager_email',
    u_3_linemanager_email.data AS '3_manager_email'
    FROM mdl_user u
    LEFT JOIN mdl_user_info_data uidt ON (uidt.userid = u.id AND uidt.fieldid = 4)
    LEFT JOIN mdl_user_info_data uidp ON (uidp.userid = u.id AND uidp.fieldid = 1)
    LEFT JOIN mdl_user_info_data uidd ON (uidd.userid = u.id AND uidd.fieldid = 13)
    LEFT JOIN mdl_user_info_data u_mangeremail ON (u_mangeremail.userid = u.id AND u_mangeremail.fieldid = 5)
    LEFT JOIN mdl_user_info_data u_2_linemanager_email ON (u_2_linemanager_email.userid = u.id AND u_2_linemanager_email.fieldid = 8)
    LEFT JOIN mdl_user_info_data u_3_linemanager_email ON (u_3_linemanager_email.userid = u.id AND u_3_linemanager_email.fieldid = 11)
    LEFT JOIN mdl_user_info_data u_buisnessunit ON (u_buisnessunit.userid = u.id AND u_buisnessunit.fieldid = 9)
    WHERE u_mangeremail.data=? AND u.suspended=0
    ";

    if ($team != '') {
        $query .= " AND uidt.data='$team'";
    }

    $result = $DB->get_records_sql($query, [strtoupper($firstlinemanageremail)]);

    return $result;
}

