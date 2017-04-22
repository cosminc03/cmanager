<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serializer;

/**
 * Course
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseRepository")
 * @UniqueEntity(fields="title", message="Title field should not be blank")
 */
class Course
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=15)
     */
    private $abbreviation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courses")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $author;

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

    /**
     * @var ArrayCollection|Module[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="Module", mappedBy="course")
     */
    private $modules;

    /**
     * @var string
     *
     * @ORM\Column(name="link_to_grades", type="string", length=255, nullable=true)
     */
    private $linkToGrades;

    /**
     * @var ArrayCollection|User[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="labs")
     * @ORM\JoinTable(
     *     name="course_assistant",
     *     joinColumns={
     *          @ORM\JoinColumn(name="course_id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="assistant_id")
     *     }
     * )
     */
    private $assistants;

    /**
     * @var ArrayCollection|Announcement[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="Announcement", mappedBy="course")
     */
    private $announcements;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->modules = new ArrayCollection();
        $this->assistants = new ArrayCollection();
        $this->announcements = new ArrayCollection();
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
     * @return Course
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
     * Set abbreviation
     *
     * @param string $abbreviation
     *
     * @return Course
     */
    public function setAbbreviation($abbreviation)
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    /**
     * Get abbreviation
     *
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Course
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
     * @return Course
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set linkToGrades
     *
     * @param string $linkToGrades
     *
     * @return Course
     */
    public function setLinkToGrades($linkToGrades)
    {
        $this->linkToGrades = $linkToGrades;

        return $this;
    }

    /**
     * Get linkToGrades
     *
     * @return string
     */
    public function getLinkToGrades()
    {
        return $this->linkToGrades;
    }

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Course
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
     * Add module
     *
     * @param Module $module
     *
     * @return Course
     */
    public function addModule(Module $module)
    {
        $this->modules[] = $module;

        return $this;
    }

    /**
     * Remove module
     *
     * @param Module $module
     */
    public function removeModule(Module $module)
    {
        $this->modules->removeElement($module);
    }

    /**
     * Get modules
     *
     * @return ArrayCollection|Module[]
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Add assistant
     *
     * @param User $assistant
     *
     * @return Course
     */
    public function addAssistant(User $assistant)
    {
        $this->assistants[] = $assistant;

        return $this;
    }

    /**
     * Remove assistant
     *
     * @param User $assistant
     */
    public function removeAssistant(User $assistant)
    {
        $this->assistants->removeElement($assistant);
    }

    /**
     * Get assistants
     *
     * @return ArrayCollection|User[]
     */
    public function getAssistants()
    {
        return $this->assistants;
    }

    /**
     * Add announcement
     *
     * @param Announcement $announcement
     *
     * @return Course
     */
    public function addAnnouncement(Announcement $announcement)
    {
        $this->announcements[] = $announcement;

        return $this;
    }

    /**
     * Remove announcement
     *
     * @param Announcement $announcement
     */
    public function removeAnnouncement(Announcement $announcement)
    {
        $this->announcements->removeElement($announcement);
    }

    /**
     * Get announcements
     *
     * @return ArrayCollection|Announcement[]
     */
    public function getAnnouncements()
    {
        return $this->announcements;
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
     * @Serializer\SerializedName("author")
     *
     * @return string
     */
    public function getAuthorId()
    {
        return $this->author ? $this->author->getId() : null;
    }

    /**
     * Returns the User username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("authorUserName")
     *
     * @return string
     */
    public function getCreatedByUserName()
    {
        return $this->author ? $this->author->getUsername() : null;
    }
}
