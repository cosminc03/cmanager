<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

class CourseRepository extends BaseRepository
{
    public function findBySubscription(User $user)
    {
        $qb = $this->createQueryBuilder('q');
        $qb
            ->join('q.subscribers', 's')
            ->where($qb->expr()->eq('s.id', $user->getId()))
        ;

        return $qb->getQuery()->getResult();
    }

    public function findByAssociates(User $user)
    {
        $qb = $this->createQueryBuilder('q');
        $qb
            ->join('q.assistants', 'a')
            ->where($qb->expr()->eq('a.id', $user->getId()))
        ;

        return $qb->getQuery()->getResult();
    }
}
