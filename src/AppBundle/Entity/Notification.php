<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 */
class Notification
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=false)
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="related_entity_id", type="integer", nullable=true)
     */
    private $relatedEntityId;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="readNotifications")
     * @ORM\JoinTable(
     *     name="notification_reader",
     *     joinColumns={
     *          @ORM\JoinColumn(name="notification_id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="reader_id")
     *     }
     * )
     */
    private $readers;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="notifications")
     * @ORM\JoinColumn(name="course_id", nullable=false)
     */
    private $course;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->readers = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set relatedEntityId
     *
     * @param integer $relatedEntityId
     *
     * @return Notification
     */
    public function setRelatedEntityId($relatedEntityId)
    {
        $this->relatedEntityId = $relatedEntityId;

        return $this;
    }

    /**
     * Get relatedEntityId
     *
     * @return integer 
     */
    public function getRelatedEntityId()
    {
        return $this->relatedEntityId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Notification
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add reader
     *
     * @param User $reader
     *
     * @return Notification
     */
    public function addReader(User $reader)
    {
        $this->readers[] = $reader;

        return $this;
    }

    /**
     * Remove reader
     *
     * @param User $reader
     */
    public function removeReader(User $reader)
    {
        $this->readers->removeElement($reader);
    }

    /**
     * Get readers
     *
     * @return ArrayCollection|User[]
     */
    public function getReaders()
    {
        return $this->readers;
    }

    /**
     * Set course
     *
     * @param Course $course
     *
     * @return Notification
     */
    public function setCourse(Course $course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }
}
