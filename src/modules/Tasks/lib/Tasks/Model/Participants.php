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

class Tasks_Model_Participants extends Doctrine_Record
{
    /**
     * Set table definition.
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $this->setTableName('tasks_participants');
        $this->hasColumn('tid', 'integer',  16, array(
           'notnull' => true,
           'primary' => true
        ));
        $this->hasColumn('uname', 'string',  55, array(
           'notnull' => true,
           'primary' => true
        ));

    }
    
    
}
