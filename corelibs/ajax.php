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
    $cid = $data['cid'];
    $qid = $data['qid'];
    $uid = $data['uid'];


    $response = [];
    $totalquestionsinfo = [];

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




    // Marks Overview.  
    $quiz_marks = [];
    $quizmarksinfo = quiz_grades($qid,$cid,$uid);
    $certificate = 'Not Issued';
    if(!empty($quizmarksinfo)){ 
        foreach($quizmarksinfo as $value) {
            $quiz_marks['total'] = [round($value->total_grade,2)];
            $quiz_marks['obtained'] = [round($value->obtained_grade,2)];

            if($value->obtained_grade >= $value->passinggrade) {
                $certificate = 'Issued';
            }
        }
    }
    else {
        $quiz_marks['total'] = [0];
        $quiz_marks['obtained'] = [0];
    }
    $response['quiz_marks'] = $quiz_marks;
    $response['quiz_certificate'] = $certificate;

    


    // Section wise marks overview

    $quizsections = course_quiz_sections($cid,$qid);

    $allcorrect = $allwrong = $allgaveup = $ttlsectionquestion = $sectionpercentage = 0;

    foreach ($quizsections as $quizseckey => $quizsecvalue) {

        $sectiontotal = 0;
        $sectionname = $quizsecvalue->section_name;
        $sectionid = $quizsecvalue->section_id;

        $secresult = quiz_sections_result($qid, $sectionid, $uid, $cid);
        $sectiontotal = $secresult['percentage'];

        $labels[] = $sectionname;
        $series[] = $sectiontotal;

        $tempquestinfo = [];
       
        $allcorrect += $secresult['total_correct'];
        $allwrong += $secresult['total_wrong'];
        $allgaveup += $secresult['total_gaveup'];
        $allquestion += $secresult['total_questions'];

    }
    

    $response['section_grade_labels'] = $labels;
    $response['section_grade_series'] = $series;


    // For Questions Overvwer
    $totalquestionsinfo['questions_ttlcorrect'] = $allcorrect;
    $totalquestionsinfo['questions_ttlwrong'] = $allwrong;
    $totalquestionsinfo['questions_ttlgaveup'] = $allgaveup;
    $totalquestionsinfo['questions_total'] = $allquestion;

    $qoverviewlabels = ['Right', 'Wrong', 'Gave Up'];
    $qoverviewseries = [$allcorrect,$allwrong,$allgaveup];

    $response['quiz_questions_info'] = $totalquestionsinfo;
    $response['questions_overview_labels'] = $qoverviewlabels;
    $response['questions_overview_series'] = $qoverviewseries;


    // QUiz Comparison

    $allquizgrades = course_quiz_grades($uid);
    $allquizgradesdata = [];
    foreach($allquizgrades as $value) {
        
        $label = $value->quizname;
        $data = $value->obtained_grade;

        $temp = [
            'x'=>$label,
            'y'=>$data,
        ];
        $allquizgradesdata[] = $temp;
    }

    $response['allquiz_data'] = $allquizgradesdata;



   
    /*  
        Individual and team averages.
        Team is considered a cohort. cohort is the main team.   
        
    */


    $teammembers = 0;
    $course_enrollments = get_users_enrolled_in_course($cid,5);

    // total team count.
    foreach($course_enrollments as $value) { 
        if($userdata->team == $value->team) {
            $teammembers++;
        }
    }
    $response['totalteammembers'] = $teammembers;
  

    // Getting quiz sections
    $section_allusers_precentage = [];
    $section_currentuser_precentage = [];
  
    foreach ($quizsections as $quizseckey => $quizsecvalue) {

        $sectionname = $quizsecvalue->section_name;
        $sectionid = $quizsecvalue->section_id;
       

        $temp = [];
        $temp2 = [];
        foreach($course_enrollments as $value) {

            if($userdata->team == $value->team) {
             
                if($value->userid == $uid) { 
                    $secresult = quiz_sections_result($qid, $sectionid, $value->userid, $cid);
                    $sectiontotal = $secresult['percentage'];
                    $temp2[] = $sectiontotal;
                }
                else {
                    $secresult = quiz_sections_result($qid, $sectionid, $value->userid, $cid);
                    $sectiontotal = $secresult['percentage'];
                    $temp[] = $sectiontotal;
                }
            }
        }
        $section_users_precentage[$sectionname] = $temp;
        $section_currentuser_precentage[$sectionname] = $temp2;
    }




    // Summing all averages. 

    $final_indivual_section_series = [];
    $final_indivual_section_labels = [];

    $totalsections_alluser_averages = [];
    $totalsections_selecteduser_averages = [];

    foreach($section_users_precentage as $key=>$value) {

        $sectionname = $key;
        $final_indivual_section_labels[] = $sectionname;

        $a = array_filter($value);
        $allteamaverage = round(array_sum($value)/count($value),2);
        
        $totalsections_alluser_averages[] = $allteamaverage;


        $usertotal = array_shift($section_currentuser_precentage[$key]);
        $totalsections_selecteduser_averages[] = $usertotal;
    }


    $final_indivual_section_series = [ 
        [ 
            "name" =>"Team",
            "data" => $totalsections_alluser_averages
        ],
        [
            "name" => "User",
            "data" => $totalsections_selecteduser_averages
        ]
    ];

    $response['indi_team_averages_lable'] = $final_indivual_section_labels;
    $response['indi_team_averages_series'] = $final_indivual_section_series;

    /* End Individual team user avg comparison chart */

    
    // Certification
    echo json_encode($response);

}