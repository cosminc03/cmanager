<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @Vich\Uploadable
 * @UniqueEntity(fields="username", message="unique.username")
 * @UniqueEntity(fields="email", message="unique.email")
 */
class User extends BaseUser
{
    const GENDER_MALE = 'gender.male';
    const GENDER_FEMALE = 'gender.female';

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_STUDENT = 'ROLE_STUDENT';
    const ROLE_PROFESSOR = 'ROLE_PROFESSOR';
    const ROLE_ASSOCIATE = 'ROLE_ASSOCIATE';

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
     * @ORM\Column(name="first_name", type="string", nullable=false)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", nullable=false)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_number", type="string", nullable=true)
     */
    private $registrationNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", nullable=false)
     */
    private $nationality;

    /**
     * @var string
     *
     * @ORM\Column(name="citizenship", type="string", nullable=false)
     */
    private $citizenship;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=128, nullable=false)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="date", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="year_of_study", type="string", nullable=true)
     */
    private $yearOfStudy;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=20, nullable=false)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", nullable=true)
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="linked_in", type="string", nullable=true)
     */
    private $linkedIn;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="gplus", type="string", nullable=true)
     */
    private $gplus;

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
     * @var ArrayCollection|Homework[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="Homework", mappedBy="author")
     */
    private $homework;

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
     * @var ArrayCollection|Course[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="Course", mappedBy="subscribers")
     */
    private $subscribedCourses;

    /**
     * @var ArrayCollection|Notification[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="author")
     */
    private $notifications;

    /**
     * @var ArrayCollection|Notification[]
     *
     * @ORM\ManyToMany(targetEntity="Notification", mappedBy="readers")
     */
    private $readNotifications;

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
        $this->homework = new ArrayCollection();
        $this->announcements = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->readNotifications = new ArrayCollection();
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
     * Add homework
     *
     * @param Homework $hmwork
     *
     * @return User
     */
    public function addHomework(Homework $hmwork)
    {
        $this->homework[] = $hmwork;

        return $this;
    }

    /**
     * Remove homework
     *
     * @param Homework $hmwork
     */
    public function removeHomework(Homework $hmwork)
    {
        $this->homework->removeElement($hmwork);
    }

    /**
     * Get homework
     *
     * @return ArrayCollection|Homework[]
     */
    public function getHomework()
    {
        return $this->homework;
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
     * Get full name
     *
     * @return string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->getFirstName(), $this->getLastName());
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
     * Set description
     *
     * @param string $description
     * @return User
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

    /**
     * Set registrationNumber
     *
     * @param string $registrationNumber
     * @return User
     */
    public function setRegistrationNumber($registrationNumber)
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    /**
     * Get registrationNumber
     *
     * @return string 
     */
    public function getRegistrationNumber()
    {
        return $this->registrationNumber;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     * @return User
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string 
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set citizenship
     *
     * @param string $citizenship
     * @return User
     */
    public function setCitizenship($citizenship)
    {
        $this->citizenship = $citizenship;

        return $this;
    }

    /**
     * Get citizenship
     *
     * @return string 
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set skype
     *
     * @param string $skype
     * @return User
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set linkedIn
     *
     * @param string $linkedIn
     * @return User
     */
    public function setLinkedIn($linkedIn)
    {
        $this->linkedIn = $linkedIn;

        return $this;
    }

    /**
     * Get linkedIn
     *
     * @return string 
     */
    public function getLinkedIn()
    {
        return $this->linkedIn;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return User
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set gplus
     *
     * @param string $gplus
     * @return User
     */
    public function setGplus($gplus)
    {
        $this->gplus = $gplus;

        return $this;
    }

    /**
     * Get gplus
     *
     * @return string 
     */
    public function getGplus()
    {
        return $this->gplus;
    }

    /**
     * Set yearOfStudy
     *
     * @param string $yearOfStudy
     * @return User
     */
    public function setYearOfStudy($yearOfStudy)
    {
        $this->yearOfStudy = $yearOfStudy;

        return $this;
    }

    /**
     * Get yearOfStudy
     *
     * @return string 
     */
    public function getYearOfStudy()
    {
        return $this->yearOfStudy;
    }

    /**
     * Add subscribedCourses
     *
     * @param Course $subscribedCourses
     * @return User
     */
    public function addSubscribedCourse(Course $subscribedCourses)
    {
        $this->subscribedCourses[] = $subscribedCourses;

        return $this;
    }

    /**
     * Remove subscribedCourses
     *
     * @param Course $subscribedCourses
     */
    public function removeSubscribedCourse(Course $subscribedCourses)
    {
        $this->subscribedCourses->removeElement($subscribedCourses);
    }

    /**
     * Get subscribedCourses
     *
     * @return ArrayCollection|Course[]
     */
    public function getSubscribedCourses()
    {
        return $this->subscribedCourses;
    }

    /**
     * Add notification
     *
     * @param Notification $notification
     *
     * @return User
     */
    public function addNotification(Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param Notification $notification
     */
    public function removeNotification(Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return ArrayCollection|Notification[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add readNotification
     *
     * @param Notification $readNotification
     *
     * @return User
     */
    public function addReadNotification(Notification $readNotification)
    {
        $this->readNotifications[] = $readNotification;

        return $this;
    }

    /**
     * Remove readNotification
     *
     * @param Notification $readNotification
     */
    public function removeReadNotification(Notification $readNotification)
    {
        $this->readNotifications->removeElement($readNotification);
    }

    /**
     * Get readNotifications
     *
     * @return ArrayCollection|Notification[]
     */
    public function getReadNotifications()
    {
        return $this->readNotifications;
    }
}
