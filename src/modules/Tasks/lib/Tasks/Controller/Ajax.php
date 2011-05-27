<?php

/**
 * Copyright Fabian Wuertz 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package ISHAsections
 * @link  http://www.isha-international.org/
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */


class Tasks_Controller_Ajax extends Zikula_AbstractController
{
    /**
     * Post setup.
     *
     * @return void
     */
    public function _postSetup()
    {
        // no need for a Zikula_View so override it.
    }


    
    public function remove()
    {
        if(!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_DELETE) ){
            return;
        }
        $tid = FormUtil::getPassedValue('id', -1, 'GET');
        $task = Doctrine_Core::getTable('Tasks_Model_Tasks')->find($tid);
        $task->delete();
    }
    
    public function removeParticipant()
    {
        if(!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_DELETE) ){
            return;
        }
        $id = FormUtil::getPassedValue('id', -1, 'GET');
        $tmpArray = explode(',', $id);
        if(count($tmpArray) < 2 ) {
            return;
        } 
        $tid   = $tmpArray[0];
        $uname = $tmpArray[1];
        $participant = Doctrine_Core::getTable('Tasks_Model_Participants')->findBytidAnduname($tid,$uname);
        $participant->delete();
    }
    
    public function getUsers()
    {
        $view = Zikula_View::getInstance($this->name);

        $fragment = $this->request->getGet()->get('fragment', $this->request->getPost()->get('fragment'));

        ModUtil::dbInfoLoad($this->name);
        $tables = DBUtil::getTables();

        $usersColumn = $tables['users_column'];

        $where = 'WHERE ' . $usersColumn['uname'] . ' REGEXP \'(' . DataUtil::formatForStore($fragment) . ')\''.
                 'AND ' . $usersColumn['activated'] .'= 1';
        $results = DBUtil::selectObjectArray('users', $where);

        $view->assign('results', $results);

        $output = $view->fetch('ajax/getusers.tpl');

        return new Zikula_Response_Ajax_Plain($output);
    }
   
}
