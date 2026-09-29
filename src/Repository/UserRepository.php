<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use function Symfony\Component\String\s;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    //graph
    public function nbCategorie(int $userId)
        //nombre de lecon par categorie
        //pour eleve
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT c.libelle as libelle, COUNT(l.id) as nbLecons
                FROM lecon l
                INNER JOIN lecon_user lu on l.id = lu.lecon_id
                INNER JOIN user u on lu.user_id = u.id
                INNER JOIN vehicule v on l.codevehicule_id = v.id
                INNER JOIN categorie c on v.codecategorie_id = c.id
                WHERE u.id = :userId
                GROUP BY c.libelle";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);


        return $resultSet->fetchAllAssociative();
    }

    public function nbLeconMoniteur(int $userId)
    {
//        $em = $this->getEntityManager();
//
//        $query = $em->createQuery('
//           SELECT l, count(u.id)
//           FROM App\Entity\Lecon l
//           JOIN l.codeuser u
//           WHERE u.id = :id
//        ')->setParameter('id', $userId);
//
//        return $query->getResult();
        //pour eleve
        //nombre de lecon par moniteur
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT u.nom as nom, COUNT(lecon_id) as nbLecons
                from lecon l
                INNER JOIN lecon_user lu on l.id = lu.lecon_id
                INNER JOIN user u on lu.user_id = u.id
                WHERE lecon_id IN (SELECT lecon.id
                                   FROM lecon
                                    INNER JOIN lecon_user  on lecon.id = lecon_user.lecon_id
                                   WHERE user_id = :userId
                                   )
                AND user_id not like :userId
                GROUP by u.nom
                ";
//        AND um.roles LIKE '%ROLE_ADMIN%'

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();


    }

    public function prixCateg(int $userId)
    {
        //prix par categorie
        //pour moni
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT c.libelle as libelle, SUM(c.prix) as somme
                FROM lecon l
                INNER JOIN vehicule v ON v.id=l.codevehicule_id
                INNER JOIN categorie c ON c.id=v.codecategorie_id
                INNER JOIN lecon_user lu ON lu.lecon_id = l.id
                INNER JOIN user um ON um.id=lu.user_id
                WHERE um.id=:userId
                GROUP BY c.libelle";
//        AND um.roles LIKE '%ROLE_ADMIN%'

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }
    public function nbLeconCateg(int $userId)
    {
        //nombre de lecon par categ
        //pour moni
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT c.libelle as libelle2, COUNT(c.id) as nb
                FROM categorie c
                INNER JOIN vehicule v ON c.id=v.codecategorie_id
                INNER JOIN lecon l ON l.codevehicule_id = v.id
                INNER JOIN lecon_user lu ON lu.lecon_id = l.id
                INNER JOIN user um ON um.id=lu.user_id
                WHERE um.id=:userId
                GROUP BY c.libelle";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }

    public function getMontantPermis(int $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT SUM(categorie.prix) as prix FROM `categorie` 
                INNER JOIN vehicule ON vehicule.codecategorie_id = categorie.id
                INNER JOIN lecon ON lecon.codevehicule_id = vehicule.id
                INNER JOIN lecon_user ON lecon_user.lecon_id=lecon.id
                WHERE lecon_user.user_id=:userId ";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }

    public function getMontantRestant(int $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT SUM(categorie.prix) as prixRestant FROM `categorie` 
                INNER JOIN vehicule ON vehicule.codecategorie_id = categorie.id
                INNER JOIN lecon ON lecon.codevehicule_id = vehicule.id
                INNER JOIN lecon_user ON lecon_user.lecon_id=lecon.id
                WHERE lecon_user.user_id=:userId
                AND lecon.reglee = 0";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }

    public function getNbLeconByEleve(int $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COUNT(lecon.id) as nbLecon FROM lecon 
                INNER JOIN lecon_user ON lecon_user.lecon_id = lecon.id
                WHERE lecon_user.user_id=:userId";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }

    public function getVehiculeUseByEleve(int $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT vehicule.Marque, vehicule.Modele, COUNT(lecon.id)AS nbLecon
                FROM vehicule
                INNER JOIN lecon ON lecon.codevehicule_id=vehicule.id
                INNER JOIN lecon_user ON lecon_user.lecon_id=lecon.id
                WHERE lecon_user.user_id=:userId
                GROUP BY vehicule.id, vehicule.Marque, vehicule.Modele
                HAVING COUNT(lecon.id)=(
                SELECT MAX(nb)
                    FROM (SELECT COUNT(lecon.id)AS nb
                          FROM lecon
                          INNER JOIN lecon_user ON lecon_user.lecon_id = lecon.id
                          INNER JOIN user ON user.id = lecon_user.user_id
                          WHERE user.id = :userId
                          GROUP by lecon.codevehicule_id)AS temp)";

//        SELECT u.nom, COUNT(lecon_id) as nbLecon
//from lecon l
//INNER JOIN lecon_user lu on l.id = lu.lecon_id
//INNER JOIN user u on lu.user_id = u.id
//WHERE lecon_id IN (SELECT lecon.id
//                   FROM lecon
//                    INNER JOIN lecon_user  on lecon.id = lecon_user.lecon_id
//                   WHERE user_id = 3
//                   )
//AND user_id not like 3
//GROUP by u.nom;
        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }

    public function getNbLeconByMoniteur(int $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT COUNT(lecon_user.user_id) as nbLecon FROM lecon_user WHERE lecon_user.user_id=:userId";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }

    public function getCATotByMoniteur(int $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT SUM(categorie.prix) as CATot FROM categorie
                INNER JOIN vehicule ON vehicule.codecategorie_id = categorie.id
                INNER JOIN lecon ON lecon.codevehicule_id = vehicule.id
                INNER JOIN lecon_user ON lecon_user.lecon_id = lecon.id
                WHERE lecon_user.user_id=:userId";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }

    public function getNbLeconByMoniByCateg(int $userId)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT categorie.libelle, COUNT(lecon_user.user_id) as nbLecon FROM lecon_user 
                INNER JOIN lecon ON lecon.id = lecon_user.lecon_id
                INNER JOIN vehicule ON vehicule.id = lecon.codevehicule_id
                INNER JOIN categorie ON categorie.id = vehicule.codecategorie_id
                WHERE lecon_user.user_id=:userId
                GROUP BY categorie.libelle";

        $resultSet = $conn->executeQuery($sql, ['userId'=>$userId]);

        return $resultSet->fetchAllAssociative();
    }
    public function getAllMoniteur(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT email 
                FROM user 
                WHERE JSON_CONTAINS(user.roles, '\"ROLE_MONITEUR\"')";

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }

    public function findUsersByRole(String $role)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }

    public function getMoniteurMaxUse()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT user.nom, COUNT(lecon_user.user_id)AS nbLecon
                FROM user
                INNER JOIN lecon_user ON lecon_user.user_id = user.id
                WHERE JSON_CONTAINS(user.roles, '\"ROLE_MONITEUR\"')
                GROUP BY user.id
                HAVING COUNT(lecon_user.user_id)=(
                SELECT MAX(nb) 
                    FROM (SELECT COUNT(lecon_user.user_id)AS nb 
                          FROM lecon_user
                          INNER JOIN user ON user.id = lecon_user.user_id
                          WHERE JSON_CONTAINS(user.roles, '\"ROLE_MONITEUR\"')
                          GROUP BY user.id)AS temp)";

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }

    public function getVehiculeMaxUse()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT vehicule.marque, vehicule.modele, COUNT(lecon.id)AS nbLecon
                FROM vehicule
                INNER JOIN lecon ON lecon.codevehicule_id = vehicule.id
                GROUP BY vehicule.id, vehicule.marque, vehicule.modele
                HAVING COUNT(lecon.id)=(
                SELECT MAX(nb) 
                    FROM (SELECT COUNT(lecon.id)AS nb 
                          FROM lecon
                          GROUP BY lecon.codevehicule_id)AS temp)";

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }
    public function nbLeconByModele()
    {
        //prix par categorie
        //pour moni
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT vehicule.modele, COUNT(lecon.id)AS nbLecon
                FROM vehicule
                INNER JOIN lecon ON lecon.codevehicule_id=vehicule.id
                GROUP BY vehicule.modele";
//        AND um.roles LIKE '%ROLE_ADMIN%'

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }
    public function nbLeconByMoniteur()
    {
        //prix par categorie
        //pour moni
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT user.nom, COUNT(lecon_user.user_id)AS nbLecon2
                FROM user
                INNER JOIN lecon_user ON lecon_user.user_id=user.id
                WHERE JSON_CONTAINS(user.roles, '\"ROLE_MONITEUR\"')
                GROUP BY user.nom";
//        AND um.roles LIKE '%ROLE_ADMIN%'

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();
    }


//SELECT um.nom, COUNT(l.id)/2
//FROM lecon l
//INNER JOIN lecon_user lu ON lu.lecon_id = l.id
//INNER JOIN user um ON um.id=lu.user_id
//INNER JOIN user ue ON ue.id = lu.user_id
//WHERE ue.id = 1
//AND um.roles LIKE "[%ROLE_ADMIN%]"
//GROUP BY um.nom
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

//correction conflit