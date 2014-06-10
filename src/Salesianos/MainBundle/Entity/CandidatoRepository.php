<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\EntityRepository;
use Salesianos\MainBundle\Entity\Usuario;

class CandidatoRepository extends EntityRepository
{
    public function findByUser(Usuario $user)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM SalesianosMainBundle:Candidato c WHERE c.usuario = '.$user->getId()
            )
            ->getSingleResult();
    }

    
}