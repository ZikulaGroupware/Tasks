<?php

/**
 * Copyright Tasks Team 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Tasks
 * @link http://code.zikula.org/piwik
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */


class Tasks_Api_Search extends Zikula_AbstractApi
{

    /**
     * Search plugin info
     **/
    public function info()
    {
        return array(
            'title' => 'Tasks',
            'functions' => array('Tasks' => 'search')
        );
    }

    /**
     * Search form component
     **/
    public function options($args)
    {
        if (SecurityUtil::checkPermission('Tasks::', '::', ACCESS_READ)) {
            $renderer = Zikula_View::getInstance('Tasks');
            $active = (isset($args['active'])&&isset($args['active']['Tasks']))||(!isset($args['active']));
            $renderer->assign('active',$active);
            return $renderer->fetch('search/options.tpl');
        }

        return '';
    }

    /**
     * Search plugin main function
     **/
    public function search($args)
    {
        // Permission check
        $this->throwForbiddenUnless(
            SecurityUtil::checkPermission('Tasks::', '::', ACCESS_READ),
            LogUtil::getErrorMsgPermission()
        );

        
        $tasks = ModUtil::apiFunc('Tasks','user','getTasks', array(
            'search' => $args['q'],
        ) );
        
        $sessionId = session_id();
        
        foreach ($tasks as $task)
        {
            if( ModUtil::available('LuMicuLa') ) {
                $text =  ModUtil::apiFunc('LuMicuLa', 'user', 'transform', array(
                    'text'   => $task['description'])
                );
            }
            
            $item = array(
                'title'   => $task['title'],
                'text'    => $text,
                'extra'   => $task['tid'],
                'created' => $task['cr_date'],
                'module'  => $this->name,
                'session' => $sessionId
            );
            $insertResult = DBUtil::insertObject($item, 'search_result');
            if (!$insertResult) {
                return LogUtil::registerError($this->__('Error! Could not load any articles.'));
            }
        }

        
      return true;
    }


    /**
     * Do last minute access checking and assign URL to items
     *
     * Access checking is ignored since access check has
     * already been done. But we do add a URL to the found user
     */
    public function search_check($args)
    {
        $datarow = &$args['datarow'];
        $id = $datarow['extra'];
        $datarow['url'] = ModUtil::url($this->name, 'user', 'view', array('id' => $id));

        return true;
    }

}

