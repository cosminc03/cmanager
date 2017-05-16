<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Homework
 *
 * @ORM\Table(name="homework")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HomeworkRepository")
 */
class Homework
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
     * @var string
     *
     * @ORM\Column(name="observation", type="text")
     */
    private $observation;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_course_homework", type="boolean", nullable=false, options={"default"=0})
     */
    private $isCourseHomework = false;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    private $deadline;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="homework")
     * @ORM\JoinColumn(name="course_id", nullable=false)
     */
    private $course;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="homework")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $author;

    /**
     * @var ArrayCollection|Post[]
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="homework")
     */
    private $posts;

    /**
     * @var ArrayCollection|File[]
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="homework")
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
     * @return Homework
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
     * @return Homework
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
     * Set observation
     *
     * @param string $observation
     *
     * @return Homework
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Set isCourseHomework
     *
     * @param boolean $isCourseHomework
     *
     * @return Homework
     */
    public function setIsCourseHomework($isCourseHomework)
    {
        $this->isCourseHomework = $isCourseHomework;

        return $this;
    }

    /**
     * Get isCourseHomework
     *
     * @return boolean
     */
    public function getIsCourseHomework()
    {
        return $this->isCourseHomework;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Homework
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
     * @return Homework
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
     * Set deadline
     *
     * @param \DateTime|null $deadline
     *
     * @return Homework
     */
    public function setDeadline(\DateTime $deadline = null)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime|null
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set course
     *
     * @param Course $course
     *
     * @return Homework
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
     * @return Homework
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
     * @return Homework
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
     * @return Homework
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
