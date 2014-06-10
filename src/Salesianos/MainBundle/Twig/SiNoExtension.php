<?php

namespace Salesianos\MainBundle\Twig;

class SiNoExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sino', array($this, 'sinoFilter')),
        );
    }

    public function sinoFilter($bool)
    {
        if($bool){
            return 'Sí';
        }
        return 'No';
    }

    public function getName()
    {
        return 'sino_extension';
    }
}