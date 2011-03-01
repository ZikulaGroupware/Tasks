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

class Tasks_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = __('Tasks');
        $meta['description']    = __('A simple to-do list manger');
        //! module url (lower case without spaces and different to displayname)
        $meta['url']      = __('Tasks');
        $meta['version']        = '2.0.0';
        $meta['author']         = 'Fabian Wuertz';
        $meta['contact']        = 'http://fabian.wuertz.org';
        $meta['securityschema']   = array('Tasks::'  => '::');
        return $meta;
    }
}


