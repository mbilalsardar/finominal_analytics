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
 * @category    blocks
 *
 * @author      Saqib Ansari (saqib@3ilogic.com)
 * @copyright   2019 onwards 3i Logic (Private) Limited (http://www.3ilogic.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
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
$PAGE->set_title('Dashboard');

// $output = $PAGE->get_renderer('block_finominal_analytics');

// Set the page heading.
$PAGE->set_heading('Dashboard');
$PAGE->navbar->add(get_string('teamdashboard', 'block_finominal_analytics'));
$PAGE->navbar->add('Dashboard', $linkurl);
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
HTML;


/* Code */
echo <<<HTML
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Teacher Dashboard</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="card">
                        <h1>bilal Sardar</h1>
                    </div>
                </div>
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
HTML;

echo $OUTPUT->footer();
