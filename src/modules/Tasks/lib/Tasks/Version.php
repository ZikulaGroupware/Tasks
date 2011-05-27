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

class Tasks_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = __('Tasks');
        $meta['description']    = __('A simple to-do list manger');
        //! module url (lower case without spaces and different to displayname)
        $meta['url']            = __('Tasks');
        $meta['version']        = '2.0.0';
        $meta['author']         = 'Fabian Wuertz';
        $meta['contact']        = 'http://fabian.wuertz.org';
        $meta['securityschema'] = array('Tasks::'  => '::');
        $meta['capabilities']   = array(HookUtil::SUBSCRIBER_CAPABLE => array('enabled' => true));
        return $meta;
    }
    
    protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.tasks.ui_hooks.editor', 'ui_hooks', $this->__('Tasks editor'));
        $bundle->addEvent('display_view', 'tasks.ui_hooks.editor.display_view');
        $this->registerHookSubscriberBundle($bundle);
        
        $bundle = new Zikula_HookManager_SubscriberBundle($this->name, 'subscriber.tasks.filter_hooks.description', 'filter_hooks', $this->__('Tasks filters'));
        $bundle->addEvent('filter', 'tasks.filter_hooks.description.filter');
        $this->registerHookSubscriberBundle($bundle);
    }
}


