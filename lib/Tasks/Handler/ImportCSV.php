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


class Tasks_Handler_ImportCSV extends Zikula_Form_AbstractHandler
{

    

    function initialize(Zikula_Form_View $view)
    {
        if ((!UserUtil::isLoggedIn()) || (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_EDIT))) {
            return LogUtil::registerPermissionError();
        }
      
        
        return true;
    }

    

    function handleCommand(Zikula_Form_View $view, &$args)
    {

        if ($args['commandName'] == 'cancel') {
            $url = ModUtil::url('Tasks', 'user', 'main');
            return $view->redirect($url);
        }
        if ($args['commandName'] == 'test') {
            $is_a_test = true;
        } else {
            $is_a_test = false;
        }
        
        
        // check for valid form
        if (!$view->isValid()) {
            return false;
        }
        
        $cols = array(
             0 => 'title',
             1 => 'description',
             2 => 'priority',
             3 => 'progress',
             4 => 'deadline',
             5 => 'cr_uid',
             6 => 'cr_date',
             7 => 'done_date',
             8 => 'participants',
             9 => 'categories',
            10 => 'approved'
        );

        // load form values
        $data = $view->getValues();
        
        
        //$input = str_replace('"', '\\"', $data['input']);
        $input = explode("\n", $data['input']);
        
                
        // test it!
        foreach ($input as $key => $line) {
            $key = $key+1;
            if($line == '') {
                continue;
            }
            $entry0 = str_getcsv($line , $delimiter = ',', $enclosure = '"', $escape = '\\');
            if( count($entry0) != count($cols) ) {
                        return LogUtil::registerError('Line: '.$key.', Check: Amount of entries');
                    }
            $entry  = array();
            foreach($entry0 as $key => $value) {
                if(empty($value)) {
                    continue;
                }
                $col = $cols[$key];
                if($col == 'deadline' or $col == 'cr_date' or $col == 'done_date') {
                    $date_array = explode('-', $value);
                    if(count($date_array) != 3 ) {
                        return LogUtil::registerError('Line: '.$key.', Col: '.$col.', Check: dateformat');
                    }
                    if( !checkdate($date_array[1], $date_array[2], $date_array[0]) ) {
                        return LogUtil::registerError('Line: '.$key.', Col: '.$col.', Check: checkdate');
                    }
                } else if ($col == 'priority' or $col == 'progress' or $col == 'cr_uid') {
                    if(!is_numeric($value) ) {
                        return LogUtil::registerError('Line: '.$key.', Col: '.$col.', Check: numeric');
                    }
                    if($col == 'priority' and ($value > 9 or $value < 0) ) {
                        return LogUtil::registerError('Line: '.$key.', Col: '.$col.', Check: < 10');
                    }
                    if($col == 'progress' and ($value > 100 or $value < 0) ) {
                        return LogUtil::registerError('Line: '.$key.', Col: '.$col.', Check: < 101');
                    }
                } else if ($col == 'approved') {
                    if(!is_numeric($value) or $value > 1 or $value < 0 ) {
                        return LogUtil::registerError('Line: '.$key.', Col: '.$col.', Check: bool');
                    }
                }
                $data[$col] = $value;
            }
            $test[] = $data;
        }  
        $this->view->assign('test', $test );
        
        
        
        if(!$is_a_test) {
            foreach ($input as $line) {
                $data =array();
                if($line == '') {
                    continue;
                }
                $entry0 = str_getcsv($line , $delimiter = ',', $enclosure = '"', $escape = '\\');
                $entry  = array();
                foreach($entry0 as $key => $value) {
                    $col = $cols[$key];
                    if($col == 'deadline' or $col == 'cr_date' or $col == 'done_date') {
                        if(!empty($value)) {
                            $value = new DateTime($value);
                        } else {
                            $value = null;
                        }
                    }
                    $data[$col] = $value;
                }

               $task = new Tasks_Entity_Tasks();
               
               if(!empty($data['participants'])) {
                    $particants = explode(',', $data['participants']);
                    foreach($particants as $participant) {
                        $task->setParticipants($participant);
                    }
                }
                unset($data['participants']);
        
        
                if(!empty($data['categories'])) {
                    $categories = explode(',', $data['categories']);
                    foreach($categories as $category) {
                        $em = ServiceUtil::getService('doctrine.entitymanager');
                        $qb = $em->createQueryBuilder();
                        $qb->select('c')
                           ->from('Tasks_Entity_Categories', 'c')
                           ->where('c.name = :name')
                           ->setParameter('name', $category);
                        $query = $qb->getQuery();
                        $result = $query->getArrayResult();
                        $task->setCategories($result[0]['id']);
                    }
                }
                unset($data['categories']);
                                
                $task->merge($data);
                $this->entityManager->persist($task);
                $this->entityManager->flush();

            }
            LogUtil::registerStatus($this->__('Import successful'));
        }

        return true;
    }

}
