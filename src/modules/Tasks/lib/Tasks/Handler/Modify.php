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


class Tasks_Handler_Modify extends Zikula_Form_Handler
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
        
        // load and assign registred categories
        $registryCategories  = CategoryRegistryUtil::getRegisteredModuleCategories('Tasks', 'tasks');
        $categories = array();
        foreach ($registryCategories as $property => $cid) {
            $categories[$property] = (int)$cid;
        }
        $view->assign('registries', $categories);
        
        $tid = FormUtil::getPassedValue('tid', null, "GET", FILTER_SANITIZE_NUMBER_INT);

         if ($tid) {
            $view->assign('templatetitle', 'Modify Task');
            
            $task = ModUtil::apiFunc('Tasks','user','getTask', $tid );

            if ($task) {
                $this->_tid = $tid;
                $view->assign($task);
            } else {
                return LogUtil::registerError($this->__f('Task with tid %s not found', $tid));
            }
        } else {
            $view->assign('templatetitle', 'Create task');
        }
        $this->view->assign('today', date('Y-m-d H:i:s') );
        
        // load the category registry util
        if (!($class = Loader::loadClass('CategoryRegistryUtil'))) {
                z_exit (pnML('_UNABLETOLOADCLASS', array('s' => 'CategoryRegistryUtil')));
        }
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Tasks', 'tasks');
        
  
        foreach(range(0, 100, 10) as $i) {
            $percentages[] = array('value' => $i, 'text' => $i.' %');
        }
        $this->view->assign('percentages', $percentages );
        foreach(range(0, 5) as $i) {
            $priorities[] = array('value' => $i, 'text' => $i);
        }
        $this->view->assign('priorities',  $priorities );
        $this->view->assign('catregistry', $catregistry);
      
        return true;
    }

    

    function handleCommand(Zikula_Form_View $view, &$args)
    {

        if ($args['commandName'] == 'cancel') {
            if ($this->_tid) {
                $url = ModUtil::url('Tasks', 'user', 'view', array('tid' => $this->_tid) );
            } else {
                $url = ModUtil::url('Tasks', 'user', 'viewAll');
            }
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
            $url  = ModUtil::url('Tasks', 'user', 'view', array('tid' => $this->_tid) );
        } else {
            $data['cr_uid'] = UserUtil::getVar('uid');
            $data['cr_date'] = date('Y-m-d');
            $task = new Tasks_Model_Tasks();

        }
        $task->merge($data);
        $task->save();

        $url = ModUtil::url('Tasks', 'user', 'view', array('tid' => $task['tid']) );
        return $this->view->redirect($url);
    }

}
