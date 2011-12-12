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


class Tasks_Handler_Modify extends Zikula_Form_AbstractHandler
{
    /**
     * User id.
     *
     * When set this handler is in edit mode.
     *
     * @var integer
     */
    private $_tid;
    private $_progress;

    

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
                $this->_tid = $tid;
                $this->_progress = (int)$task['progress'];
                $task['participants2'] = $task['participants']; 
                $task['participants'] = implode(',', $task['participants']);
                $task['categories'] = array_keys($task['categories']);
                $view->assign($task);
            } else {
                return LogUtil::registerError($this->__f('Task with tid %s not found', $tid));
            }
        } else {
            $view->assign('categories2', array());
            $this->_progress = 0;
        }
        $this->view->assign('dateformat', __('%Y-%m-%d') );
                

        $percentages = array();
        foreach(range(0, 100, 10) as $i) {
            $percentages[] = array('value' => $i, 'text' => $i.' %');
        }
        $this->view->assign('percentages', $percentages );
        $priorities = array();
        foreach(range(1, 9) as $i) {
            if($i == 1) {
                $text = $i.' ('.$this->__('highest').')';
            } else if ($i == 5) {
                $text = $i.' ('.$this->__('medium').')';;
            } else if ($i == 9) {
                $text = $i.' ('.$this->__('lowest').')';;
            } else {
                $text = $i;
            }
            $priorities[] = array('value' => $i, 'text' => $text);
        }
        $this->view->assign('priorities',  $priorities );        
        
        $allCategories = ModUtil::apiFunc($this->name, 'User', 'getCategories', 'formdropdownlist');
        $this->view->assign('allCategories',  $allCategories );
        
      
        
        return true;
    }

    

    function handleCommand(Zikula_Form_View $view, &$args)
    {
        // switch between edit and create mode        
        if ($this->_tid) {        
            $url = ModUtil::url('Tasks', 'user', 'view', array(
                'tid' => $this->_tid
            ) );
        } else {
            $url = ModUtil::url('Tasks', 'user', 'main');
        }
        

        if ($args['commandName'] == 'cancel') {
            return $view->redirect($url);
        }
        
        // check for valid form
        if (!$view->isValid()) {
            return false;
        }
        


        // load form values
        $data = $view->getValues();
        
        
        
        // switch between edit and create mode        
        if ($this->_tid) {
            $task = $this->entityManager->find('Tasks_Entity_Tasks', $this->_tid);
            
            // remove old participants
            $old_participants = $this->entityManager->getRepository('Tasks_Entity_Participants')
                                                    ->findBy(array('entity' => $this->_tid));
            foreach($old_participants as $old_participant) {
                $this->entityManager->remove($old_participant);
                $this->entityManager->flush();
            }
            // remove old categories
            $old_categories = $this->entityManager->getRepository('Tasks_Entity_CategoryMembership')
                                                  ->findBy(array('entity' => $this->_tid));
            foreach($old_categories as $old_category) {
                $this->entityManager->remove($old_category);
                $this->entityManager->flush();
            }
            $new_task = false;
        } else {
            $data['cr_uid'] = UserUtil::getVar('uid');
            $data['cr_date'] = new DateTime;
            $task = new Tasks_Entity_Tasks();
            $new_task = true;
        }
        
        if((int)$data['progress'] == 100 and $this->_progress != 100) {
            $data['done_date'] = new DateTime;;
        }
        

        $participants = array();
        if(!empty($data['participants'])) {
            $participants = explode(',', $data['participants']);
            foreach($participants as $participant) {
                $task->setParticipants($participant);
            }
        }
        unset($data['participants']);
        
        
        if(!empty($data['categories'])) {
            foreach($data['categories'] as $id) {
                $task->setCategories($id);
            }
        }
        unset($data['categories']);
        
        
        $data['deadline'] = new DateTime($data['deadline']);
        
        $task->merge($data);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        
        if($new_task) {
            ModUtil::apiFunc($this->name, 'Notification', 'newTask', array(
                'users' => $participants,
                'task'  => $task
            ));
        }
        
        return $this->view->redirect($url);
    }

}