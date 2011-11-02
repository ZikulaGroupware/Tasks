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
        return $this->viewTasks();
    }

    /**
    * View all tasks
    *
    * @return The view var
    */

    public function viewTasks()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }
        
        $data['mode']        = FormUtil::getPassedValue('mode');
        $data['onlyMyTasks'] = FormUtil::getPassedValue('onlyMyTasks', false); 
        $data['category']    = FormUtil::getPassedValue('category', 'all');
        $data['limit']       = FormUtil::getPassedValue('limit', 10);
        $data['startnum']    = FormUtil::getPassedValue('startnum', 0);
        // this is needed because of onlyMyTasks
        if(empty($data['mode'])) {
            $data['mode']        = 'undone';
            $data['onlyMyTasks'] = true;
        }
        $this->view->assign($data);
        $data['paginator'] = true; 
        
        list($tasks, $tasks_count) = ModUtil::apiFunc($this->name,'user','getTasks', $data);
        $this->view->assign('tasks', $tasks);
        $this->view->assign('tasks_count', $tasks_count);
        
        
        $modes = ModUtil::apiFunc($this->name,'user','getModes');
        $this->view->assign('modes', $modes);        
        
        $categories = ModUtil::apiFunc($this->name,'user','getCategories', 'select');
        $this->view->assign('categories', $categories);
        
        
        $items_per_page = ModUtil::apiFunc($this->name,'user','getItemsPerPage'); 
        $this->view->assign('items_per_page', $items_per_page);
        
        return $this->view->fetch('user/viewTasks.tpl');
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
        $tid = FormUtil::getPassedValue('tid', isset($args['tid']) ? $args['tid'] : null, 'REQUEST');

        $task = ModUtil::apiFunc($this->name,'user','getTask', $tid );
        $task['participants'] = implode(', ', $task['participants']);

        // Create and return output object
        $this->view->assign($task);
        return $this->view->fetch('user/view.tpl');
    }
    
    
    public function reminder($args)
    {
        $form = FormUtil::newForm($this->name, $this);
        return $form->execute('user/reminder.tpl', new Tasks_Handler_Reminder());
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
        $form = FormUtil::newForm($this->name, $this);
        return $form->execute('user/modify.tpl', new Tasks_Handler_Modify());
    }
    
    
    
    
    public function ical()
    {
       
        $tasks = ModUtil::apiFunc($this->name,'user','getTasks', array() );
        foreach($tasks as $key => $value) {
            $tasks[$key]['due'] = str_replace('-', '', $value['deadline']);
        }
        //header( 'Content-Type: application/calendar+xml; charset=utf-8' );
        $this->view->assign('tasks', $tasks);
        echo $this->view->fetch('user/ical.tpl');
        die();

    }
    
    
        
}