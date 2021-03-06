<?php
namespace Salesianos\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Salesianos\MainBundle\Entity\Oferta;
use Salesianos\MainBundle\Entity\Curriculum;
use Salesianos\MainBundle\Entity\Logo;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Salesianos\MainBundle\Form\Type\OfertaFormType;
use Salesianos\MainBundle\Form\Type\EmpresaFormType;
use Salesianos\MainBundle\Form\Type\BuscaOfertasFormType;
use Salesianos\MainBundle\Form\Type\CandidatoFormType;
use Salesianos\MainBundle\Form\Type\CurriculumFormType;
use Salesianos\MainBundle\Form\Type\LogoFormType;


class OfertasController extends Controller
{
	//Muestra las ofertas
    public function ofertasAction()
    {
        $paginator  = $this->get('knp_paginator');
        $request = $this->getRequest();
        $form = $this->createForm(new BuscaOfertasFormType());
        $form->handleRequest($request);
        $repository = $this->getDoctrine()
                           ->getRepository('SalesianosMainBundle:Oferta');
        $query = null;
        $fecha = new \DateTime('now');


        if ($form->isValid()) {

            $datos = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $queryBuilder = $repository->createQueryBuilder('o');
            $queryBuilder->where('o.visible = 1');
            $queryBuilder->andWhere('o.fecha_fin > :fecha_fin')->setParameter('fecha_fin', $fecha->format('Y-m-d'));
            if($datos['sector']!=null){
                $queryBuilder->andWhere('o.sector = :sector')->setParameter('sector', $datos['sector']);
            }
            if($datos['provincia']!=null){
                    $queryBuilder->andWhere('o.provincia = :provincia')->setParameter('provincia', $datos['provincia']);              
            }
            $query = $queryBuilder->getQuery();

        }else{
            $queryBuilder = $repository->createQueryBuilder('o')->where('o.visible = 1');
            $queryBuilder->andWhere('o.fecha_fin > :fecha_fin')->setParameter('fecha_fin', $fecha->format('Y-m-d'));
            $query = $queryBuilder->getQuery();
        }

        $pagination = $paginator->paginate(
                $query,
                $this->get('request')
                    ->query
                    ->get('page', 1)/*page number*/,
                5/*limit per page*/
            );

        return $this->render('SalesianosMainBundle:Main:ofertas.html.twig', array(
                'pagination' => $pagination,
                 'form' => $form->createView(),
                  'activo' => 'todas'
            ));
    }

    //Muestra las ofertas publicadas por la empresa del usuario
    public function misofertasAction()
    {
        $paginator  = $this->get('knp_paginator');
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user->hasRole("ROLE_ALUMNO")){
            $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
            $candidato = $repository->findOneByUsuario($user);
            $ofertas = $candidato->getOfertas();
        }else{
            $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa');
            $empresa = $repository->findOneByUsuario($user);
            $ofertas = $empresa->getOfertas();
        }
        $pagination = $paginator->paginate(
                $ofertas,
                $this->get('request')->query->get('page', 1)/*page number*/,
                5/*limit per page*/
        );
        return $this->render('SalesianosMainBundle:Main:ofertas.html.twig', array(
                'pagination' => $pagination,
                  'activo' => 'mias'
            ));
    }

    // //Muestra la oferta que tenga el id correspondiente
    // public function ofertaAction($id)
    // {        
    //     $oferta = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->find($id);
    //     return $this->render('SalesianosMainBundle:Main:oferta.html.twig', array(
    //         'oferta' => $oferta,
    //     ));
    // }

    //Muestra el formulario para crear una nueva oferta
    public function nuevaofertaAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();         
        $empresa = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->findByUser($user);
        if(!$empresa->getValida()){
            return $this->render('SalesianosMainBundle:Main:mensaje.html.twig', array(
                    'mensaje' => '<p>Tu empresa no ha sido validada por el administrador aún.<br> Inténtalo más tarde.</p>'
                ));
        }
        $form = $this->createForm(new OfertaFormType(), new Oferta());
        return $this->render('SalesianosMainBundle:Main:nueva_oferta.html.twig', array(
            'form' => $form->createView(),
             'activo' => 'nueva'));
    }

    //Publica una oferta
    public function publicarofertaAction()
    {        
        $request = $this->getRequest();
        $oferta = new Oferta();
        $form = $this->createForm(new OfertaFormType(), $oferta);
        $resultado = false;
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);     
            
            if($form->isValid()){                
                $user = $this->container->get('security.context')->getToken()->getUser();
                $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa');
                $empresa = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->findByUser($user);
                if(!$empresa->getValida()){
                    return $this->render('SalesianosMainBundle:Main:mensaje.html.twig', array(
                            'mensaje' => 'Tu empresa no ha sido validada por el administrador aún.<br> Inténtalo más tarde.'
                        ));
                }
                $oferta->setEmpresa($empresa);
                $em = $this->getDoctrine()->getManager();
                $em->persist($oferta);
                $em->flush();
                $resultado = true;
            }
        }
        return $this->render('SalesianosMainBundle:Main:nueva_oferta.html.twig', array(
                'resultado' => $resultado,
                'activo' => 'nueva'
            ));

    }

    public function inscripcionAction($id_oferta){

        $paginator  = $this->get('knp_paginator');
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user->hasRole("ROLE_ALUMNO")){
            $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
            $candidato = $repository->findOneByUsuario($user);
            $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta');
            $oferta = $repository->find($id_oferta);
            if($candidato->hasOferta($oferta)){
                return $this->redirect($this->generateUrl('salesianos_main_misofertas'));
            }
            $candidato->addOferta($oferta);

            $em = $this->getDoctrine()->getManager();
            $em->persist($oferta);
            $em->persist($candidato);
            $em->flush();

            $ofertas = $candidato->getOfertas();

            $form = $this->createForm(new BuscaOfertasFormType());
            $pagination = $paginator->paginate(
                    $ofertas,
                    $this->get('request')->query->get('page', 1)/*page number*/,
                    5/*limit per page*/
                );

            return $this->render('SalesianosMainBundle:Main:ofertas.html.twig', array(
                    'form' => $form->createView(),
                    'pagination' => $pagination,
                    'ofertas' => $ofertas,
                    'activo' => 'mias'
            ));
        }
    }

    public function verinscritosAction($id_oferta)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user->hasRole("ROLE_EMPRESA")){
            $oferta = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->find($id_oferta);
            if($oferta->getEmpresa()->getUsuario()->getId() == $user->getId()){
                $candidatos = $oferta->getCandidatos();            
            }else{
                return $this->redirect($this->generateUrl('salesianos_main_homepage'));
            }
        }else{
             return $this->redirect($this->generateUrl('salesianos_main_homepage'));
        }
        return $this->render('SalesianosMainBundle:Main:candidatos.html.twig', array(
                    'candidatos' => $candidatos,
                    'oferta' => $oferta,
        ));
    }



}
