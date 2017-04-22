<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Announcements
 *
 * @ORM\Table(name="announcement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnnouncementRepository")
 */
class Announcement
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="announcements")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $createdBy;

    /**
     * @var Course
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="announcements")
     * @ORM\JoinColumn(name="course_id", nullable=true)
     */
    private $course;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * Set title
     *
     * @param string $title
     *
     * @return Announcement
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Announcement
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Announcement
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
     * Set updatedAt
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Announcement
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     *
     * @return Announcement
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set course
     *
     * @param Course $course
     *
     * @return Announcement
     */
    public function setCourse(Course $course = null)
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

    /**
     * Returns createdAt.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdAt")
     *
     * @return string
     */
    public function getCreatedAtFormatted()
    {
        return $this->createdAt ? $this->createdAt->format('d/m/Y') : null;
    }

    /**
     * Returns updatedAt.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("updatedAt")
     *
     * @return string
     */
    public function getUpdatedAtFormatted()
    {
        return $this->updatedAt ? $this->updatedAt->format('d/m/Y') : null;
    }

    /**
     * Returns the User id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdBy")
     *
     * @return string
     */
    public function getCreatedById()
    {
        return $this->createdBy ? $this->createdBy->getId() : null;
    }

    /**
     * Returns the User username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdByUserName")
     *
     * @return string
     */
    public function getCreatedByUserName()
    {
        return $this->createdBy ? $this->createdBy->getUsername() : null;
    }

    /**
     * Returns the Course id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("course")
     *
     * @return string
     */
    public function getCourseId()
    {
        return $this->course ? $this->course->getId() : null;
    }

    /**
     * Returns the Course title.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("courseTitle")
     *
     * @return string
     */
    public function getCourseTitle()
    {
        return $this->course ? $this->course->getTitle() : null;
    }
}
