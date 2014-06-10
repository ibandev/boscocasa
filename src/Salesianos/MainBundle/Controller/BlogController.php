<?php
namespace Salesianos\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
	
    public function indexAction()
    {
        //Muestra el último artículo
        $articulos = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo')->findAllByFecha();
        return $this->render('SalesianosMainBundle:Main:blog.html.twig', array(
                'articulo' => $articulos[0],
                'articulos' => $articulos,
        ));
    }

    public function showAction($id_articulo)
    {
        //Muestra el artículo con $id_articulo
        $articulo = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo')->find($id_articulo);
        $articulos = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo')->findAllByFecha();
        return $this->render('SalesianosMainBundle:Main:blog.html.twig', array(
                'articulo' => $articulo,
                'articulos' => $articulos,
        ));
    }



}
