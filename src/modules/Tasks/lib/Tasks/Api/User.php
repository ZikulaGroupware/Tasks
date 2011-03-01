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


class Tasks_Api_User extends Zikula_Api 
{

    /**
    * Select an category by the given category ID
    *
    * @param int $cid category ID
    * @return category data
    */

    public function getTask($id)
    {
        $task = Doctrine_Core::getTable('Tasks_Model_Tasks')->find($id);
        return $task->toArray();
    }

    /**
    * Select all categories
    *
    * @return categories data
    */

    public function getTasks($mode = false)
    { 
        $q = Doctrine_Query::create()
            ->from('Tasks_Model_Tasks e')
            ->orderBy('priority desc, deadline asc');

        if($mode) {
            if($mode == "undone") {
                $q->where('progress < 100');
            } else if($mode == "done") {
                $q->where('progress = 100');
            }
        }

        $tasks = $q->execute();
        return $tasks->toArray();
    }

}