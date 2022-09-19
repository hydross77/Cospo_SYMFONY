<?php

namespace App\Repository;

use DateTime;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Level;
use App\Entity\Sport;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Récupère les événement en lien avec une recherche
     * @return Event[]
     */
    public function findSearch(array $parameters) //parameters est le tableau
    {
        $qb = $this->createQueryBuilder('E');
        // fait une requête sur l'entité 'E' : 'EVENT'

        $result = $qb->join(User::class, 'U', 'WITH', 'U.id = E.user')
            ->join(Sport::class, 'S', 'WITH', 'S.id = E.sport')
            ->join(Level::class, 'L', 'WITH', 'L.id = E.level');

        if ($parameters['q'] !== null) {
            // si le pseudo est différent de null une fois submit, on le cherche içi :
            $result = $qb->andWhere('U.pseudo LIKE :pseudo')
                ->setParameter('pseudo', "%{$parameters['q']}%");
            // lie 'q' du tableau $parameter au pseudo de la table user // bindParamPDO
        }

        if ($parameters['sport'] !== null) {
            $result = $qb->andWhere('S.id = :sport')
                ->setParameter('sport', $parameters['sport']);
        }

        if ($parameters['level'] !== null) {
            $result = $qb->andWhere('L.id = :level')
                ->setParameter('level', $parameters['level']);
        }

        if ($parameters['ville'] !== null) {
            $result = $qb->andWhere('E.ville = :ville')
                ->setParameter('ville', $parameters['ville']);
        }
        if ($parameters['date'] !== null) {
            $result = $qb->andWhere("DATE_FORMAT(E.date_event, '%d-%m-%Y') = :date")
                ->setParameter('date', $parameters['date']);
        }
        dump($parameters['date']);

        $result = $qb->getQuery()
            ->getResult(); // requete bdd

        return $result; //retourne le tableau des résultats
    }

    public function add(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function FuturEvent(): array
    {
        $now = new DateTime();
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_event > :val')
            ->setParameter('val', $now)
            ->orderBy('e.date_event', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function PastEvent(): array
    {
        $now = new DateTime();
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_event < :val')
            ->setParameter('val', $now)
            ->orderBy('e.date_event', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Event[] Returns an array of Event objects
     */
    public function PresentEvent(): array
    {
        $now = new DateTime();
        return $this->createQueryBuilder('e')
            ->Where('e.date_event = :val')
            ->setParameter('val', $now)
            ->orderBy('e.date_event', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Event
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


}
