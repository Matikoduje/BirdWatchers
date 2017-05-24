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
        $time = $this->setCorrectTime($time);
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

    public function countFindByParameters($user, $species, $time)
    {
        $time = $this->setCorrectTime($time);
        $now = new \DateTime();

        if ($user === 'all' && $species === 'all') {
            $q = $this->createQueryBuilder('o')
                ->where('o.observationDate BETWEEN :date1 AND :date2')
                ->setParameter('date1', $time->format('Y-m-d'))
                ->setParameter('date2', $now->format('Y-m-d'))
                ->select('count(o.id)')
                ->join('o.state', 'state')
                ->addSelect('state.name')
                ->groupBy('o.state')
                ->getQuery();
        } else if ($user !== 'all' && $species === 'all') {
            $q = $this->createQueryBuilder('o')
                ->where('o.user = :user')
                ->andWhere('o.observationDate BETWEEN :date1 AND :date2')
                ->setParameter('date1', $time)
                ->setParameter('date2', $now)
                ->setParameter('user', $user)
                ->select('count(o.id)')
                ->join('o.state', 'state')
                ->addSelect('state.name')
                ->groupBy('o.state')
                ->getQuery();
        } else if ($species !== 'all' && $user === 'all') {
            $q = $this->createQueryBuilder('o')
                ->where('o.species = :species')
                ->andWhere('o.observationDate BETWEEN :date1 AND :date2')
                ->setParameter('date1', $time)
                ->setParameter('date2', $now)
                ->setParameter('species', $species)
                ->select('count(o.id)')
                ->join('o.state', 'state')
                ->addSelect('state.name')
                ->groupBy('o.state')
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
                ->select('count(o.id)')
                ->join('o.state', 'state')
                ->addSelect('state.name')
                ->groupBy('o.state')
                ->getQuery();
        }

        return $q->getResult();
    }

    public function countAllObservations()
    {
        $q = $this->createQueryBuilder('observation')
            ->select('count(observation.id)')
            ->join('observation.state', 'state')
            ->addSelect('state.name')
            ->groupBy('observation.state')
            ->getQuery();
        return $q->getResult();
    }

    public function setCorrectTime($time)
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

        return $time;
    }

    public function countSpeciesObservations($id)
    {
        $q = $this->createQueryBuilder('observation')
            ->select('count(observation.id)')
            ->where('observation.species = :id')
            ->setParameter('id', $id)
            ->getQuery();
        return $q->getSingleScalarResult();
    }

    public function countUserObservations($id)
    {
        $q = $this->createQueryBuilder('observation')
            ->select('count(observation.id)')
            ->where('observation.user = :id')
            ->setParameter('id', $id)
            ->getQuery();
        return $q->getSingleScalarResult();
    }
}