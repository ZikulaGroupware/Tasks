<?php

/**
 * Copyright LuMicuLa Team 2011
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package LuMicuLa
 * @link http://code.zikula.org/LuMicuLa
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class Tasks_Controller_Admin extends Zikula_AbstractController
{
    /**
     * Post initialise.
     *
     * Run after construction.
     *
     * @return void
     */
    
    protected function postInitialize()
    {
        // Disable caching by default.
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    }
    
    
    public function main()
    {
        return $this->view->fetch('admin/main.tpl');
    }
    
    
   /**
    * Import a CSV input
    *
    * @param array $args POST/REQUEST vars
    * @return The view var
    */
    public function viewCategories($args)
    {
        $form = FormUtil::newForm('AlternativeCategories', $this);
        $handler =new AlternativeCategories_Handler_Modify();
        $handler->setEntity('Tasks_Entity_Categories');
        return $form->execute('admin/modify.tpl', $handler);
    }
    
    
    
   /**
    * Import a CSV input
    *
    * @param array $args POST/REQUEST vars
    * @return The view var
    */
    public function importCSV($args)
    {
        $form = FormUtil::newForm('Tasks', $this);
        return $form->execute('admin/importCSV.tpl', new Tasks_Handler_ImportCSV());
    }
    
     
}
