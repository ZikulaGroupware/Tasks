<?php

/**
 * Copyright Piwik Team 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Piwik
 * @link http://code.zikula.org/piwik
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Tasks_Handler_Participants extends Zikula_Form_AbstractHandler
{
    /**
     * section name.
     *
     * When set this handler is in edit mode.
     *
     * @var integer
     */
    private $_task;

    
    
    function initialize(Zikula_Form_View $view)
    {

       // permission checks
        $tid = FormUtil::getPassedValue('tid', isset($args['tid']) ? $args['tid'] : null, 'REQUEST');
        if (empty($tid)) {
            return LogUtil::registerError('No tid');
        }
        if (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_ADD) ) {
            return LogUtil::registerPermissionError();
        }
        
         
        $this->_task = ModUtil::apiFunc('Tasks','user','getTask', $tid );
        
        $participants = ModUtil::apiFunc('Tasks','user','getParticipants', $tid );  
        foreach($participants as $key => $value) {
           // $participants[$key]['uname'] = UserUtil::getVar('uname', $value['uid']);
        }
        $this->view->assign('participants', $participants );
        $this->view->assign($this->_task);

        
        return true;
    }


    function handleCommand(Zikula_Form_View $view, &$args)
    {
        // check for valid form
        if (!$view->isValid()) {
            return false;
        }
        
        $data = $view->getValues();
        $data['tid'] = $this->_task['tid'];

        $participant = Doctrine_Core::getTable('Tasks_Model_Participants')
            ->findOneBytidAnduname( $data['tid'], $data['uname']);
                
        if(!$participant ) {
            $participant = new Tasks_Model_Participants();
            $participant->merge($data);
            $participant->save();
        } else {
            LogUtil::registerError($this->__("Participant already added!"));
        }
        
        $uname = UserUtil::getVar('uname');
        if($data['uname'] != $uname) {
            $view = Zikula_View::getInstance($this->name, false);
            $view->assign('baseUrl', System::getBaseUrl());
            $view->assign($this->_task);
            $view->assign('uname', $uname );
            $message = $view->fetch('notification/new.tpl');
            $subject = '['.System::getVar('sitename').'] '.$this->__('There is something to do');
            $uid = UserUtil::getIdFromName($data['uname']);
            $toaddress = UserUtil::getVar('email', $uid);
            ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array(
                'toaddress' => $toaddress,
                'subject'   => $subject,
                'body'      => $message,
                'html'      => true
            ));
        }
        
        $url = ModUtil::url(
            'Tasks',
            'user',
            'participants',
            array('tid'=>$this->_task['tid'])
        ); 
        return $this->view->redirect($url);
    }

}