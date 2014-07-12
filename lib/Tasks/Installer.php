<?php

/**
 * Tasks
 *
 * @copyright (c) 2011, Fabian Wuertz
 * @author Fabian Wuertz
 * @link http://fabian.wuertz.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Tasks
 */

 

class Tasks_Installer extends Zikula_AbstractInstaller
{
    /**
    * initialise the template module
    * This function is only ever called once during the lifetime of a particular
    * module instance
    */
    public function install()
    {
        // create table
        try {
            DoctrineHelper::createSchema($this->entityManager, array(
                'Tasks_Entity_Tasks',
                'Tasks_Entity_Participants',
                'Tasks_Entity_Categories'
            ));
        } catch (Exception $e) {
            LogUtil::registerStatus($e->getMessage());
            return false;
        }

        $this->createdefaultcategories();

        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
        
        // Initialisation successful
        return true;
    }

    /**
    * Upgrade the errors module from an old version
    *
    * This function must consider all the released versions of the module!
    * If the upgrade fails at some point, it returns the last upgraded version.
    *
    * @param        string   $oldVersion   version number string to upgrade from
    * @return       mixed    true on success, last valid version string or false if fails
    */
    public function upgrade($oldversion)
    {
        // Update successful
        return true;
    }

    /**
    * delete the errors module
    * This function is only ever called once during the lifetime of a particular
    * module instance
    */
    public function uninstall()
    {
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());

        // drop tables
        DoctrineHelper::dropSchema($this->entityManager, array(
            'Tasks_Entity_Tasks',
            'Tasks_Entity_Participants',
            'Tasks_Entity_Categories'
        ));
        // Delete any module variables
        $this->delVars();

        // delete categories
        CategoryRegistryUtil::deleteEntry('Tasks');
        CategoryUtil::deleteCategoriesByPath('/__SYSTEM__/Modules/Tasks', 'path');
        
        
        return true;

    }



    function createdefaultcategories()
    {

        if (!$cat = CategoryUtil::createCategory('/__SYSTEM__/Modules', 'Tasks', null, $this->__('Tasks'), $this->__('Tasks categories'))) {
            return false;
        }
        
        $rootcat = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Tasks');
        CategoryRegistryUtil::insertEntry('Tasks', 'Tasks', 'Main', $rootcat['id']);

        CategoryUtil::createCategory('/__SYSTEM__/Modules/Tasks', 'category1', null, $this->__('Category 1'), $this->__('Category 1'));
        CategoryUtil::createCategory('/__SYSTEM__/Modules/Tasks', 'category1', null, $this->__('Category 1'), $this->__('This is category 1'));

        
        LogUtil::registerStatus($this->__("Tasks: 'Main' categories created."));
        return true;
    }



    

}