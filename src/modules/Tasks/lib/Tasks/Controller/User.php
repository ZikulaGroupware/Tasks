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

class Tasks_Controller_User extends Zikula_AbstractController
{

    //-----------------------------------------------------------//
    //-- View ---------------------------------------------------//
    //-----------------------------------------------------------//

    /**
    * Main page - Redirect to viewAll
    *
    * @return The view redirect
    */

    public function main()
    {
        return $this->viewAll();
    }

    /**
    * View all tasks
    *
    * @return The view var
    */

    public function viewAll()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        // Get form/request vars
        $mode  = FormUtil::getPassedValue('mode', isset($args['mode']) ? $args['mode'] : null, 'POST');
        $onlyMyTasks = FormUtil::getPassedValue('onlyMyTasks', isset($args['onlyMyTasks']) ? $args['onlyMyTasks'] : null, 'POST');
                
        if(empty($mode)) $mode = 'undone';

        $tasks = ModUtil::apiFunc('Tasks','user','getTasks', array(
            'mode' => $mode,
            'onlyMyTasks' => $onlyMyTasks
        ) );


        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Tasks', 'tasks');

        // Create and return output object
        $this->view->assign('tasks', $tasks);
        $this->view->assign('mode', $mode );
        $this->view->assign('onlyMyTasks', $onlyMyTasks );
        $this->view->assign('catregistry', $catregistry);
        return $this->view->fetch('user/viewAll.tpl');
    }


    /**
    * View all tasks
    *
    * @return The view var
    */

    public function view()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        // Get form/request vars
        $id = FormUtil::getPassedValue('id', isset($args['id']) ? $args['id'] : null, 'REQUEST');

        $task = ModUtil::apiFunc('Tasks','user','getTask', $id );


        // Create and return output object
        $this->view->assign($task);
        return $this->view->fetch('user/view.tpl');
    }
    
    
    //-----------------------------------------------------------//
    //-- Add/Edit -----------------------------------------------//
    //-----------------------------------------------------------//

    /**
    * Add/Edit a task (gui)
    *
    * @param array $args POST/REQUEST vars
    * @return The view var
    */

    public function modify($args)
    {
        $form = FormUtil::newForm('Tasks', $this);
        return $form->execute('user/modify.tpl', new Tasks_Handler_Modify());
    }
    
    
        /**
    * Add/Edit a task (gui)
    *
    * @param array $args POST/REQUEST vars
    * @return The view var
    */

    public function participants($args)
    {
        $form = FormUtil::newForm('Tasks', $this);
        return $form->execute('user/participants.tpl', new Tasks_Handler_Participants());
    }
    
    
    public function ical()
    {
       
        $tasks = ModUtil::apiFunc('Tasks','user','getTasks', array() );
        foreach($tasks as $key => $value) {
            $tasks[$key]['due'] = str_replace('-', '', $value['deadline']);
        }
        //header( 'Content-Type: application/calendar+xml; charset=utf-8' );
        $this->view->assign('tasks', $tasks);
        echo $this->view->fetch('user/ical.tpl');
        die();

    }

}