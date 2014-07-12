<?php

/**
 * Copyright LuMicuLa Team 2011
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package LuMicuLa
 * @link http://code.zikula.org/LuMicuLa
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Tasks_Api_Admin extends Zikula_AbstractApi
{

    /**
    *  get available admin panel links
    *
    * @return array array of admin links
    */
    public function getlinks($args)
    {
        $links = array();
        if (SecurityUtil::checkPermission('Tasks::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'viewCategories'),
                'text' => __('Categories'),
                'class' => 'z-icon-es-view'
            );
            $links[] = array(
                'url' => ModUtil::url($this->name, 'admin', 'importCSV'),
                'text' => __('Import CSV'),
                'class' => 'z-icon-es-import'
            );
        }
        return $links;
    }
    
    
    
}