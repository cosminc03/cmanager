<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var ArrayCollection|Course[]
     *
     * @ORM\OneToMany(targetEntity="Course", mappedBy="author")
     */
    private $courses;

    /**
     * @var ArrayCollection|Course[]
     *
     * @ORM\ManyToMany(targetEntity="Course", mappedBy="assistants")
     */
    private $labs;

    /**
     * @var ArrayCollection|Module[]
     *
     * @ORM\OneToMany(targetEntity="Module", mappedBy="author")
     */
    private $modules;

    /**
     * @var ArrayCollection|Announcement[]
     *
     * @ORM\OneToMany(targetEntity="Announcement", mappedBy="course")
     */
    private $announcements;

    /**
     * @var ArrayCollection|Post[]
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="createdBy")
     */
    private $posts;

    public function __construct()
    {
        parent::__construct();

        $this->courses = new ArrayCollection();
        $this->labs = new ArrayCollection();
        $this->modules = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime|null $dateOfBirth
     *
     * @return User
     */
    public function setDateOfBirth(\DateTime $dateOfBirth = null)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime|null
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Add course
     *
     * @param Course $course
     *
     * @return User
     */
    public function addCourse(Course $course)
    {
        $this->courses[] = $course;

        return $this;
    }

    /**
     * Remove course
     *
     * @param Course $course
     */
    public function removeCourse(Course $course)
    {
        $this->courses->removeElement($course);
    }

    /**
     * Get courses
     *
     * @return ArrayCollection|Course[]
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Add lab
     *
     * @param Course $lab
     *
     * @return User
     */
    public function addLab(Course $lab)
    {
        $this->labs[] = $lab;

        return $this;
    }

    /**
     * Remove lab
     *
     * @param Course $lab
     */
    public function removeLab(Course $lab)
    {
        $this->labs->removeElement($lab);
    }

    /**
     * Get labs
     *
     * @return ArrayCollection|Course[]
     */
    public function getLabs()
    {
        return $this->labs;
    }

    /**
     * Add module
     *
     * @param Module $module
     *
     * @return User
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
     * Add announcement
     *
     * @param Announcement $announcement
     *
     * @return User
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
     * Add post
     *
     * @param Post $post
     *
     * @return User
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
}
