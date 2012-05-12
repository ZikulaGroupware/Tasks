<?php

/**
 * Copyright Tasks Team 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/GPLv3 (or at your option, any later version).
 * @package Tasks
 * @link https://github.com/phaidon/Tasks
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

use Doctrine\ORM\Mapping as ORM;


/**
 * Tasks entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="tasks_categories",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="cat_unq",columns={"registryId", "categoryId", "entityId"})})
 */
class Tasks_Entity_Categories extends Zikula_Doctrine2_Entity_EntityCategory
{
    
    /**
     * @ORM\ManyToOne(targetEntity="Tasks_Entity_Tasks", inversedBy="categories")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="tid")
     * @var Tasks_Entity_Tasks
     */
    private $entity;

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
    
}