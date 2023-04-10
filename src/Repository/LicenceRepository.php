<?php

namespace App\Repository;

use App\Entity\Licence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Licence>
 *
 * @method Licence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Licence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Licence[]    findAll()
 * @method Licence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LicenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Licence::class);
    }

    public function save(Licence $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Licence $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findConflictingLicenses(Licence $licence, int $userId): array
    {
        $qb = $this->createQueryBuilder('l')
            ->andWhere('l.codeuser = :user')
            ->andWhere('l.codecategorie = :categorie')
            ->setParameter('user', $userId)
            ->setParameter('categorie', $licence->getCodecategorie());

        if ($licence->getId()) {
            $qb->andWhere('l.id != :id')
                ->setParameter('id', $licence->getId());
        }

        return $qb->getQuery()->getResult();
    }

    public function userLicence(int $idUser){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT l FROM App\Entity\Licence l JOIN l.codeuser lc WHERE lc.id= ?1')->setParameter('1',$idUser);
        return $query->getResult();
    }
//    /**
//     * @return Licence[] Returns an array of Licence objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Licence
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
