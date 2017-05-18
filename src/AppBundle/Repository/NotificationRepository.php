<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function findAllNotifications($courses)
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q')
            ->where('q.course IN (:courses)')
            ->setParameter('courses', $courses->toArray())
            ->orderBy('q.createdAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function countUnseenNotifications(User $user, $courses)
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q')
            ->where('q.course IN (:courses)')
            ->andWhere(':user NOT IN (q.readers)')
            ->setParameter('courses', $courses->toArray())
            ->setParameter('user', $user)
            ->orderBy('q.createdAt', 'DESC')
        ;

        return $qb->getQuery()->getResult();
    }
}
