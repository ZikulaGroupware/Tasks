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
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Tasks entity class.
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Tasks_Entity_Tasks extends Zikula_EntityAccess {

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tid;

    public function setTid($tid) {
        $this->tid = $tid;
    }

    public function getTid() {
        return $this->tid;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $title;

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="text")
     */
    private $description;

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="date", nullable="true")
     */
    private $done_date = null;

    public function setDone_date($done_date) {
        $this->done_date = $done_date;
    }

    public function getDone_date() {
        return $this->done_date;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     */
    private $cr_uid;

    public function getCr_uid() {
        return $this->cr_uid;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @var datetime
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $cr_date;

    public function getCr_date() {
        return $this->cr_date;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="date", nullable="true")
     */
    private $deadline = null;

    public function setDeadline($deadline) {
        if (!empty($deadline)) {
            $this->deadline = new \DateTime($deadline);
        }
        return true;
    }

    public function getDeadline() {
        return $this->deadline;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="integer", length=3)
     */
    private $progress = 0;

    public function setProgress($progress) {
        $this->progress = $progress;
    }

    public function getProgress() {
        return $this->progress;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="integer", length=1)
     */
    private $priority = 1;

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getPriority() {
        return $this->priority;
    }

    /**
     * The following are annotations which define the id field.
     *
     * @ORM\Column(type="boolean")
     */
    private $approved = false;

    public function setApproved($approved) {
        $this->approved = $approved;
    }

    public function getApproved() {
        return $this->approved;
    }

    /**
     * @ORM\OneToMany(targetEntity="Tasks_Entity_Categories", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true, indexBy="categoryRegistryId")
     */
    private $categories;

    public function getCategories() {
        return $this->categories;
    }

    public function setCategories($categories) {
        $this->categories = $categories;
    }

    public function getCategories2() {
        $output = array();
        foreach ($this->categories as $category) {
            $test = $category->toArray();
            $output[] = $test['category']['name'];
        }
        return implode(', ', $output);
    }

    /**
     * participants
     *
     * @ORM\OneToMany(targetEntity="Tasks_Entity_Participants", 
     *                mappedBy="entity", cascade={"all"},
     *                orphanRemoval=true, indexBy="uname")
     */
    private $participants;

    public function getParticipants() {
        return $this->participants;
    }

    public function getParticipants2() {
        $output = array();
        foreach ($this->participants as $participant) {
            $output[] = $participant->getUname();
        }
        return implode(', ', $output);
    }

    public function setParticipants($participants) {
        
        $participants = explode(',', $participants);
        $participants = array_combine($participants, $participants);

        foreach ($this->participants as $key => $value) {
            if (!in_array($key, $participants)) {
                unset($this->participants[$key]);
            } else {
                unset($participants[$key]);
            }
        }
        
        foreach ($participants as $participant) {
            $this->participants[$participant] = new Tasks_Entity_Participants($participant, $this);
        }
        
    }

    public function __construct() {
        $this->categories = new Doctrine\Common\Collections\ArrayCollection();
        $this->participants = new Doctrine\Common\Collections\ArrayCollection();
    }

}