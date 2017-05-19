<?php

namespace AppBundle\Services;

use AppBundle\Entity\Announcement;
use AppBundle\Entity\Course;
use AppBundle\Entity\Module;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class NotificationService
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $type
     * @param User   $author
     * @param Course $course
     * @param Module|Announcement $relatedEntity
     * @param string $msg
     */
    public function addNotificationByType($type, User $author, Course $course, $relatedEntity, $msg)
    {
        $notification = new Notification();

        $notification
            ->setCourse($course)
            ->setAuthor($author)
            ->setType($type)
            ->setMessage($msg)
            ->setRelatedEntityId($relatedEntity->getId())
        ;

        $this->em->persist($notification);
        $this->em->flush();

        return;
    }
}