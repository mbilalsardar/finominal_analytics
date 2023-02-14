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
 * Analytics Dashboard main class.
 *
 * @category    Blocks
 *
 * @author      Bilal Sardar (bilal@3ilogic.com)
 * @copyright   2021 onwards 3i Logic (Private) Limited (http://www.3ilogic.com)
 * @license     Private
 */

require_once dirname(__FILE__) . '/../../config.php'; // Creates $PAGE.
// require_once $CFG->libdir.'/adminlib.php';
// require_once $CFG->dirroot.'/user/filters/lib.php';
require_once 'corelibs/lib.php';

$context = context_system::instance();

require_login();

$linkurl = new moodle_url('/blocks/finominal_analytics/indi_dashboard.php');
$linkurlhome = new moodle_url('/my');

$PAGE->set_context($context);
$PAGE->set_url($linkurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_title('Individual Dashboard');

// $output = $PAGE->get_renderer('block_finominal_analytics');

// Set the page heading.
$PAGE->set_heading(get_string('individualdashboard', 'block_finominal_analytics'));
$PAGE->navbar->add('Dashboard', $linkurlhome);
$PAGE->navbar->add(get_string('individualdashboard', 'block_finominal_analytics'),$linkurl);
$PAGE->requires->jquery();


$baseurl = new moodle_url(basename(__FILE__));
$returnurl = $baseurl;

global $DB, $CFG, $USER;


$assetpath = $CFG->wwwroot . "/blocks/finominal_analytics/assets";
$ajaxurl = $CFG->wwwroot . "/blocks/finominal_analytics/corelibs/ajax.php";

// CHECK IF USER IS ADMIN OR MANAGER
    
$userrole = get_user_role($USER->id);

$usertype = $userrole->role;
$userid = $USER->id;

if($usertype == 'manager' || is_siteadmin()) {
    $home = $CFG->wwwroot . "/my";
    redirect($home, 'Please login as Student', 5);
    die();
}


$user = $DB->get_record('user', array('id' => $USER->id));
$picture = $OUTPUT->user_picture($user, array('size' => 150));

echo $OUTPUT->header();


/* Header Files */
echo <<<HTML
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{$assetpath}/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{$assetpath}/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{$assetpath}/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="{$assetpath}/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{$assetpath}/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{$assetpath}/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{$assetpath}/css/colors.css"> 
    <!-- <link rel="stylesheet" type="text/css" href="{$assetpath}/css/components.css">  -->
    <!-- <link rel="stylesheet" type="text/css" href="{$assetpath}/css/themes/dark-layout.css"> -->
    <link rel="stylesheet" type="text/css" href="{$assetpath}/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="{$assetpath}/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{$assetpath}/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="{$assetpath}/css/plugins/charts/chart-apex.min.css">
    <!-- END: Page CSS-->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>


    <style>
            .app-content {
                font-family: Verdana,Geneva,sans-serif !important;
            }
    </style>
HTML;


/* Code */
echo <<<HTML
    <div class="app-content content">
        <!-- <div class="content-wrapper">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Teacher Dashboard</h2>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="content-body">


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="course">Course</label>
                                        <select class="form-control" id="course">
                                            <option>Select Course</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="quiz">Quiz</label>
                                        <select class="form-control" id="quiz">
                                            <option>Select Quiz</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="student">Employee</label>
                                        <select class="form-control" id="student">
                                            <option>Select Student</option>
                                        </select>s
                                    </div>
                                </div> -->
                                <input value="{$userid}" id='userid_input_hidden' type='text' hidden='true'/>
                                <input value="{$usertype}" id='usertype_input_hidden' type='text'  hidden='true'/>
                              

                                <div class="col-lg-3 col-sm-12">
                                    <div class='mt-2'></div>
                                    <button id='view' class='btn btn-primary waves-effect waves-float waves-light'>View</button> 
                                    <button id='reset' class='btn btn-danger waves-effect waves-float waves-light'>Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  <!-- end Row 1 -->


            <!-- User Info Block -->
            <div class="row">
                 <!-- <div class="col-lg-4 col-sm-12">

                    <div class="card user-card" style="min-height:320px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Employee Details</h4>
                        </div>
                        
                        <div id='customuseravatar'> {$picture}  </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <i data-feather="user" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Username</span>
                                    <span class="float-right pr-2" id='userinfo_username'>-</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <i data-feather="check" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Designation</span>
                                    <span class="float-right pr-2" id='userinfo_designation' >-</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <i data-feather="star" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Team</span>
                                    <span class="float-right pr-2" id='userinfo_team'>-</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <i data-feather="flag" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Location</span>
                                    <span class="float-right pr-2" id='userinfo_location'>-</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <i data-feather="star" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Department</span>
                                    <span class="float-right pr-2" id='userinfo_department'>-</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <i data-feather="user" class="mr-1"></i>
                                    <span class="card-text user-info-title font-weight-bold mb-0">Manager</span>
                                    <span class="float-right pr-2" id='userinfo_manager'>-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="col-lg-4 col-sm-12">
                    <div class="card user-card" style="min-height:320px;">
                            
                        <div class="card-header d-flex justify-content-between align-items-center mb-2">
                            <h4 class="card-title">Employee Details</h4>
                        </div>

                        <div class="card-body">
                            <div class="user-avatar-section">
                                <div class="d-flex justify-content-start">
                                    <div id='customuseravatar'>
                                        $picture
                                    </div>
                                    <div class="d-flex flex-column ml-1">
                                        <div class="col-12">
                                            <table style="width:%; border:none">
                                                <tr>
                                                    <td colspan='2'><h4 class="mb-0" id='userinfo_username'>Username</h4></td>
                                                </tr>
                                                <tr>
                                                    <td colspan='2'><span class="" id='userinfo_designation'>Designation</span></td>
                                                </tr>
                                                <tr>
                                                    <td colspan='2'>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>Team</td>
                                                    <td style="">:&nbsp; <span class="" id='userinfo_team'>-</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Location</td>
                                                    <td style="">:&nbsp; <span class="" id='userinfo_location'>-</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Department</td>
                                                    <td style="">:&nbsp; <span class="" id='userinfo_department'>-</span></td>
                                                </tr>
                                                <tr>
                                                    <td>Manager</td>
                                                    <td style="">:&nbsp; <span class="" id='userinfo_manager'>-</span></td>
                                                </tr>
                                            </table>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End User Info Block -->



                <!-- Marks Overview Card -->
                <div class="col-lg-4 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Marks Overview</h4>
                        </div>
                        <div class="card-body p-0">
                            <div id="marks-overview-chart"></div>
                            <div class="row border-top text-center mx-0">
                                <div class="col-6 border-right py-1">
                                    <p class="card-text text-muted mb-0">Total Marks</p>
                                    <h3 class="font-weight-bolder mb-0" id='marks_overview_totalmarks'>0</h3>
                                </div>
                                <div class="col-6 py-1">
                                    <p class="card-text text-muted mb-0">Obtained Marks</p>
                                    <h3 class="font-weight-bolder mb-0" id='marks_overview_obtainedmarks'>0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Marks Overview Card -->


                <!-- Total Marks Block -->
                <div class="col-lg-4 col-12">

                    <div class="row">
                        <div class="col-lg-12">    
                            <div class="card py-1 text-center mx-0" style='min-height:120px'>
                                <div class="" style='padding:20px; margin-top: 30px'> 
                                    <span> <i data-feather="award" 
                                    style='
                                    width: 50px; 
                                    height: 50px;
                                    background:#008ffb; 
                                    color:white;
                                    border-radius:100px;
                                    padding : 12px;
                                    display: float;
                                    float : left;
                                    '>
                                    </i>
                                    </span>
                                    <h4 class="mb-0">Status</h4>
                                    <h4 class="font-weight-bolder card-text mb-0" id='certificate_status_div'>-</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card py-1 text-center mx-0" style='min-height:140px;'>
                                <span> <i data-feather="hash" 
                                    style='
                                    width: 40px; 
                                    height: 40px;
                                    background:#feb019;
                                    color:white;
                                    border-radius:100px;
                                    padding : 8px;
                                    margin-bottom : 5px;
                                    '>
                                    </i>
                                </span>
                                <h4 class=''>Total Questions</h4>
                                <h4 class='font-weight-bolder' id='totalquestions_count_div'>0</h4>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card py-1 text-center mx-0" style='min-height:140px;'>
                                <span> <i data-feather="users" 
                                    style='
                                    width: 40px; 
                                    height: 40px;
                                    background:red; color:white;
                                    border-radius:100px;
                                    padding : 8px;
                                    margin-bottom : 5px;
                                    '>
                                    </i>
                                </span>
                                <h4 class=''>Team Members</h4>
                                <h4 class='font-weight-bolder' id='teammember_count_div'>0</h4>
                            </div>
                        </div>
                    </div>
                </div>

            
            </div> <!-- end row -->

            <div class="row">
                <!-- section wise marks donut Starts-->
                <!-- <div class="col-xl-6 col-12">
                    <div class="card">
                        <div class="card-header flex-column align-items-start">
                            <h4 class="card-title mb-75">Section Wise Marks Overview</h4>
                            <span class="card-subtitle text-muted">Quiz sections marks overview </span>
                        </div>
                        <div class="card-body">
                            <div id="section_marks_chart"></div>
                        </div>
                    </div>
                </div> -->
                <!-- Donut Chart Ends-->



                <!-- Section Overview Individual and Team Averages -->
                <div class="col-xl-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                            <div>
                                <p class="card-subtitle text-muted mb-25">Sections Overview</p>
                                <h4 class="card-title ">Individual and Team Averages</h4>
                            </div>
                            <!-- <div class="d-flex align-items-center mt-md-0 mt-1">
                                <i class="font-medium-2" data-feather="calendar"></i>
                                <input type="text" class="form-control flat-picker bg-transparent border-0 shadow-none" placeholder="YYYY-MM-DD" />
                            </div> -->
                        </div>
                        <div class="card-body">
                            <div id="individual_averages_chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Bar Chart Ends -->

            </div>


            <div class="row">


                <!-- quiz comparision chart -->
                <div class="col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Quiz Comparision</h4>
                        </div>
                        <div class="card-body p-0">
                            <div id='quizcomparision_chart'></div>
                        </div>
                    </div>
                </div>
                <!-- End quiz comparision chart -->



                <!-- Questions Overview Card -->
                <div class="col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Questions Overview</h4>
                        </div>
                        <div class="card-body p-0">
                            <div id="questions_overview_chart"></div>

                            <div class="row border-top text-center mx-0">
                                <div class="col-4 border-right py-1">
                                    <p class="card-text text-muted mb-0">Right</p>
                                    <h3 class="font-weight-bolder mb-0" id='q_overview_right'>0</h3>
                                </div>
                                <div class="col-4 border-right py-1">
                                    <p class="card-text text-muted mb-0">Wrong</p>
                                    <h3 class="font-weight-bolder mb-0" id='q_overview_wrong' >0</h3>
                                </div>
                                <div class="col-4 py-1">
                                    <p class="card-text text-muted mb-0">Gaveup</p>
                                    <h3 class="font-weight-bolder mb-0" id='q_overview_gaveup'>0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Questions Overview Card -->


                
            </div>
        </div> 
    </div>
HTML;




/* JS Files */
echo <<<HTML
    <!-- BEGIN: Page Vendor JS-->
    <script src="{$assetpath}/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="{$assetpath}/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="{$assetpath}/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    <script src="{$assetpath}/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>
    <script src="{$assetpath}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="{$assetpath}/vendors/js/charts/apexcharts.min.js"></script>

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
    </script>
    
    
    <script>
        AJAXURL = "{$ajaxurl}";
    </script>
    <!-- Dashboard js -->
    <script src="{$assetpath}/js/dashboards/individual_dash.js"></script>


    
    <!-- END: Page Vendor JS-->
HTML;

echo $OUTPUT->footer();
