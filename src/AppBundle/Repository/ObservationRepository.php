<?php


namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ObservationRepository extends EntityRepository
{
    public function findsAllByUser($user)
    {
        $q = $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery();
        return $q->getResult();
    }

    public function findByParameters($user, $species, $time)
    {

        $now = new \DateTime();

        if ($time === 'day') {
            $time = $now;
            $time->modify('-1 day');
        } else if ($time === 'week') {
            $time = $now;
            $time->modify('-1 week');
        } else if ($time === 'month') {
            $time = $now;
            $time->modify('-1 month');
        } else if ($time === 'year') {
            $time = $now;
            $time->modify('-1 year');
        } else {
            $time = $now;
            $time->modify('-30 years');
        }

        $now = new \DateTime();

        if ($user === 'all' && $species === 'all') {
            $q = $this->createQueryBuilder('o')
                ->where('o.observationDate BETWEEN :date1 AND :date2')
                ->setParameter('date1', $time->format('Y-m-d'))
                ->setParameter('date2', $now->format('Y-m-d'))
                ->getQuery();
        } else if ($user !== 'all' && $species === 'all') {
            $q = $this->createQueryBuilder('o')
                ->where('o.user = :user')
                ->andWhere('o.observationDate BETWEEN :date1 AND :date2')
                ->setParameter('date1', $time)
                ->setParameter('date2', $now)
                ->setParameter('user', $user)
                ->getQuery();
        } else if ($species !== 'all' && $user === 'all') {
            $q = $this->createQueryBuilder('o')
                ->where('o.species = :species')
                ->andWhere('o.observationDate BETWEEN :date1 AND :date2')
                ->setParameter('date1', $time)
                ->setParameter('date2', $now)
                ->setParameter('species', $species)
                ->getQuery();
        } else {
            $q = $this->createQueryBuilder('o')
                ->where('o.user = :user')
                ->andWhere('o.species = :species')
                ->andWhere('o.observationDate BETWEEN :date1 AND :date2')
                ->setParameter('user', $user)
                ->setParameter('species', $species)
                ->setParameter('date1', $time)
                ->setParameter('date2', $now)
                ->getQuery();
        }

        return $q->getResult();
    }
}