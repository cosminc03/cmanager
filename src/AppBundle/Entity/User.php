<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const GRAVATAR_BASE_URL = 'https://www.gravatar.com/avatar/';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=128, nullable=true)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var ArrayCollection|Course[]
     *
     * @ORM\OneToMany(targetEntity="Course", mappedBy="author")
     */
    private $courses;

    /**
     * @var ArrayCollection|Course[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="Course", mappedBy="assistants")
     */
    private $labs;

    /**
     * @var ArrayCollection|Module[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="Module", mappedBy="author")
     */
    private $modules;

    /**
     * @var ArrayCollection|Announcement[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="Announcement", mappedBy="course")
     */
    private $announcements;

    /**
     * @var ArrayCollection|Post[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="createdBy")
     */
    private $posts;

    /**
     * @Vich\UploadableField(mapping="user_avatars", fileNameProperty="avatar")
     * @Serializer\Exclude()
     *
     * @var File
     */
    private $avatarFile;

    /**
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     *
     * @Serializer\Exclude()
     *
     * @var string
     */
    private $avatar;

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
        parent::__construct();

        $this->createdAt = new \DateTime();
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
     * Set avatarFile.
     *
     * @param File|null $image
     *
     * @return User
     */
    public function setAvatarFile(File $image = null)
    {
        $this->avatarFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Get avatarFile.
     *
     * @return File
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
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

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
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
     * Get gravatar url.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("gravatar")
     *
     * @return string
     */
    public function getGravatar()
    {
        $email = md5(strtolower(trim($this->getEmail())));
        $gravatarUrl = sprintf('%s%s?d=identicon', self::GRAVATAR_BASE_URL, $email);

        return $gravatarUrl;
    }
}
