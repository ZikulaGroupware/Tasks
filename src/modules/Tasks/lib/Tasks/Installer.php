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
                'Tasks_Entity_CategoryMembership',
                'Tasks_Entity_Categories'
            ));
        } catch (Exception $e) {
            LogUtil::registerStatus($e->getMessage());
            return false;
        }
       

        //$this->setVar('enablecategorization', true);
        // insert default category
        //$this->createdefaultcategory('/__SYSTEM__/Modules/Tasks');

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
        /*DoctrineHelper::dropSchema($this->entityManager, array(
            'Tasks_Entity_Tasks',
            'Tasks_Entity_Participants',
            'Tasks_Entity_CategoryMembership',
            'Tasks_Entity_Categories'
        ));*/
        // Delete any module variables
        $this->delVars();

        return true;

    }



    function createdefaultcategory($regpath = '/__SYSTEM__/Modules/Global')
    {

            // load necessary classes
            Loader::loadClass('CategoryUtil');

            if (!$cat = $this->createcategory(array(
                'rootpath'    => '/__SYSTEM__/Modules',
                'name'        => 'Tasks',
                'displayname' => __('Tasks'),
                'description' => __('to-do list for Zikula')))) {
                return false;
            }

            // get the category path to insert upgraded Tasks categories
            $rootcat = CategoryUtil::getCategoryByPath($regpath);
            if ($rootcat) {
                // create an entry in the categories registry to the Main property
                $this->create_regentry($rootcat, array(
                    'modname'  => 'Tasks',
                    'table'    => 'tasks',
                    'property' => __('Main')));
            } else {
                return false;
            }

            LogUtil::registerStatus(__("Tasks: 'Main' category created."));
            return true;
    }



    /**
    * create category
    * @author Craig Heydenburg
    */
    function createcategory($catarray)
    {
        // expecting array(rootpath=>'', name=>'', displayname=>'', description=>'', attributes=>array())
        // load necessary classes
        Loader::loadClass('CategoryUtil');
        Loader::loadClassFromModule('Categories', 'Category');
        Loader::loadClassFromModule('Categories', 'CategoryRegistry');

        // get the language file
        $lang = ZLanguage::getLanguageCode();

        // get the category path to insert category
        $rootcat = CategoryUtil::getCategoryByPath($catarray['rootpath']);
        $nCat = CategoryUtil::getCategoryByPath($catarray['rootpath'] . "/" . $catarray['name']);

        if (!$nCat) {
            $cat = new Categories_DBObject_Category();
            $data = $cat->getData();
            $data['parent_id'] = $rootcat['id'];
            $data['name'] = $catarray['name'];
            if (isset($catarray['value'])) {
                $data['value'] = $catarray['value'];
            }
            $data['display_name'] = array(
                $lang => $catarray['displayname']);
            $data['display_desc'] = array(
                $lang => $catarray['description']);
            if ((isset($catarray['attributes'])) && is_array($catarray['attributes'])) {
                foreach ($catarray['attributes'] as $name => $value) {
                    $data['__ATTRIBUTES__'][$name] = $value;
                }
            }
            $cat->setData($data);
            if (!$cat->validate('admin')) {
                return false;
            }
            $cat->insert();
            $cat->update();
            return $cat->getDataField('id');
        }
        return -1;
    }


    /**
    * create an entry in the categories registry
    * @author Craig Heydenburg
    */
    function create_regentry($rootcat, $data)
    {
        // expecting $rootcat - rootcategory info
        // expecting array(modname=>'', table=>'', property=>'')
        // load necessary classes
        Loader::loadClass('CategoryUtil');
        Loader::loadClassFromModule('Categories', 'Category');
        Loader::loadClassFromModule('Categories', 'CategoryRegistry');

        $registry = new Categories_DBObject_Registry();
        $registry->setDataField('modname',     $data['modname']);
        $registry->setDataField('table',       $data['table']);
        $registry->setDataField('property',    $data['property']);
        $registry->setDataField('category_id', $rootcat['id']);
        $registry->insert();

        return true;
    }

}