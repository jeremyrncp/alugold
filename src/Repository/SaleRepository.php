<?php

namespace App\Repository;

use App\Entity\Sale;
use App\Entity\Vendor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /**
     * @param \DateTimeImmutable $dateTimeImmutable
     * @return Sale[]
     */
    public function findByMonth(\DateTimeImmutable $dateTimeImmutable)
    {
        return $this->createQueryBuilder('s')
                ->where('MONTH(s.acceptedAt) = :month')
                ->andWhere('YEAR(s.acceptedAt) = :year')
                ->setParameter('month', $dateTimeImmutable->format('m'))
                ->setParameter('year', $dateTimeImmutable->format('Y'))
                ->getQuery()
                ->getResult()
        ;
    }

    /**
     * @param Vendor|null $vendor
     * @return int|mixed|string
     */
    public function findAllMonthAvailable(Vendor $vendor = null)
    {
        $query = $this->createQueryBuilder('s')
                ->select("DISTINCT DATE_FORMAT(s.acceptedAt, '%m-%Y') as date")
                ->orderBy('date', 'DESC');

        if (!is_null($vendor)) {
            $query->where('s.Vendor = :vendor')
                ->setParameter('vendor', $vendor);
        }

        return $query->getQuery()
            ->getResult();
    }


    /**
     * @param Vendor $vendor
     * @return int|mixed|string
     */
    public function findByVendor(Vendor $vendor)
    {
        return $this->createQueryBuilder('s')
                ->join('s.Proposition', 'p')
                ->where('p.Vendor = :vendor')
                ->orderBy('s.acceptedAt', 'DESC')
                ->setParameter('vendor', $vendor)
                ->getQuery()
                ->getResult()
        ;
    }
}
