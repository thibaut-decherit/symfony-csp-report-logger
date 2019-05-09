<?php

namespace App\Repository;

use App\Entity\CspViolation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CspViolation|null find($id, $lockMode = null, $lockVersion = null)
 * @method CspViolation|null findOneBy(array $criteria, array $orderBy = null)
 * @method CspViolation[]    findAll()
 * @method CspViolation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CspViolationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CspViolation::class);
    }

    /**
     * Gets possible CspViolation duplicate based on violatedDirective and documentUri.
     *
     * @param CspViolation $cspViolation
     * @return CspViolation|null
     */
    public function findDuplicate(CspViolation $cspViolation): ?CspViolation
    {
        return $this
            ->findOneBy([
                'violatedDirective' => $cspViolation->getViolatedDirective(),
                'documentUri' => $cspViolation->getDocumentUri(),
            ]);
    }

    /**
     * Increments existing CspViolation count and updates lastViolationAt datetime.
     *
     * @param CspViolation $cspViolation
     * @return mixed
     */
    public function incrementCount(CspViolation $cspViolation)
    {
        return $this
            ->createQueryBuilder('c')
            ->update()
            ->set('c.count', 'c.count + 1')
            ->set('c.lastViolationAt', 'CURRENT_TIME()')
            ->where('c.id = :id')
            ->setParameter('id', $cspViolation->getId())
            ->getQuery()
            ->execute();
    }
}
