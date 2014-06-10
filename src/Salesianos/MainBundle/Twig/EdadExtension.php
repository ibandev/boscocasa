<?php

namespace Salesianos\MainBundle\Twig;

class EdadExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('edad', array($this, 'edadFilter')),
        );
    }

    public function edadFilter($date)
    {
        $date = explode(" / ", $date);
        $edad = (date("dm", date("U", mktime(0, 0, 0, $date[1], $date[0], $date[2]))) > date("dm")
             ? ((date("Y") - $date[2]) - 1)
             : (date("Y") - $date[2]));
        return $edad;
    }

    public function getName()
    {
        return 'edad_extension';
    }
}