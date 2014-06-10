<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\EntityRepository;
use Salesianos\MainBundle\Entity\Usuario;

class EmpresaRepository extends EntityRepository
{
    public function findByUser(Usuario $user)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM SalesianosMainBundle:Empresa e WHERE e.usuario = '.$user->getId()
            )
            ->getSingleResult();
    }

    
}