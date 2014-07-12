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


class Tasks_Controller_Ajax extends Zikula_Controller_AbstractAjax
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
        $task = $this->entityManager->find('Tasks_Entity_Tasks', $tid);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }
    
    
    public function removeCategory()
    {
        if(!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_DELETE) ){
            return;
        }
        $id = FormUtil::getPassedValue('id', -1, 'GET');        
        $category = $this->entityManager->find('Tasks_Entity_Categories', $id);
        $this->entityManager->remove($category);
        $this->entityManager->flush();
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
    
    /**
     * getusers
     * performs a user search based on the keyword entered so far
     *
     * @author Frank Schummertz
     * @param keyword string the fragment of the username entered
     * @return void nothing, direct ouptut using echo!
     */
    public function getusers()
    {        
        // Security check
        if (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $keyword = FormUtil::getPassedValue('keyword', '', 'POST');
        if (empty($keyword)) {
            //AjaxUtil::
            //AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }
        
        $pntable     = DBUtil::getTables();
        $userscolumn = $pntable['users_column'];

        $where = 'WHERE ' . $userscolumn['uname'] . ' REGEXP \'(' . DataUtil::formatForStore($keyword) . ')\' AND '.$userscolumn['uname'].' NOT LIKE \'Anonymous\'';
        $orderby = 'ORDER BY ' . $userscolumn['uname'] . ' ASC';

        $countusers = DBUtil::selectObjectCount('users', $where);
        if ($countusers < 11) {
            $users = DBUtil::selectObjectArray('users', $where, $orderby);
        } else {
            return;
        }

        if ($users === false) {
            return AjaxUtil::registerError ($this->__('Error! Could not load data.'));
        }

        $return = array();
        foreach ($users as $user) {
            $return[] = array('caption' => $user['uname'],
                    'value'   => $user['uname']);
        }

        $output = json_encode($return);

        header('HTTP/1.0 200 OK');
        echo $output;
        System::shutdown();
    }
    
    
    /**
     * getusers
     * performs a user search based on the keyword entered so far
     *
     * @param keyword string the fragment of the username entered
     * @return void nothing, direct ouptut using echo!
     */
    public function getcategories()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_READ)) {
            AjaxUtil::error($this->__('Sorry! You do not have authorisation for this module.'));
        }

        $keyword = FormUtil::getPassedValue('keyword', '', 'POST');
        if (empty($keyword)) {
           AjaxUtil::error($this->__('Error! The action you wanted to perform was not successful for some reason, maybe because of a problem with what you input. Please check and try again.'));
        }
        
        
        $em = $this->getService('doctrine.entitymanager');
        $qb = $em->createQueryBuilder();
        $qb->select('c')
           ->from('Tasks_Entity_Categories', 'c');
        $query = $qb->getQuery();
        $categories = $query->getArrayResult();

        if ($categories === false) {
            return AjaxUtil::registerError ($this->__('Error! Could not load data.'));
        }

        $return = array();
        foreach ($categories as $category) {
            $return[] = array('caption' => $category['name'],
                    'value'   => $category['name']);
        }

        $output = json_encode($return);

        header('HTTP/1.0 200 OK');
        echo $output;
        System::shutdown();
    }
    
    
    
   
}
