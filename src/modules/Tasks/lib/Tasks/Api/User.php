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


class Tasks_Api_User extends Zikula_AbstractApi 
{

    /**
    * Select an category by the given category ID
    *
    * @param int $cid category ID
    * @return category data
    */

    public function getTask($id)
    {
        $q = Doctrine_Query::create()->from('Tasks_Model_Tasks e');
        $q->where('tid = ?', array($id));
        $task = $q->execute();
        $task = $task->toArray();
        if(count($task) == 0) {
            return false;
        }
        return $task[0];
    }

    /**
    * Select all categories
    *
    * @return categories data
    */

    public function getTasks($args)
    { 
        extract($args);
        
        
        $q = Doctrine_Query::create()->from('Tasks_Model_Tasks a');
            

        if(!empty($mode)) {
            if($mode == "undone") {
                $q->where('progress < 100');
            } else if($mode == "done") {
                $q->where('progress = 100');
            }
        }
        if(empty($orderBy)) {
            $q->orderBy('priority asc, deadline asc');
        } else {
            $q->orderBy($orderBy);
        }
            
        if(!empty($limit)) {
            $q->limit($limit);
        }
        if(!empty($search)) {
            $search = '%'.$search.'%';
            $q->addWhere('title like ? or description like ?', array($search,$search));
        }
        
                
        if(!empty($onlyMyTasks) and $onlyMyTasks == 'on') {
            $q->leftJoin('a.Tasks_Model_Participants b');
            $q->addWhere('b.uname = ?', array(UserUtil::getVar('uname')));
        }
        

        $tasks = $q->execute();
        return $tasks->toArray();
    }
    
    
    public function getParticipants($tid)
    {
        $q = Doctrine_Query::create()
            ->from('Tasks_Model_Participants e')
            ->where('tid = ?', array($tid));
        $participants = $q->execute();
        return $participants->toArray();
    }

}