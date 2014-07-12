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
    private $_task;
    private $_progress;

    

    function initialize(Zikula_Form_View $view)
    {
        if ((!UserUtil::isLoggedIn()) || (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_EDIT))) {
            return LogUtil::registerPermissionError();
        }    
        
                
        $tid = FormUtil::getPassedValue('tid', null, "GET", FILTER_SANITIZE_NUMBER_INT);

        if ($tid) {
            // load user with id
            $task = $this->entityManager->find('Tasks_Entity_Tasks', $tid);

            if (!$task) {
                return LogUtil::registerError($this->__f('Task with id %s not found', $tid));
            }
            $view->assign('templatetitle', $this->__('Modify Task'));
            $this->_progress = (int)$task->getProgress();
            
            
        } else {
            $task = new Tasks_Entity_Tasks();
        }
        

        // assign current values to form fields
        $view->assign('task', $task)
             ->assign($task->toArray());
        
        
        
        
        $this->view->assign('dateformat', $this->__('%Y-%m-%d') );
                

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
        

        // load categories
        $categories = CategoryRegistryUtil::getRegisteredModuleCategories('Tasks', 'Tasks', 'id');
        $view->assign('registries', $categories);
        
        $this->_task = $task;
        
        return true;
    }

    

    function handleCommand(Zikula_Form_View $view, &$args)
    {
        $task = $this->_task;
        
        
        // switch between edit and create mode        
        if ($task) {        
            $url = ModUtil::url('Tasks', 'user', 'view', array(
                'tid' => $task->getTid()
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
        


        // load form valuest
        $data = $view->getValues();
        
        
                
        if((int)$data['progress'] == 100 and $task->getProgress != 100) {
            $data['done_date'] = new DateTime;;
        }
        
        
        
        $task->merge($data);
        $this->entityManager->persist($task);
        $this->entityManager->flush(); 
        
        
        return $this->view->redirect($url);
    }

}