<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\EntityRepository;

class ArticuloRepository extends EntityRepository
{
    public function findAllByFecha()
    {
        $articulos = $this->getEntityManager()
            ->createQuery(
                'SELECT a FROM SalesianosMainBundle:Articulo a ORDER BY a.fecha_publi DESC'
            )
            ->getResult();
        if($articulos == null){
            $articulos = array(new Articulo());
        }
        return $articulos;
    }

    public function findLastPublished()
    {
        $articulos = $this->getEntityManager()
			            ->createQuery(
			                'SELECT a FROM SalesianosMainBundle:Articulo a ORDER BY a.fecha_publi DESC'
			            )
			            ->getResult();
        if($articulos == null){
            return new Articulo();            
        }
		return $articulos[0];
    }

    
}