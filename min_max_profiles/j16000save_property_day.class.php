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

class j16000save_property_day
{
    public function __construct()
    {
        $MiniComponents = jomres_singleton_abstract::getInstance('mcHandler');
        if ($MiniComponents->template_touch) {
            $this->template_touchable = false;

            return;
        }
        $countryCode = jomresGetParam($_REQUEST, 'country', '');
        $currentRegion = jomresGetParam($_REQUEST, 'region', '');
        $input_name = jomresGetParam($_REQUEST, 'input_name', '');

      
      

       
        echo $dropdown;
    }

    // This must be included in every Event/Mini-component
    public function getRetVals()
    {
        return null;
    }
}
