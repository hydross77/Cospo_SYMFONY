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
    public function findSearch(array $parameters)
    {
        $qb = $this->createQueryBuilder('E');

        if ($parameters['q'] !== null) {
            $result = $qb->addSelect('U.pseudo')
                ->join(User::class, 'U', 'WITH', 'U.id = E.user')
                ->andWhere('U.pseudo = :pseudo')
                ->setParameter('pseudo', $parameters['q']);
        }

        if ($parameters['ville'] !== null) {
            $result = $qb->addSelect('V.ville')
                ->join(Ville::class, 'V', 'WITH', 'V.id = E.ville')
                ->andWhere('V.ville = :ville')
                ->setParameter('ville', $parameters['ville']);
        }

        if ($parameters['sport'] !== null) {
            $sports = [];
            $sp = $parameters['sport']->toArray();
            foreach ($sp as $sport) {
                array_push($sports, $sport->getId());
            }
            $result = $qb->addSelect('S.sport')
                ->join(Sport::class, 'S', 'WITH', 'S.id = E.sport')
                ->andWhere('S.id IN (\'' . implode("','", $sports) . '\')')
                ->setParameter('sport', $parameters['sport']);
        }

        if ($parameters['level'] !== null) {
            $levels = [];
            foreach ($parameters['level'] as $level) {
                array_push($levels, $level->getId());
            }
            $result = $qb->addSelect('L.level')
                ->join(Level::class, 'L', 'WITH', 'L.id = E.level')
                ->andWhere('L.id IN (\'' . implode("','", $levels) . '\')')
                ->setParameter('level', $parameters['level']);
        }

        $result = $qb->getQuery()
            ->getResult();

        return $result;
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
