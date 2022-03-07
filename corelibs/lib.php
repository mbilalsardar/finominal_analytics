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
function get_all_courses($catid)
{
    global $DB;

    $query = "SELECT id,fullname FROM mdl_course WHERE category=? AND visible=1";
    
    $params = [];
    
    if($catid !== '') {
        $params[] = $catid;
    }

    $allRecords = $DB->get_records_sql($query,$params);

    return $allRecords;
}
