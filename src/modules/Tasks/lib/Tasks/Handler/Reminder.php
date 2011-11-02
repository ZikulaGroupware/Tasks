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


class Tasks_Handler_Reminder extends Zikula_Form_AbstractHandler
{
    /**
     * User id.
     *
     * When set this handler is in edit mode.
     *
     * @var integer
     */
    private $_tid;
    

    function initialize(Zikula_Form_View $view)
    {
        if ((!UserUtil::isLoggedIn()) || (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_EDIT))) {
            return LogUtil::registerPermissionError();
        }
        
        
        $tid = FormUtil::getPassedValue('tid', null, "GET", FILTER_SANITIZE_NUMBER_INT);

         if ($tid) {
            $view->assign('templatetitle', $this->__('Modify Task'));
            
            $task = ModUtil::apiFunc('Tasks','user','getTask', $tid );

            if ($task) {
                $participants = $task['participants'];
                $uname = UserUtil::getVar('uname');
                foreach($participants as $key => $participant) {
                    if($participant == $uname) {
                        unset($participants[$key]);
                        break;
                    }
                }
                $this->_tid = $tid;
                $view->assign('participants2', $participants);
                $view->assign('participants', implode(',', $participants));
                $view->assign($task);
                $url =  System::getBaseUrl().ModUtil::url($this->name, 'User', 'view', array('tid'=>$task['tid']));
                $message = '<p>'.$this->__("Please don't forget the following task:").'</p><ul>'.
                           '<li>'.$this->__('Title').': '.$task['title'].'</li>'.
                           '<li>'.$this->__('Deadline:').' '.$task['deadline']->format('d. M. Y').'</li>'.
                           '</ul>'.$this->__('More information here').': '.'<a href="'.$url.'">'.$url.'</a>';
                $view->assign('message', $message);
            } else {
                return LogUtil::registerError($this->__f('Task with tid %s not found', $tid));
            }
        } else {
            return LogUtil::registerError($this->__('No tid given'));
        }
      
        
        return true;
    }

    

    function handleCommand(Zikula_Form_View $view, &$args)
    {
     
        $url = ModUtil::url('Tasks', 'user', 'view', array(
            'tid' => $this->_tid
        ));
        

        if ($args['commandName'] == 'cancel') {
            return $view->redirect($url);
        }
        
        // check for valid form
        if (!$view->isValid()) {
            return false;
        }
        


        // load form values
        $data = $view->getValues();      
        $users = explode(',', $data['participants']);
        $message = $this->__f('%s sends you the following reminder', UserUtil::getVar('uname')).
                   '<br />'.$data['message'];
        
        foreach ($users as $user) {
            $view = Zikula_View::getInstance($this->name, false);
            $subject = '['.System::getVar('sitename').'] '.$this->__('Task reminder');
            $uid = UserUtil::getIdFromName($user);
            $toaddress = UserUtil::getVar('email', $uid);
            ModUtil::apiFunc('Mailer', 'user', 'sendmessage', array(
                'toaddress' => $toaddress,
                'subject'   => $subject,
                'body'      => $message,
                'html'      => true
            ));
        }     

        
        LogUtil::registerStatus($this->__('Reminder sended'));
        return $this->view->redirect($url);
    }

}
