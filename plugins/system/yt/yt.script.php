<?php

/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2015 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die();


class PlgSystemYtInstallerScript
{
    /**
     * Called after any type of action
     */
    public function postFlight($route, JAdapterInstance $adapter)
    {
        $db    = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->update('#__extensions')
            ->set("`enabled`='1'")
            ->where("`type`='plugin'")
            ->where("`folder`='system'")
            ->where("`element`='yt'");
        $db->setQuery($query);
        $db->execute();
        
        return true;
    }
}
