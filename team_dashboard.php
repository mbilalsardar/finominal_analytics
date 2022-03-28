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
 * @category    block
 * @author      Bilal Sardar (bilal@3ilogic.com)
 * @copyright   2021 onwards 3i Logic (Private) Limited (http://www.3ilogic.com)
 * @license     Private
 */

require_once dirname(__FILE__) . '/../../config.php'; // Creates $PAGE.
// require_once $CFG->libdir.'/adminlib.php';
// require_once $CFG->dirroot.'/user/filters/lib.php';
// require_once 'lib.php';

$context = context_system::instance();

require_login();

$linkurl = new moodle_url('/blocks/finominal_analytics/team_dashboard.php');

$PAGE->set_context($context);
$PAGE->set_url($linkurl);
$PAGE->set_pagelayout('admin');
$PAGE->set_title('Team Dashboard');

// $output = $PAGE->get_renderer('block_finominal_analytics');

// Set the page heading.
$PAGE->set_heading(get_string('teamdashboard', 'block_finominal_analytics'));
$PAGE->navbar->add('Dashboard', $linkurl);
$PAGE->navbar->add(get_string('teamdashboard', 'block_finominal_analytics'));
$PAGE->requires->jquery();


$baseurl = new moodle_url(basename(__FILE__));
$returnurl = $baseurl;

global $DB, $CFG, $USER;
$userid = $USER->id;

echo $OUTPUT->header();

global $CFG;
$assetpath = $CFG->wwwroot . "/blocks/finominal_analytics/assets";

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
HTML;


/* Code */
echo <<<HTML
    <div class="app-content content">


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
                                            <option value=''>Select Course</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="quiz">Quiz</label>
                                        <select class="form-control" id="quiz">
                                            <option value='' >Select Quiz</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="team">Team</label>
                                        <select class="form-control" id="team">
                                            <option value=''>Select Team</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <select class="form-control" id="designation">
                                            <option value=''>All</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row"> 
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="manager">Manager</label>
                                        <select class="form-control" id="manager">
                                            <option value=''>All</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="department">Department</label>
                                        <select class="form-control" id="department">
                                            <option value=''>All</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <select class="form-control" id="location">
                                            <option value='' >All</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="col-lg-3 col-sm-12">
                                    <div class="row">
                                        <div class="col-6 mt-2">
                                            <button id='view' class='btn btn-primary btn-block waves-effect waves-float waves-light'>View</button>
                                        </div>

                                        <div class="col-6 mt-2">
                                            <button id='reset' class='btn btn-danger btn-block waves-effect waves-float waves-light'>Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End of Filter row -->

            <div class="row">
                <!-- Total Marks Block -->
                <div class="col-lg-3 col-12">

                    <div class="row">
                        <div class="col-lg-12">    
                            <div class="card py-1 text-center mx-0" style='min-height:120px'>
                                <div class="" style='padding:20px; margin-top: 15px'> 
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
                                    <h4 class="card-text mb-0" id='ttl_members_count'>0</h4>
                                    <h4 class="mb-0 font-weight-bolder ">Total Participants</h4>
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
                                <h4 id='totalquestions_count_div'>0</h4>
                                <h4 class='font-weight-bolder '>Total Questions</h4>
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
                                <h4 id='total_sections_div'>0</h4>
                                <h4 class='font-weight-bolder '>Total Sections</h4>
                            </div>
                        </div>
                    </div>
                </div> <!-- Info Box Column End -->

                <!-- Participation Overview Card -->
                <div class="col-lg-3  col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Quiz Participation</h4>
                        </div>
                        <div class="card-body p-0">
                            <div id="attendance-chart"></div>
                            <div class="row border-top text-center mx-0">
                                <div class="col-6 border-right py-1">
                                    <p class="card-text text-muted mb-0">Participated</p>
                                    <h3 class="font-weight-bolder mb-0" id='participated'>0</h3>
                                </div>
                                <div class="col-6 py-1">
                                    <p class="card-text text-muted mb-0">Not Participated</p>
                                    <h3 class="font-weight-bolder mb-0" id='notparticipated'>0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ End Participation Overview Card -->


                <!-- Certification Overview Card -->
                <div class="col-lg-3  col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Certification Overview</h4>
                        </div>
                        <div class="card-body p-0">
                            <div id="certification-chart"></div>
                            <div class="row border-top text-center mx-0">
                                <div class="col-6 border-right py-1">
                                    <p class="card-text text-muted mb-0">Issued</p>
                                    <h3 class="font-weight-bolder mb-0" id='ttlcertissued'>0</h3>
                                </div>
                                <div class="col-6 py-1">
                                    <p class="card-text text-muted mb-0">Not Issued</p>
                                    <h3 class="font-weight-bolder mb-0" id='ttlcertnotissued'>0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ End Certification Overview Card -->



                <!-- Certification Overview Card -->
                <div class="col-lg-3  col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Marks Summary</h4>
                        </div>
                        <div class="card-body">
                            <div id="marks_summary"></div>
                
                        </div>
                    </div>
                </div>
                <!--/ End Certification Overview Card -->






            </div> <!-- end row -->


            <div class="row">
                <!-- section wise marks donut Starts-->
                <div class="col-xl-6 col-12">
                    <div class="card">
                        <div class="card-header flex-column align-items-start">
                            <h4 class="card-title mb-75">Section Performance Overview</h4>
                            <!-- <span class="card-subtitle text-muted">Sections marks Averages </span> -->
                        </div>
                        <div class="card-body">
                            <div id="section_marks_chart"></div>
                        </div>
                    </div>
                </div>
                <!-- Donut Chart Ends-->
          
                <!-- Section Averages Marks Bar Chart -->
                <div class="col-xl-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                            <div>
                                <!-- <h4 class="card-title font-weight-bolder">Individual and Team Averages</h4> -->
                                <h4 class="card-title mb-75">Section's Average Marks</h4>
                            </div>
                            <!-- <div class="d-flex align-items-center mt-md-0 mt-1">
                                <i class="font-medium-2" data-feather="calendar"></i>
                                <input type="text" class="form-control flat-picker bg-transparent border-0 shadow-none" placeholder="YYYY-MM-DD" />
                            </div> -->
                        </div>
                        <div class="card-body">
                            <div id="section_average_marks_chart"></div>
                        </div>
                    </div>
                </div>
                <!-- End Bar Chart Ends -->
            </div>



            <div class="row">
                <!-- Top Performers -->
                <div class="col-xl-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                            <div>
                                <!-- <h4 class="card-title font-weight-bolder">Individual and Team Averages</h4> -->
                                <h4 class="card-title mb-75">Top Performers</h4>
                            </div>
                          
                        </div>
                        <div class="card-body">
                            <div id="top_performers_bar_chart"></div>
                        </div>
                    </div>
                </div>
                <!-- End Performer chart -->

                <!-- Questions Overview Card -->
                <div class="col-lg-6 col-sm-12">
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
                <!--/ end Questions Overview Card -->

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
    <!-- END: Page Vendor JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

    <script src="{$assetpath}/js/dashboards/team_dash.js"></script>

HTML;

echo $OUTPUT->footer();
