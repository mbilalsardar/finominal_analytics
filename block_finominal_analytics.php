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
 * Analytics Dashboard main class
 *
 * @package     block_finominal_analytics
 * @category    blocks
 * @author      Saqib Ansari (saqib@3ilogic.com)
 * @copyright   2019 onwards 3i Logic (Private) Limited (http://www.3ilogic.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_finominal_analytics extends block_base
{


    // function get_user_role($uid) {

    //     global $DB;
    
    //     $query = "SELECT
    //     u.id,
    //     u.username,
    //     r.shortname AS 'role',
    //     CASE ctx.contextlevel 
    //       WHEN 10 THEN 'system'
    //       WHEN 20 THEN 'personal'
    //       WHEN 30 THEN 'user'
    //       WHEN 40 THEN 'course_category'
    //       WHEN 50 THEN 'course'
    //       WHEN 60 THEN 'group'
    //       WHEN 70 THEN 'course_module'
    //       WHEN 80 THEN 'block'
    //      ELSE CONCAT('unknown context: ',ctx.contextlevel)
    //     END AS 'context_level',
    //     ctx.instanceid AS 'context_instance_id'
    //     FROM mdl_role_assignments ra
    //     JOIN mdl_user u ON u.id = ra.userid
    //     JOIN mdl_role r ON r.id = ra.roleid
    //     JOIN mdl_context ctx ON ctx.id = ra.contextid
    //     WHERE u.id=?
    //     GROUP BY u.id
    //     ORDER BY u.username ";
    
    //     $result=$DB->get_record_sql($query,[$uid]);
    
    //     return $result;
    // }

    function init()
    {
        $this->blockname = get_class($this);
        $this->title = get_string('pluginname', $this->blockname);
    }

    // public function hide_header() {
    //     return false;
    // }

    public function get_content()
    {

        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;

        $_csi = context_system::instance();


        // $this->content->header = get_string('dashboard', $this->blockname);
        $blocklink = new moodle_url('/blocks/finominal_analytics');
        $my = new moodle_url('/my');
        // $this->content->text = '<a href="' . $blocklink . '/admin_dashboard.php">' . get_string('viewadmindashboard', $this->blockname) . '</a><br />';
        // $this->content->footer = '';


        // global $DB,$USER,$CFG;
        $this->content->text = '<a style="font-size:18px;" href="'.$blocklink.'/dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp'.get_string('dashboard',$this->blockname).'</a><br />';


        // $userroles = $this->get_user_role($USER->id);
        // if($userroles->role == 'manager' || is_siteadmin()) {
        //     $this->content->text = '<a style="font-size:18px;" href="'.$blocklink.'/team_dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp'.get_string('teamdashboard',$this->blockname).'</a><br />';
        // }

        // elseif ($userroles->role == 'student') { 
        //     $this->content->text .= '<a style="font-size:18px;" href="'.$blocklink.'/indi_dashboard.php"><i class="fa fa-tachometer" aria-hidden="true"></i>&nbsp'.get_string('individualdashboard',$this->blockname).'</a><br />';
        // }
        
        
        
        


        return $this->content;
    }
}

