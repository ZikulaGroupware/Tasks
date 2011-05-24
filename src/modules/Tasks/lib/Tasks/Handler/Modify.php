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
        
        // load and assign registred categories
        $registryCategories  = CategoryRegistryUtil::getRegisteredModuleCategories('Tasks', 'Tasks');
        $categories = array();
        foreach ($registryCategories as $property => $cid) {
            $categories[$property] = (int)$cid;
        }
        $view->assign('registries', $categories);
 

        
        $tid = FormUtil::getPassedValue('tid', null, "GET", FILTER_SANITIZE_NUMBER_INT);

         if ($tid) {
            $view->assign('templatetitle', $this->__('Modify Task'));
            
            $task = ModUtil::apiFunc('Tasks','user','getTask', $tid );

            if ($task) {
                $this->_tid = $tid;
                $this->_progress = (int)$task['progress'];
                $view->assign($task);
            } else {
                return LogUtil::registerError($this->__f('Task with tid %s not found', $tid));
            }
        } else {
            $view->assign('templatetitle', $this->__('Create task'));
            $this->_progress = 0;
        }
        $this->view->assign('today', date('Y-m-d H:i:s') );
        
        

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
      
        
        return true;
    }

    

    function handleCommand(Zikula_Form_View $view, &$args)
    {
        // switch between edit and create mode        
        if ($this->_tid) {        
            $url = ModUtil::url('Tasks', 'user', 'view', array(
                'id' => $this->_tid
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
            $task = Doctrine_Core::getTable('Tasks_Model_Tasks')->find($this->_tid);
        } else {
            $data['cr_uid'] = UserUtil::getVar('uid');
            $data['cr_date'] = date('Y-m-d');
            $task = new Tasks_Model_Tasks();

        }
        
        if((int)$data['progress'] == 100 and $this->_progress != 100) {
            $data['done_date'] = date('Y-m-d');
        }
        
        
        $task->merge($data);
        $task->save();

        return $this->view->redirect($url);
    }

}
