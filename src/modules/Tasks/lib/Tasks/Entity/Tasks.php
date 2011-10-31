<?php

/**
 * Copyright Wikula Team 2011
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/GPLv3 (or at your option, any later version).
 * @package Piwik
 * @link https://github.com/phaidon/Wikula
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Wikula links entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Tasks_Entity_Tasks extends Zikula_EntityAccess
{
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tid;
    
    
    public function setTid($tid)
    {
        $this->tid = $tid;
    }
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $title;
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="text")
     */
    private $description;
    
    public function setDescription($description)
    {
        $this->description = $description;
    }
 
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="date", nullable="true")
     */
    private $done_date = null;
    
    public function setDone_date($done_date)
    {
        $this->done_date = $done_date;
    }
    
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="integer", length=16)
     */
    private $cr_uid;
    
    public function setCr_uid($cr_uid)
    {
        $this->cr_uid = $cr_uid;
    }
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="date", nullable="true")
     */
    private $cr_date = null;

    public function setCr_date($cr_date)
    {
        $this->cr_date = $cr_date;
    }
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="date", nullable="true")
     */
    private $deadline = null; 
    
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;
    }
    
    
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="integer", length=3)
     */
    private $progress = 0;
    
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }
    
    
    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="integer", length=1)
     */
    private $priority = 1;
    
    
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
    

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="boolean")
     */
    private $approved = false;
    
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }
    
    
    /**
     * @ORM\OneToMany(targetEntity="Tasks_Entity_CategoryMembership", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true)
     */
    private $categories;
    
    
    
    
    
    public function setCategories($category)
    {
        $this->categories[] = new Tasks_Entity_CategoryMembership($category, $this);
    }
    
    
    /**
     * participants
     *
     * @ORM\OneToMany(targetEntity="Tasks_Entity_Participants", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true)
     */
    private $participants;
    
    
    public function setParticipants($participant)
    {
        $this->participants[] = new Tasks_Entity_Participants($participant, $this);
    }
    
    
    


    
    public function __construct()
    {
        $this->categories   = new Doctrine\Common\Collections\ArrayCollection();
        $this->participants = new Doctrine\Common\Collections\ArrayCollection();
    }
    
}