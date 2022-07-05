<?php

namespace App\Repository;

use App\Entity\GeoNames;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @extends ServiceEntityRepository<GeoNames>
 *
 * @method GeoNames|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeoNames|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeoNames[]    findAll()
 * @method GeoNames[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeoNamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeoNames::class);
    }

    public function importCsv($fileName) {
        $em = $this->getEntityManager();

        if (($handle = fopen($fileName, "r")) !== false)
        {
            $count = 0;
            $batchSize = 1000;

            while (($data = fgetcsv($handle, 0, "\t")) !== false)
            {
                $phone = str_replace(['+', '-'], ['', ''], $data[12]);

                if (empty($phone) ||
                    ($phone == 1 && $data[8] != 'NA') ||
                    ($phone == 61 && $data[8] != 'OC') ||
                    ($phone == 7 && $data[8] != 'EU')) {
                    continue;
                }

                $count++;
                $entity = new GeoNames();

                $entity->setIso($data[0]);
                $entity->setCountry($data[4]);
                $entity->setContinent($data[8]);

                $entity->setPhone($phone);

                $em->persist($entity);

                if (($count % $batchSize) === 0 )
                {
                    $em->flush();
                    $em->clear();
                }
            }
            fclose($handle);
            $em->flush();
            $em->clear();
        }
    }

    public function add(GeoNames $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GeoNames $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findOneByPhone($value)
    {

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('iso', 'iso');
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('continent', 'continent');
        $rsm->addScalarResult('country', 'country');
        $rsm->addScalarResult('phone', 'phone');

        $query = $this->getEntityManager()->createNativeQuery("
            select *
            from commpeak.geo_names
            where ? like concat(phone, '%') and
                  phone != '' order by length(phone) desc
        ", $rsm);
        $query->setParameter(1, $value);

        return $query->getResult();

    }

}
