<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module
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
     * @var bool
     *
     * @ORM\Column(name="is_course", type="boolean", nullable=false, options={"default"=0})
     */
    private $isCourse = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_seminar", type="boolean", nullable=false, options={"default"=0})
     */
    private $isSeminar = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="modules")
     * @ORM\JoinColumn(name="course_id", nullable=false)
     */
    private $course;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="modules")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $author;

    /**
     * @var ArrayCollection|Post[]
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="module")
     */
    private $posts;

    /**
     * @var ArrayCollection|File[]
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="module")
     */
    private $files;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->posts = new ArrayCollection();
        $this->files = new ArrayCollection();
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
     * @return Module
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
     * @return Module
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
     * Set isCourse
     *
     * @param boolean $isCourse
     *
     * @return Module
     */
    public function setIsCourse($isCourse)
    {
        $this->isCourse = $isCourse;

        return $this;
    }

    /**
     * Get isCourse
     *
     * @return boolean
     */
    public function getIsCourse()
    {
        return $this->isCourse;
    }

    /**
     * Set isSeminar
     *
     * @param boolean $isSeminar
     *
     * @return Module
     */
    public function setIsSeminar($isSeminar)
    {
        $this->isSeminar = $isSeminar;

        return $this;
    }

    /**
     * Get isSeminar
     *
     * @return boolean
     */
    public function getIsSeminar()
    {
        return $this->isSeminar;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Module
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
     * @return Module
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
     * Set course
     *
     * @param Course $course
     *
     * @return Module
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

    /**
     * Set author
     *
     * @param User $author
     *
     * @return Module
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
     * Add post
     *
     * @param Post $post
     *
     * @return Module
     */
    public function addPost(Post $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param Post $post
     */
    public function removePost(Post $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return ArrayCollection|Post[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add file
     *
     * @param File $file
     * @return Module
     */
    public function addFile(File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param File $file
     */
    public function removeFile(File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return ArrayCollection|File[]
     */
    public function getFiles()
    {
        return $this->files;
    }
}
