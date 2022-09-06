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
        // on prend le formulaire qu'on lie à 'E' : 'EVENT'

        $result = $qb->addSelect('E.title_event, E.nb_places, E.content_event, E.date_event, E.cp, E.ville, E.adresse')
            // on recupere ce qu'on à besoin dans la base de données
            ->addSelect('U.pseudo')
            // on selectionne le pseudo dans user pour le joindre :
            ->join(User::class, 'U', 'WITH', 'U.id = E.user')
            // on le joint a l'id de la table User
            ->addSelect('S.title_sport')
            ->join(Sport::class, 'S', 'WITH', 'S.id = E.sport')
            ->addSelect('L.title_level')
            ->join(Level::class, 'L', 'WITH', 'L.id = E.level');

        if ($parameters['q'] !== null) {
            // si le pseudo est différent de null une fois submit, on le cherche içi :
            $result = $qb->andWhere('U.pseudo = :pseudo')
                ->setParameter('pseudo', $parameters['q']);
            // lie 'q' du tableau $parameter au pseudo de la table user // bindParamPDO
        }

        if (!empty($parameters['sport']->toArray())) {
            // si l'utiliser selectionne une ou des options, on le transforme en tableau ->toArray() des id selectionnés
            $sports = []; // tableau vide
            $sp = $parameters['sport']->toArray(); // on recupère le tableau des id selectionnés
            foreach ($sp as $sport) { // on met les options selectionné dans le tableau vide de sport[]
                array_push($sports, $sport->getId()); //empile une ou plusieurs options à la fin d'un tableau
            }
            $result = $qb->andWhere('S.id IN (\'' . implode("','", $sports) . '\')'); // resultat des ID implode en string
        }

        if (!empty($parameters['level']->toArray())) {
            $levels = [];
            $lv = $parameters['level']->toArray();
            foreach ($lv as $level) {
                array_push($levels, $level->getId());
            }
            $result = $qb->andWhere('L.id IN (\'' . implode("','", $levels) . '\')');
        }

        if ($parameters['ville'] !== null) {
            $result = $qb->andWhere('E.ville = :ville')
                ->setParameter('ville', $parameters['ville']);
        }

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
