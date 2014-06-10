<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\EntityRepository;
use Salesianos\MainBundle\Entity\Provincia;

class PoblacionRepository extends EntityRepository
{
    public function findByProvincia(Provincia $provincia)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM SalesianosMainBundle:Poblacion p WHERE p.provincia = '.$provincia
            )
            ->getResult();
    }
}