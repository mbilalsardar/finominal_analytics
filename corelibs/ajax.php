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
 * @license     Private
 */

require_once dirname(__FILE__) . '/../../../config.php'; // Creates $PAGE.
require_once dirname(__FILE__) . '/../../../mod/quiz/locallib.php';
require_once dirname(__FILE__) . '/../../../mod/quiz/addrandomform.php';
require_once dirname(__FILE__) . '/../../../question/editlib.php';

require_once dirname(__FILE__) . '/lib.php';

global $USER;

/* Course Drop Down */
if($_POST['function'] == 'get_all_courses') {

    $data = sanitize_data($_POST);


    $userroles = get_user_role($USER->id);
    $options = [];
    $options[] = '<option value="">Select Course</option>';

    if(is_siteadmin() || $userroles->role=='manager') {
        // get all course
        $allcourses = get_all_courses();
        foreach($allcourses as $value) {
            $options[] = '<option value="'.$value->id.'">'.$value->fullname.'</option>';
        }
    }
    else if($userroles->role == 'student') {
        $allcourses = stud_get_enrolled_courses($USER->id);
        foreach($allcourses as $value) {
            $options[] = '<option value="'.$value->cid.'">'.$value->course.'</option>';
        }
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

/* Student Drop Down */
if($_POST['function'] == 'get_team_in_course') {

    $data = sanitize_data($_POST);

    $allenrolments = get_users_enrolled_in_course($data['cid'],5);

    $options = [];
    $options[] = '<option value="">Select Team</option>';

    $onlyteamarray = [];

    foreach($allenrolments as $key=>$value) {
        if(!array_key_exists($value->teamid ,$onlyteamarray)){
            $onlyteamarray[$value->teamid] = $value->team;
            $options[] = '<option value="'.$value->teamid.'">'.$value->team.'</option>';
        }
    }

    $optionsstr = implode('',$options);
    echo json_encode($optionsstr);    
}

/* Get Manager */
if($_POST['function'] == 'get_team_managers') {

    $data = sanitize_data($_POST);


    $allcohortmembers = get_cohort_memebers($data['teamid']);

    $uniquemanagersoptions = $uniquemanagers = $uniquedepartments = $uniquedepartmentsarr = $locationarr = $uniquelocation = [];
    $uniquedesig = $uniquedesigarr = [];
    $uniquemanagersoptions[] = "<option value=''>All</option>";
    $uniquedepartmentsarr[] = "<option value=''>All</option>";
    $locationarr[] = "<option value=''>All</option>";
    $uniquedesigarr[] = "<option value=''>All</option>";

    
    foreach($allcohortmembers as $value) {

        // Add Manager.
        if($value->manager != "") { 
            if (!array_key_exists($value->manager_email,$uniquemanagers)) {
                $uniquemanagers[$value->manager_email] = $value->manager;
                $uniquemanagersoptions[] = "<option value='".$value->manager_email."'>".$value->manager."</option>";
            }
        }

        // Add Department
        if($value->department != "") {
            if(!in_array($value->department,$uniquedepartments)) {
                $uniquedepartments[] = $value->department;
                $uniquedepartmentsarr[] = "<option value='".$value->department."'>".$value->department."</option>";
            }
        }

        // Location
        if($value->city != "") {
            if(!in_array($value->city,$uniquelocation)) {
                $uniquelocation[] = $value->city;
                $locationarr[] = "<option value='".$value->city."'>".$value->city."</option>";
            }
        }

        // Designation
        if($value->designation != "") {
            if(!in_array($value->designation,$uniquedesig)) {
                $uniquedesig[] = $value->designation;
                $uniquedesigarr[] = "<option value='".$value->designation."'>".$value->designation."</option>";
            }
        }
    }
    

    $optionsstr = implode('',$uniquemanagersoptions);
    $optionsstrdepart = implode('',$uniquedepartmentsarr);
    $optionsstrlocation = implode('',$locationarr);
    $uniquedesigstr = implode('',$uniquedesigarr);

    $response = [];
    $response['manager'] = $optionsstr;
    $response['department'] = $optionsstrdepart;
    $response['location'] = $optionsstrlocation;
    $response['designation'] = $uniquedesigstr;

    echo json_encode($response);    
}

/* Individual Dash View POST */
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
    // $certificate = 'Not Issued';
    $certificate = 'Failed';
    if(!empty($quizmarksinfo)) { 
        foreach($quizmarksinfo as $value) {
            $quiz_marks['total'] = [round($value->total_grade,2)];
            $quiz_marks['obtained'] = [round($value->obtained_grade,2)];
            $quiz_marks['percentage'] = [round(($value->obtained_grade / $value->total_grade) * 100,1)];

            if ($value->passinggrade != '') { 
                if(($value->obtained_grade >= $value->passinggrade)) {
                    // $certificate = 'Issued';
                    $certificate = 'Passed';
                }
            }
        }
    }
    else {
        $quiz_marks['total'] = [0];
        $quiz_marks['obtained'] = [0];
        $quiz_marks['percentage'] = [0];
    }
    $response['quiz_marks'] = $quiz_marks;
    $response['quiz_certificate'] = $certificate;

 
    
    // Section wise marks overview

    $quizsections = course_quiz_sections($cid,$qid);
    $allcorrect = $allwrong = $allgaveup = $ttlsectionquestion = $sectionpercentage = $allquestion = 0;



    // ---------- Getting all sections -----------------------
    $allSectionsArray = [];
    if(!empty($quizsections)) { 
        foreach ($quizsections as $quizseckey => $quizsecvalue) {
            if(empty($quizsecvalue)) { continue; }
            if(!array_key_exists($quizsecvalue->section_id,$allSectionsArray))  { 
                $allSectionsArray[$quizsecvalue->section_id] = $quizsecvalue->section_name;
            }
        }
    }



    foreach ($allSectionsArray as $quizseckey => $quizsecvalue) {

        $sectiontotal = 0;
        $sectionname = $quizsecvalue;
        $sectionid = $quizseckey;

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
        
        if(!empty($value)) { 
            $sectionname = $key;
            $final_indivual_section_labels[] = $sectionname;

            $a = array_filter($value);
            $allteamaverage = round(array_sum($value)/count($value),2);
            
            $totalsections_alluser_averages[] = $allteamaverage;


            $usertotal = array_shift($section_currentuser_precentage[$key]);
            $totalsections_selecteduser_averages[] = $usertotal;
        }
        else {
            $totalsections_alluser_averages[] =  0;
            $totalsections_selecteduser_averages[] = 0;
        }
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

    // $final_indivual_section_series = [ 
    //     [ 
    //         "name" =>"Team",
    //         "data" => [10,20,30]
    //     ],
    //     [
    //         "name" => "User",
    //         "data" => [10,20,60]
    //     ]
    // ];


    $response['indi_team_averages_label'] = $final_indivual_section_labels;
    $response['indi_team_averages_series'] = $final_indivual_section_series;
    // $response['test'] = $section_users_precentage;
    
    
    echo json_encode($response);

}

// Team Dash view 
if($_POST['function'] == 'team_dash_view') {

    $data = sanitize_data($_POST);
    $cid = $data['cid'];
    $qid = $data['qid'];
    $teamid = $data['teamid'];
    $manageremail = $data['manageremail'];
    $designation = $data['designation'];
    $department = $data['department'];
    $location = $data['location'];


    // Get array of all users in team first.
    $allteamusers = get_users_by_filters($teamid,$manageremail,$designation,$location,$department);

    // getting only users enrolled in course 
    $allcourseenrollments = get_users_enrolled_in_course($cid,5);

    $allenrolledusers = [];

    foreach($allteamusers as $key=>$value) {
        
        foreach($allcourseenrollments as $enrolkey=>$enrolval)
        {
            if((int)$value->id == (int)$enrolval->userid) { 
                $allenrolledusers[] = (int)$value->id; 
                break;
            }
        }
    }

    /* Quiz Participation */

    $particpated = 0;
    $notparticipated = 0;

    foreach($allenrolledusers as $value) {
        
        $attempt = check_if_quiz_attempted($cid,$qid,$value);

        if($attempt) { $particpated++; }
        else { $notparticipated++; }

    }

    $response['quizparticipated'] = $particpated;
    $response['quiznotparticipated'] = $notparticipated;
    $response['quizparticipatedpercent'] = round(($particpated / count($allenrolledusers)) * 100,1);


    /*  SECTION PERFORMANCE */ 
    $quizsections = course_quiz_sections($cid,$qid);
    $allcorrect = $allwrong = $allgaveup = $ttlsectionquestion = $sectionpercentage = $allquestion = 0;
    $sectionaveragemarks = [];

    // ---------- Getting all sections -----------------------
    $allSectionsArray = [];
    if(!empty($quizsections)) { 
        foreach ($quizsections as $quizseckey => $quizsecvalue) {
            if(empty($quizsecvalue)) { continue; }
            if(!array_key_exists($quizsecvalue->section_id,$allSectionsArray))  { 
                $allSectionsArray[$quizsecvalue->section_id] = $quizsecvalue->section_name;
            }
        }
    }

    $allquestion = count($quizsections);
    foreach ($allSectionsArray as $quizseckey => $quizsecvalue) {

        $sectiontotal = [];
        $sectionname = $quizsecvalue;
        $sectionid = $quizseckey;
        $labels[] = $sectionname;
   
        foreach($allenrolledusers as $user) { 
            $secresult = quiz_sections_result($qid, $sectionid, $user, $cid);
            $percentagesectiontotal = $secresult['percentage'];
            $allcorrect += $secresult['total_correct'];
            $allwrong += $secresult['total_wrong'];
            $allgaveup += $secresult['total_gaveup'];
            $sectiontotal[] = $percentagesectiontotal;
        }
        
        $series[] = round(array_sum($sectiontotal)/count($sectiontotal),2);

        /* Section Averages */
        $sectionaveragemarks[] = [
            'x' => $sectionname,
            'y' => round(array_sum($sectiontotal)/count($sectiontotal),2),
        ];

    }

    $response['section_performance_labels'] = $labels;
    $response['section_performance_series'] = $series;

    /* Question Overview  */
    $response['question_overview_labels'] = ['Right','Wrong','Gave Up'];
    $response['question_overview_series'] = [$allcorrect, $allwrong, $allgaveup];
    $response['ttlrightquest'] = $allcorrect;
    $response['ttlwrongquest'] = $allwrong;
    $response['ttlgaveupquest'] = $allgaveup;

    /*  Total Questions */
    $response['ttlquestions'] = $allquestion;
    /*  Team Sections */
    $response['ttlsections'] = count($labels);
    /* Total Team Members */
    $response['ttlparticipants'] = count($allenrolledusers);

    /* Average  */
    $response['sectionaveragemarks'] = $sectionaveragemarks;

    /*  Certification Overview - pass / fail */
    $allquizmarks = [];
    $allmarkswithuser = [];
    $total_certificates = 0;
    $total_pass = 0;
    $total_fail = 0;

    foreach($allenrolledusers as $user) {
        $quizmarksinfo = quiz_grades($qid,$cid,$user);
        
        if(!empty($quizmarksinfo)){ 
            foreach($quizmarksinfo as $value) {
                $allmarkswithuser[$value->fullname] = $value->obtained_grade;
                $allquizmarks[] = $value->obtained_grade;
                if($value->obtained_grade >= $value->passinggrade) {
                    $total_pass++;
                }
                else {
                    $total_fail++;
                }
            }
        }
        else {
            $total_fail++;
        }
    }

    $response['totalpass'] = $total_pass;
    $response['totalfail'] = $total_fail;
    $response['certificate_series'] = [$total_pass,$total_fail];


    /*  Marks Summary  */
    sort($allquizmarks);    
    $minmarks = $allquizmarks[0];
    $avgmarks = round(array_sum($allquizmarks) / count($allquizmarks),2);
    $maxmarks = $allquizmarks[count($allquizmarks)-1];

    $markssummary = [
        [
            'x'=>'Minimum',
            'y'=>$minmarks,
        ],
        [
            'x'=>'Average',
            'y'=>$avgmarks,
        ],
        [
            'x'=>'Maximum',
            'y'=>$maxmarks,
        ],  
    ];
    $response['marks_summary'] = $markssummary;


    /* Top Performers */
    arsort($allmarkswithuser);
    $topperformers=[];
    $count = 0;
    foreach($allmarkswithuser as $key=>$value){

        if($count >= 5) {break;}

        $temp=[
            'x'=>$key,
            'y'=>$value,
        ];

        $topperformers[] = $temp;
        $count++;
    }

    $response['top_performer'] = $topperformers;

    echo json_encode($response);

}