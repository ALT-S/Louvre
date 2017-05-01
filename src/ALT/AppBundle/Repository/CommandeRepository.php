<?php

namespace ALT\AppBundle\Repository;

/**
 * CommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNbBillets(\DateTime $date)
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->select('SUM(c.nbBillets)')
            ->where('c.dateVisite = :date')
            ->setParameter('date', $date->format('Y-m-d'));

        return $qb->getQuery()->getSingleScalarResult();
    }
}
