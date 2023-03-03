<?php

namespace App\Repository;

use App\Entity\ThematicImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ThematicImage>
 *
 * @method ThematicImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThematicImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThematicImage[]    findAll()
 * @method ThematicImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThematicImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThematicImage::class);
    }

    public function save(ThematicImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ThematicImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function append(ThematicImage $entity): void
    {

        if ($entity->getPosition() <= 1) $entity->setPosition(1);
        $max = $entity->getThematic()->getThematicImages()->count() + 1;
        if ($entity->getPosition() >= $max) $entity->setPosition($max);

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $queryBuilder = $em->createQueryBuilder();
            $query = $queryBuilder->update(ThematicImage::class, 'ci')
                ->set('ci.position', $queryBuilder->expr()->sum('ci.position', 1))
                ->where('ci.thematic = :thematicId')
                ->andWhere('ci.position >= :newPosition')
                ->setParameter('thematicId', $entity->getThematic())
                ->setParameter('newPosition', $entity->getPosition())
                ->getQuery();

            $query->execute();
            $em->persist($entity);
            $em->flush();
            $em->getConnection()->commit();

        } catch (\Exception $ex) {
            $em->getConnection()->rollback();
            throw $ex;
        }

    }

    public function deleteFromPosition(ThematicImage $entity): void
    {

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {

            $em->remove($entity);
            $em->flush();

            $queryBuilder = $em->createQueryBuilder();
            $query = $queryBuilder->update(ThematicImage::class, 'ci')
                ->set('ci.position', $queryBuilder->expr()->diff('ci.position', 1))
                ->where('ci.thematic = :thematicId')
                ->andWhere('ci.position >= :oldPosition')
                ->setParameter('thematicId', $entity->getThematic())
                ->setParameter('oldPosition', $entity->getPosition())
                ->getQuery();

            $query->execute();

            $em->getConnection()->commit();

        } catch (\Exception $ex) {
            $em->getConnection()->rollback();
            throw $ex;
        }

    }

    public function moveUp(ThematicImage $entity): void
    {

        if ($entity->getPosition() === 1) return;

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {

            $qb = $em->createQueryBuilder();

            $qb->update(ThematicImage::class, 'ci')
                ->set('ci.position', $qb->expr()->sum('ci.position', 1))
                ->where('ci.thematic = :thematicId')
                ->andWhere('ci.position = :position')
                ->setParameter('thematicId', $entity->getThematic())
                ->setParameter('position', $entity->getPosition() - 1)
                ->getQuery()
                ->execute();

            $entity->setPosition($entity->getPosition() - 1);
            $em->persist($entity);
            $em->flush();
            $em->getConnection()->commit();

        } catch (\Exception $ex) {
            $em->getConnection()->rollback();
            throw $ex;
        }

    }

    public function moveDown(ThematicImage $entity): void
    {

        if ($entity->getPosition() === $entity->getThematic()->getThematicImages()->count()) return;

        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {

            $qb = $em->createQueryBuilder();

            $qb->update(ThematicImage::class, 'ci')
                ->set('ci.position', $qb->expr()->diff('ci.position', 1))
                ->where('ci.thematic = :thematicId')
                ->andWhere('ci.position = :position')
                ->setParameter('thematicId', $entity->getThematic())
                ->setParameter('position', $entity->getPosition() + 1)
                ->getQuery()
                ->execute();

            $entity->setPosition($entity->getPosition() + 1);
            $em->persist($entity);
            $em->flush();
            $em->getConnection()->commit();

        } catch (\Exception $ex) {
            $em->getConnection()->rollback();
            throw $ex;
        }

    }

}
