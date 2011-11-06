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

    public function getTask($tid)
    {
        $em = $this->getService('doctrine.entitymanager');
        $qb = $em->createQueryBuilder();
        $qb->select('t, p, c')
           ->from('Tasks_Entity_Tasks', 't')
           ->where('t.tid = :tid')
           ->setParameter('tid', $tid)
           ->leftJoin('t.participants', 'p')
           ->leftJoin('t.categories', 'c');
         //  ->leftJoin('c.name', 'n');
        $query = $qb->getQuery();
        $result = $query->getArrayResult();
        if(count($result) == 0) {
            return false;
        }
        
        $task = $result[0];
        
        
        
        $all_categories = $this->getCategories('list');
        $categories = array();
        foreach($task['categories'] as $value) {
            $id  = $value['categoryId'];
            $categories[$id] = $all_categories[$id];
        }
        $task['categories']  = $categories;
        
        $participants = array();
        foreach($task['participants'] as $value) {
            $participants[] = $value['uname'];
        }
        $task['participants'] = $participants;      
        
        return $task;
    }

    /**
    * Select all categories
    *
    * @return categories data
    */

    public function getTasks($args)
    { 
        extract($args);
        
        
        $em = $this->getService('doctrine.entitymanager');
        $qb = $em->createQueryBuilder();
        $qb->select('t, p, c')
           ->from('Tasks_Entity_Tasks', 't')
           ->leftJoin('t.categories', 'c')
           ->leftJoin('t.participants', 'p');

       if(!empty($mode)) {
            if( is_array($mode) ) {
                $mode = $mode['0'];
            }
            if($mode == "undone") {
                $qb->where('t.progress < 100');
                if(empty($orderBy)) {
                    $qb->orderBy('t.deadline', 'ASC');
                }
            } else if($mode == "done") {
                $qb->where('t.progress = 100');
            }
        }
        
        
        if(!empty($orderBy)) {
            list($order, $sort) = explode(' ', $orderBy);
            $qb->orderBy('t.'.$order, $sort);
        }
        
        
        if(!empty($category) and $category[0] != 'all' and $category != 'all') {
           $qb->leftJoin('t.categories', 'cc')
              ->andWhere('cc.categoryId = :categoryId')
              ->setParameter('categoryId', $category);
        }
            

       if(!empty($search)) {
            $search = '%'.$search.'%';
            $qb->andWhere('t.title like ? or t.description like ?', array($search,$search));
        }
        
        
        if(!empty($onlyMyTasks) and $onlyMyTasks !== false) {
           $qb->leftJoin('t.participants', 'q')
              ->andWhere('q.uname = :uname')
              ->setParameter('uname', UserUtil::getVar('uname'));
        }
        
        
                
        if(empty($paginator) and !empty($limit)) {
            if( is_array($limit) ) {
                $limit = $limit['0'];
            }
            $qb->setMaxResults($limit);
        }
        
        $query = $qb->getQuery();  
        
        if( !empty($paginator) and !empty($limit)) {      
            $count = \DoctrineExtensions\Paginate\Paginate::getTotalQueryResults($query);
            $paginateQuery = \DoctrineExtensions\Paginate\Paginate::getPaginateQuery($query, $startnum, $limit); // Step 2 and 3
            $result = $paginateQuery->getArrayResult();
        } else {
            $result = $query->getArrayResult();
        }
        
                
        // format categories
        $all_categories = $this->getCategories('list');
        foreach ($result as $key => $tasks) {
            $list = array();
            foreach($tasks['categories'] as $category) {
                $id = $category['categoryId'];
                if( array_key_exists($id, $all_categories) ) {
                    $list[$id] = $all_categories[$id];
                }
            }
            $result[$key]['categories'] = $list;
        }
        
        
        if(!empty($paginator) ) {
            if(empty($count)) {
                $count = 0;
            }
            return array($result, $count);
        }
        
        
        return $result;
    }
    
    /**
     * Get all tasks categories
     *
     * @param $outputStyle string output style (query|list|select|formdropdownlist)
     * @return array query array | formdropdownlist array
     */    
    public function getCategories( $outputStyle = 'query' )
    {
        $args['entity']      = 'Tasks_Entity_Categories';
        $args['outputStyle'] = $outputStyle;
        return ModUtil::apiFunc('AlternativeCategories', 'User', 'getCategories', $args);
    }
    
    
    /**
     * Get all tasks modes (done,undone and all)
     *
     * @return array formdropdownlist array
     */
    public function getModes()
    {
        return array(
            'undone' => $this->__('Undone tasks'),
            'done'   => $this->__('Completed tasks'),
            'all'    => $this->__('All tasks')
        );
    }
    
    
    /**
     * Get items per page array
     *
     * @return array formdropdownlist array
     */
    public function getItemsPerPage()
    {
        $result = array();
        foreach(array( 1, 5, 10, 20, 50, 100) as $value) {
            $result[$value] = $value;
        }
        return $result;
    }
    
    
    public function isAllowedToEdit($args)
    {
        extract($args);
        unset($args);
        if(SecurityUtil::checkPermission('Tasks::', '::', ACCESS_EDIT) ) {
            return true;
        } else if (isset ($owner) and $owner == UserUtil::getVar('uid')) {
            return true;
        } else {
            return false;
        }

    }
   
    
}