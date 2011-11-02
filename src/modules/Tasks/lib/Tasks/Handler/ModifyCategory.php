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


class Tasks_Handler_ModifyCategory extends Zikula_Form_AbstractHandler
{
    
    private $_user;

    
    

    function initialize(Zikula_Form_View $view)
    {
        if ((!UserUtil::isLoggedIn()) || (!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_ADMIN))) {
            return LogUtil::registerPermissionError();
        }       

        $categories = ModUtil::apiFunc($this->name,'user','getCategories', 'query');
        $this->view->assign('categories', $categories);
        
                
        
        $id = FormUtil::getPassedValue('id', null, "GET", FILTER_SANITIZE_NUMBER_INT);

        if ($id) {
            // load user with id
            $this->_category = $this->entityManager->find('Tasks_Entity_Categories', $id);

            if ($this->_category) {
                $this->view->assign($this->_category->toArray()); 
            } else {
                return LogUtil::registerError($this->__f('Category with id %s not found', $id));
            }
        } else {
            $this->_category  = new Tasks_Entity_Categories();
        }
        
        return true;
    }

    

    function handleCommand(Zikula_Form_View $view, &$args)
    {   

        $url = ModUtil::url($this->name, 'admin', 'viewCategories');
        if ($args['commandName'] == 'cancel') {
            return $view->redirect($url);
        }
        
        // check for valid form
        if (!$view->isValid()) {
            return false;
        }
        

        // load form values
        $data = $view->getValues();
        

        $this->_category->merge($data);
        $this->entityManager->persist($this->_category);
        $this->entityManager->flush();
        

        return $this->view->redirect($url);
    }

}
