<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@jomres.net>
 *
 * @version Jomres 9.9.19
 *
 * @copyright	2005-2018 Vince Wooll
 * Jomres (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_JOMRES_INITCHECK') or die('');
// ################################################################

class j06000save_property_day
{
    public function __construct()
    {
        $MiniComponents = jomres_singleton_abstract::getInstance('mcHandler');
        if ($MiniComponents->template_touch) {
            $this->template_touchable = false;

            return;
        }
        $activity = $_REQUEST['activity'];
        $activity_act_description =$_REQUEST['activity_act_description'];
        $activity_res =$_REQUEST['activity_res'];
		$activity_act_res_desc = $_REQUEST['activity_act_res_desc'];
		$day_title=$_REQUEST['day_title'];
		$day_id = $_REQUEST['day_id'];
 echo $day_id;
        foreach ( $day_title as $dt){
			
			echo $dt;
		}
    }

    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return null;
    }
}
