<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\EntityRepository;
use Salesianos\MainBundle\Entity\Poblacion;
use Salesianos\MainBundle\Entity\Oferta;

class ProvinciaRepository extends EntityRepository
{
    public function findByPoblacion(Poblacion $poblacion)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM SalesianosMainBundle:Provincia p WHERE p.id = '.$poblacion->getId()
            )
            ->getSingleResult();
    }

}