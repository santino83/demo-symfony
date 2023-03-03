<?php

namespace App\Repository;

use App\Entity\CarouselImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarouselImage>
 *
 * @method CarouselImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarouselImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarouselImage[]    findAll()
 * @method CarouselImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarouselImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarouselImage::class);
    }

    public function save(CarouselImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CarouselImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function append(CarouselImage $entity): void {

        if($entity->getPosition() <= 1) $entity->setPosition(1);
        $max = $entity->getCarousel()->getCarouselImages()->count() + 1;
        if($entity->getPosition() >= $max) $entity->setPosition($max);

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $queryBuilder = $em->createQueryBuilder();
            $query = $queryBuilder->update(CarouselImage::class, 'ci')
                ->set('ci.position', $queryBuilder->expr()->sum('ci.position', 1))
                ->where('ci.carousel = :carouselId')
                ->andWhere('ci.position >= :newPosition')
                ->setParameter('carouselId', $entity->getCarousel())
                ->setParameter('newPosition', $entity->getPosition())
                ->getQuery();

            $query->execute();
            $em->persist($entity);
            $em->flush();
            $em->getConnection()->commit();

        }catch (\Exception $ex){
            $em->getConnection()->rollback();
            throw $ex;
        }

    }

    public function deleteFromPosition(CarouselImage $entity): void {

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {

            $em->remove($entity);
            $em->flush();

            $queryBuilder = $em->createQueryBuilder();
            $query = $queryBuilder->update(CarouselImage::class, 'ci')
                ->set('ci.position', $queryBuilder->expr()->diff('ci.position', 1))
                ->where('ci.carousel = :carouselId')
                ->andWhere('ci.position >= :oldPosition')
                ->setParameter('carouselId', $entity->getCarousel())
                ->setParameter('oldPosition', $entity->getPosition())
                ->getQuery();

            $query->execute();

            $em->getConnection()->commit();

        }catch (\Exception $ex){
            $em->getConnection()->rollback();
            throw $ex;
        }

    }

    public function moveUp(CarouselImage $entity): void {

        if($entity->getPosition() === 1) return;

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try{

            $qb = $em->createQueryBuilder();

            $qb->update(CarouselImage::class, 'ci')
                ->set('ci.position', $qb->expr()->sum('ci.position', 1))
                ->where('ci.carousel = :carouselId')
                ->andWhere('ci.position = :position')
                ->setParameter('carouselId', $entity->getCarousel())
                ->setParameter('position', $entity->getPosition() - 1)
                ->getQuery()
                ->execute();

            $entity->setPosition($entity->getPosition() - 1);
            $em->persist($entity);
            $em->flush();
            $em->getConnection()->commit();

        }catch (\Exception $ex){
            $em->getConnection()->rollback();
            throw $ex;
        }

    }

    public function moveDown(CarouselImage $entity): void {

        if($entity->getPosition() === $entity->getCarousel()->getCarouselImages()->count()) return;

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try{

            $qb = $em->createQueryBuilder();

            $qb->update(CarouselImage::class, 'ci')
                ->set('ci.position', $qb->expr()->diff('ci.position', 1))
                ->where('ci.carousel = :carouselId')
                ->andWhere('ci.position = :position')
                ->setParameter('carouselId', $entity->getCarousel())
                ->setParameter('position', $entity->getPosition() + 1)
                ->getQuery()
                ->execute();

            $entity->setPosition($entity->getPosition() + 1);
            $em->persist($entity);
            $em->flush();
            $em->getConnection()->commit();

        }catch (\Exception $ex){
            $em->getConnection()->rollback();
            throw $ex;
        }

    }


}
