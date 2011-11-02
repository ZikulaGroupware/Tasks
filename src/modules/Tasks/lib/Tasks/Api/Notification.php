<?php

/**
 * Tasks
 *
 * @copyright (c) 2009, Fabian Wuertz
 * @author Fabian Wuertz
 * @link http://fabian.wuertz.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Tasks
 */


class Tasks_Api_Notification extends Zikula_AbstractApi 
{

    
    
    
    
    public function newTask($args) {
              
        extract($args);
        
        if(!is_array($users) or count($users) == 0 ) {
            return;
        }
        
        $uname = UserUtil::getVar('uname');
        
        foreach ($users as $user) {
            if($user == $uname) {
                continue;
            }
            $view = Zikula_View::getInstance($this->name, false);
            $view->assign('baseUrl', System::getBaseUrl());
            $view->assign('tid', $task['tid']);
            $view->assign('uname', $user );
            $message = $view->fetch('notification/new.tpl');
            $subject = '['.System::getVar('sitename').'] '.$this->__('There is something to do');
            $uid = UserUtil::getIdFromName($user);
            $toaddress = UserUtil::getVar('email', $uid);
            ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array(
                'toaddress' => $toaddress,
                'subject'   => $subject,
                'body'      => $message,
                'html'      => true
            ));
        }     

    }
    
}