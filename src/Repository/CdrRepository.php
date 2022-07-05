<?php

namespace App\Repository;

use App\Entity\Cdr;
use App\Libraries\GeoLocation;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Cdr>
 *
 * @method Cdr|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cdr|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cdr[]    findAll()
 * @method Cdr[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CdrRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cdr::class);
    }

    public function add(Cdr $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cdr $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function importCsv(string $fileName, GeoLocation $geoLocation, GeoNamesRepository $geoNamesRepository, string $geo_api_url , string $geo_api_key ) {
        $em = $this->getEntityManager();

        if (($handle = fopen($fileName, "r")) !== false)
        {
            $count = 0;
            $batchSize = 1000;

            while (($data = fgetcsv($handle, 0, ",")) !== false)
            {
                $count++;
                $entity = new Cdr();

                $entity->setCustomerId($data[0]);
                $entity->setDateString($data[1]);
                $entity->setDuration($data[2]);
                $entity->setPhoneNumber($data[3]);
                $entity->setCustomerIp($data[4]);

                $geo = $geoLocation->byIp($data[4],$geo_api_url, $geo_api_key );

                $entity->setOriginCountry($geo->country_code2 ?? 'NOT_FOUND');
                $entity->setOriginContinent($geo->continent_code ?? 'NOT_FOUND');

                $geoName = $geoNamesRepository->findOneByPhone($data[3]);

                $entity->setTargetCountry($geoName[0]['iso'] ?? 'NOT_FOUND');
                $entity->setTargetContinent($geoName[0]['continent'] ?? 'NOT_FOUND');

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
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function loadCustomerStat($value)
    {

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('customer_id', 'customer_id');
        $rsm->addScalarResult('total_call', 'total_call');
        $rsm->addScalarResult('same_continent', 'same_continent');
        $rsm->addScalarResult('same_continent_duration', 'same_continent_duration');
        $rsm->addScalarResult('total_duration', 'total_duration');

        $query = $this->getEntityManager()->createNativeQuery("
            SELECT customer_id, count(*) total_call, 
                sum(if(origin_continent = target_continent, 1, 0)) same_continent, 
                sum(if(origin_continent = target_continent, duration, 0)) same_continent_duration,
                sum(duration) total_duration
                FROM commpeak.cdr 
                where customer_id = ? ", $rsm);
        $query->setParameter(1, $value);

        return $query->getResult();

    }


}
