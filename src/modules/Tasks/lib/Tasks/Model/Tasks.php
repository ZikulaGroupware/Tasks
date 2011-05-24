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

class Tasks_Model_Tasks extends Doctrine_Record
{
    /**
     * Set table definition.
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $this->setTableName('tasks');
        $this->hasColumn('tid',   'integer', 16, array(
            'unique'  => true,
            'primary' => true,
            'notnull' => true,
            'autoincrement' => true
        ));
        $this->hasColumn('title', 'string',  64, array(
           'notnull' => true
        ));
        $this->hasColumn('description', 'clob');
        $this->hasColumn('done_date',   'date');
        $this->hasColumn('cr_uid',       'integer', 16, array(
           'notnull' => true
        ));
        $this->hasColumn('cr_date',     'date', array(
          'notnull' => true
        ));
        $this->hasColumn('deadline',    'date');
        $this->hasColumn('progress',    'integer',  3, array(
           'default' => 0
        ));
        $this->hasColumn('priority',    'integer',  1, array(
           'default' => 1
        ));
        $this->hasColumn('approved',    'integer',  1, array(
           'default' => 0
        ));
    }
    
    /**
     * Record setup.
     *
     * @return void
     */
    public function setUp()
    {
        $this->actAs('Zikula_Doctrine_Template_Categorisable');
        $this->hasOne('Tasks_Model_Participants', array(
            'local' => 'tid',
            'foreign' => 'tid',
            'onDelete' => 'CASCADE')
        );
    }
}
