<?php

namespace App\Repository;

use App\Entity\Lecon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lecon>
 *
 * @method Lecon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lecon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lecon[]    findAll()
 * @method Lecon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeconRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lecon::class);
    }

    public function save(Lecon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Lecon $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUser(int $idUser){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "select distinct lecon.id, lecon.codevehicule_id, lecon.reglee, lecon.date_end, lecon.date_start  from bddautoecoleweb.lecon, lecon_user, user
where lecon_user.user_id=$idUser and lecon_user.lecon_id=lecon.id";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery();


        return $resultSet->fetchAllAssociative();
    }

    public function findConflictingLessons(Lecon $lesson): array
    {
        $qb = $this->createQueryBuilder('l')
            ->where('l.dateStart = :start_time')
            ->setParameter('start_time', $lesson->getDateStart());

        if ($lesson->getId()) {
            $qb->andWhere('l.id != :id')
                ->setParameter('id', $lesson->getId());
        }

        return $qb->getQuery()->getResult();
    }

    public function userCalendar(int $idUser){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT l FROM App\Entity\Lecon l JOIN l.codeuser lc WHERE lc.id= ?1')->setParameter('1',$idUser);
        return $query->getResult();
    }
//SELECT u FROM User u JOIN Banlist b WITH u.email = b.email
//    /**
//     * @return Lecon[] Returns an array of Lecon objects
//     */
    public function findByExampleField(int $value): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
       ;
    }

//    public function findOneBySomeField($value): ?Lecon
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
