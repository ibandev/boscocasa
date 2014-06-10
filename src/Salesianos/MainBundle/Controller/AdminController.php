<?php
namespace Salesianos\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Salesianos\MainBundle\Entity\Oferta;
use Salesianos\MainBundle\Entity\Articulo;
use Salesianos\MainBundle\Entity\Estudio;
use Salesianos\MainBundle\Entity\Curriculum;
use Salesianos\MainBundle\Entity\Logo;
use Salesianos\MainBundle\Entity\Experiencia;
use Salesianos\MainBundle\Entity\Idioma;
use Salesianos\MainBundle\Entity\Conocimiento;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Salesianos\MainBundle\Form\Type\OfertaFormType;
use Salesianos\MainBundle\Form\Type\OfertaAdminFormType;
use Salesianos\MainBundle\Form\Type\EmpresaFormType;
use Salesianos\MainBundle\Form\Type\BuscaOfertasFormType;
use Salesianos\MainBundle\Form\Type\CandidatoFormType;
use Salesianos\MainBundle\Form\Type\CurriculumFormType;
use Salesianos\MainBundle\Form\Type\LogoFormType;
use Salesianos\MainBundle\Form\Type\EstudioFormType;
use Salesianos\MainBundle\Form\Type\ConocimientoFormType;
use Salesianos\MainBundle\Form\Type\ExperienciaFormType;
use Salesianos\MainBundle\Form\Type\IdiomaFormType;
use Salesianos\MainBundle\Form\Type\ArticuloFormType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class AdminController extends Controller
{
	
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa');
        $query = $repository->createQueryBuilder('e')
                ->where('e.valida = 0')          
                ->getQuery();
        $empresas = $query->getResult();
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta');
        $query = $repository->createQueryBuilder('o')
                ->where('o.visible = 1')          
                ->getQuery();
        $ofertas = $query->getResult();
        return $this->render('SalesianosMainBundle:Admin:admin.html.twig', array(
                'empresas' => $empresas,
                'ofertas' => $ofertas
            ));
    }

    public function candidatosShowAction()
    {
        $paginator  = $this->get('knp_paginator');
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $queryBuilder = $repository->createQueryBuilder('c')->orderBy('c.apellidos', 'ASC');
        $candidatos = $queryBuilder->getQuery(); 
        $pagination = $paginator->paginate(
                        $candidatos,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->render('SalesianosMainBundle:Admin:candidatos.html.twig', array(
                    'pagination' => $pagination,
                    'candidatos' => $candidatos,
        ));
    }

    public function candidatoRemoveAction($id_candidato)
    {
        $candidato = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato')->find($id_candidato);
        $em = $this->getDoctrine()->getManager();
        $em->remove($candidato);
        $em->flush();

        $paginator  = $this->get('knp_paginator');
        $candidatos = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato')->findAll();
        $pagination = $paginator->paginate(
                        $candidatos,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->redirect($this->generateUrl('salesianos_admin_candidatos'));
    }

    public function candidatoEditAction($id_candidato)
    {
        $request = $this->getRequest();
        $candidato = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato')->find($id_candidato);
        $form = $this->createForm(new CandidatoFormType(), $candidato);
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($candidato);
                $em->flush();
                return $this->redirect($this->generateUrl('salesianos_admin_candidatos'));
            }
        }
        return $this->render('SalesianosMainBundle:Admin:editar_candidato.html.twig',array(
                'form' => $form->createView(),
            ));        
    }

    public function empresasShowAction()
    {
        $paginator  = $this->get('knp_paginator');
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa');
        $queryBuilder = $repository->createQueryBuilder('e')->orderBy('e.nombre', 'ASC');
        $empresas = $queryBuilder->getQuery(); 
        $pagination = $paginator->paginate(
                        $empresas,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->render('SalesianosMainBundle:Admin:empresas.html.twig', array(
                    'pagination' => $pagination,
                    'empresas' => $empresas,
        ));
    }

    public function empresaRemoveAction($id_empresa)
    {
        $empresa = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->find($id_empresa);
        if($empresa != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($empresa);
            $em->flush();
        }        

        $paginator  = $this->get('knp_paginator');
        $empresas = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->findAll();
        $pagination = $paginator->paginate(
                        $empresas,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->redirect($this->generateUrl('salesianos_admin_empresas',array('pagination' => $pagination,
                                                                                      'empresas' => $empresas,)));
    }

    public function empresaEditAction($id_empresa)
    {
        $request = $this->getRequest();
        $empresa = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->find($id_empresa);
        $form = $this->createForm(new EmpresaFormType(), $empresa);
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $empresa->upload();
                $em->persist($empresa);
                $em->flush();
                return $this->redirect($this->generateUrl('salesianos_admin_empresas'));
            }
        }
        return $this->render('SalesianosMainBundle:Admin:editar_empresa.html.twig',array(
                'form' => $form->createView(),
            ));        
    }

    public function empresaValidateAction($id_empresa)
    {
        $request = $this->getRequest();
        $empresa = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->find($id_empresa);
        if($empresa->getValida()){
            $empresa->setValida(false);
        }else{
            $empresa->setValida(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($empresa);
        $em->flush();
        return $this->redirect($this->generateUrl('salesianos_admin_empresas'));      
    }

    public function ofertasShowAction()
    {
        $paginator  = $this->get('knp_paginator');
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta');
        $queryBuilder = $repository->createQueryBuilder('o')->orderBy('o.fecha_ini', 'DESC');
        $ofertas = $queryBuilder->getQuery(); 
        $pagination = $paginator->paginate(
                        $ofertas,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->render('SalesianosMainBundle:Admin:ofertas.html.twig', array(
                    'pagination' => $pagination,
                    'ofertas' => $ofertas,
        ));
    }

    public function ofertaRemoveAction($id_oferta)
    {
        $oferta = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->find($id_oferta);
        if($oferta != null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($oferta);
            $em->flush();
        }        

        $paginator  = $this->get('knp_paginator');
        $ofertas = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->findAll();
        $pagination = $paginator->paginate(
                        $ofertas,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->redirect($this->generateUrl('salesianos_admin_ofertas'));
    }

    public function ofertaEditAction($id_oferta)
    {
        $request = $this->getRequest();
        $oferta = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->find($id_oferta);
        $form = $this->createForm(new OfertaAdminFormType(), $oferta);
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($oferta);
                $em->flush();
                return $this->redirect($this->generateUrl('salesianos_admin_ofertas'));
            }
        }
        return $this->render('SalesianosMainBundle:Admin:editar_oferta.html.twig',array(
                'form' => $form->createView(),
            ));        
    }

    public function ofertaEstadoAction($id_oferta)
    {
        $request = $this->getRequest();
        $oferta = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->find($id_oferta);
        if($oferta->getVisible()){
            $oferta->setVisible(false);
        }else{
            $oferta->setVisible(true);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($oferta);
        $em->flush();
        return $this->redirect($this->generateUrl('salesianos_admin_ofertas'));      
    }

    public function blogShowAction()
    {
        $paginator  = $this->get('knp_paginator');
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo');
        $queryBuilder = $repository->createQueryBuilder('a')->orderBy('a.fecha_publi', 'DESC');
        $articulos = $queryBuilder->getQuery();     
        $pagination = $paginator->paginate(
                        $articulos,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->render('SalesianosMainBundle:Admin:blog.html.twig', array(
                    'pagination' => $pagination,
                    'articulos' => $articulos,
        ));
    }

    public function articuloRemoveAction($id_articulo)
    {
        $articulo = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo')->find($id_articulo);
        $em = $this->getDoctrine()->getManager();
        $em->remove($articulo);
        $em->flush();

        $paginator  = $this->get('knp_paginator');
        $articulos = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo')->findAll();
        $pagination = $paginator->paginate(
                        $articulos,
                        $this->get('request')->query->get('page', 1)/*page number*/,
                        30/*limit per page*/
                    );
        return $this->redirect($this->generateUrl('salesianos_admin_blog',array('pagination' => $pagination,
                                                                                      'articulos' => $articulos,)));
    }

    public function articuloEditAction($id_articulo)
    {
        $request = $this->getRequest();
        $articulo = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo')->find($id_articulo);
        $form = $this->createForm(new ArticuloFormType(), $articulo);
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $articulo->upload();
                $em->persist($articulo);
                $em->flush();
                return $this->redirect($this->generateUrl('salesianos_admin_blog'));
            }
        }
        return $this->render('SalesianosMainBundle:Admin:editar_articulo.html.twig',array(
                'form' => $form->createView(),
            ));        
    }

    public function articuloAddAction()
    {
        $request = $this->getRequest();
        $articulo = new Articulo();
        $form = $this->createForm(new ArticuloFormType(), $articulo);
        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $articulo->upload();
                $em->persist($articulo);
                $em->flush();
                return $this->redirect($this->generateUrl('salesianos_admin_blog'));
            }
        }
        return $this->render('SalesianosMainBundle:Admin:add_articulo.html.twig',array(
                'form' => $form->createView(),
        ));
        
    }

    

    

    


}
