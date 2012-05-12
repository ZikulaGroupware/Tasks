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
        return $this->entityManager->find('Tasks_Entity_Tasks', $tid);
    }

    /**
    * Select all categories
    *
    * @return categories data
    */

    public function getTasks($args)
    {
        
        
        $em = $this->getService('doctrine.entitymanager');
        $qb = $em->createQueryBuilder();
        $qb->select('t, c, p, d')
           ->from('Tasks_Entity_Tasks', 't')
           ->leftJoin('t.categories', 'c')
           ->leftJoin('c.category', 'd')
           ->leftJoin('t.participants', 'p');

       if(!empty($args['mode'])) {
            if( is_array($args['mode']) ) {
                $args['mode'] = $args['mode']['0'];
            }
            if($args['mode'] == "undone") {
                $qb->where('t.progress < 100');
                if(empty($args['orderBy'])) {
                    $qb->orderBy('t.deadline', 'ASC');
                }
            } else if($args['mode'] == "done") {
                $qb->where('t.progress = 100');
            }
        }
        
        
        if (!empty($args['orderBy'])) {
            list($order, $sort) = explode(' ', $orderBy);
            $qb->orderBy('t.'.$order, $sort);
        }
        

        
        if(isset($args['category']) && $args['category'] != 'all') {
           $qb->leftJoin('c.category', 'cc')
              ->andWhere('cc.id = :categoryId')
              ->setParameter('categoryId', $args['category']);
        }
            

        if (!empty($args['search'])) {
            $args['search'] = '%'.$args['search'].'%';
            $qb->andWhere('t.title like :search or t.description like :search')
               ->setParameter( 'search', $args['search']);
        }
        
        
        if (isset($args['participant']) && $args['participant'] != 2) {
           if($args['participant'] == 1) {
               $args['participant'] = UserUtil::getVar('uname');
           }
                      
           $qb->leftJoin('t.participants', 'q')
              ->andWhere('q.uname = :uname')
              ->setParameter('uname', $args['participant']);
        }
        
                
        if(empty($args['paginator']) and !empty($args['limit'])) {
            if( is_array($args['limit']) ) {
                $args['limit'] = $args['limit']['0'];
            }
            $qb->setMaxResults($args['limit']);
        }
        
        $query = $qb->getQuery();
                
        
        if( !empty($args['paginator']) and !empty($args['limit'])) {
            if(empty($args['startnum']) ) {
                $args['startnum'] = 1;
            }
            $count = \DoctrineExtensions\Paginate\Paginate::getTotalQueryResults($query);
            $paginateQuery = \DoctrineExtensions\Paginate\Paginate::getPaginateQuery($query, $args['startnum']-1 , $args['limit']); // Step 2 and 3
            $result = $paginateQuery->getArrayResult();
        } else {
            $result = $query->getArrayResult();
        }
        
        
        if(!empty($args['paginator']) ) {
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
    public function getCategories()
    {
        $mainCategory = CategoryRegistryUtil::getRegisteredModuleCategories('Tasks', 'Tasks');
        $output = array();
        foreach(CategoryUtil::getCategoriesByParentID($mainCategory['Main']) as $category) {
            $output[$category['id']] = $category['display_name'][ZLanguage::getLocale()];
        }
        return $output;
        
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
    
    
    
            
    /**
     * decode a short url
     * 
     * @param args array page arguments
     */
    public function decodeurl($args)
    {        
        // check we actually have some vars to work with...
        if (!isset($args['vars'])) {
            return LogUtil::registerArgsError();
        }  
        
        
        if (!isset($args['vars'][2])) {
            return;            
        }
        
        if ($args['vars'][2] == $this->__('new')) {
            System::queryStringSetVar('func', 'modify');
            return;
        }
        
        
        System::queryStringSetVar('tid', $args['vars'][2]);
        
        if (isset($args['vars'][3])) {
            System::queryStringSetVar('func', $args['vars'][3]);           
        } else {
            System::queryStringSetVar('func', 'view');
        }
    }
    
    
    
    /**
     * encode an url into a shorturl
     * 
     * @param vars array page variables
     */
    public function encodeurl($vars)
    {
        $shorturl = $vars['modname'];
        
        if ($vars['func'] == 'main' || $vars['func'] == 'viewTasks') {
            return $shorturl;
        }
        
        if (isset($vars['args']['tid'])) {
            $shorturl .= '/'.$vars['args']['tid'];            
        } else if ($vars['func'] == 'modify') {
            return $shorturl .= '/'.$this->__('new'); 
        }
        
        if ($vars['func'] != 'view') {
            $shorturl .= '/'.$vars['func'];
        }
        
        return $shorturl;
    }

    
   
    
}