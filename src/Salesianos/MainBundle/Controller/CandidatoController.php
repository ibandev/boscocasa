<?php
namespace Salesianos\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Salesianos\MainBundle\Entity\Oferta;
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
use Salesianos\MainBundle\Form\Type\EmpresaFormType;
use Salesianos\MainBundle\Form\Type\BuscaOfertasFormType;
use Salesianos\MainBundle\Form\Type\CandidatoFormType;
use Salesianos\MainBundle\Form\Type\OtrosDatosFormType;
use Salesianos\MainBundle\Form\Type\LogoFormType;
use Salesianos\MainBundle\Form\Type\EstudioFormType;
use Salesianos\MainBundle\Form\Type\ConocimientoFormType;
use Salesianos\MainBundle\Form\Type\ExperienciaFormType;
use Salesianos\MainBundle\Form\Type\IdiomaFormType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class CandidatoController extends Controller
{
	

    //Muestra los datos del candidato y permite modificarlos
    public function datosalumnoAction()
    {
        $request = $this->getRequest();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');

        $query = $repository->createQueryBuilder('c')
            ->where('c.usuario = :usuario')
            ->setParameter('usuario', $user->getId())
            ->getQuery();
         
        $candidato = $query->getSingleResult();

        $form = $this->createForm(new CandidatoFormType(), $candidato);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);

            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();                
                $em->persist($candidato);
                $user->setEmail($candidato->getEmail());
                $em->persist($user);
                $em->flush();
            }
        }
        return $this->render('SalesianosMainBundle:Main:datos_alumno.html.twig',array(
                'form' => $form->createView(),
                'nif' => $user->getUsername(),
            ));
    }

    //Muestra el CV del candidato y permite modificarlo
    public function CVAction()
    {
        
        $user = $this->container->get('security.context')->getToken()->getUser();

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $query = $repository->createQueryBuilder('c')
            ->where('c.usuario = :usuario')
            ->setParameter('usuario', $user->getId())
            ->getQuery();
        $candidato = $query->getSingleResult();
       
        $cv = $candidato->getCurriculum();
        if($cv == null){
            $cv = new Curriculum();
            $cv->setCandidato($candidato);
            $em = $this->getDoctrine()->getManager();
            $em->persist($cv);
            $em->flush();
        }

        return $this->render('SalesianosMainBundle:Main:cv.html.twig',array(
                'cv' => $cv,
            ));
    }

    public function eliminarCVAction($tipo, $id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $query = $repository->createQueryBuilder('c')
            ->where('c.usuario = :usuario')
            ->setParameter('usuario', $user->getId())
            ->getQuery();
        $candidato = $query->getSingleResult();

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Curriculum');
        $cv = $repository->findOneByCandidato($candidato);

        switch($tipo){
            case 'estudio':
                $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Estudio');
                break;
            case 'experiencia':
                $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Experiencia');
                break;
            case 'conocimiento':
                $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Conocimiento');
                break;
            case 'idioma':
                $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Idioma');
                break;
        }
        $objeto = $repository->find($id);

        if($objeto != null && ($this->get('security.context')->isGranted('ROLE_ADMIN') || ($cv->getId() == $objeto->getCurriculum()->getId()))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($objeto);
            $cv->setUltimaActualizacion(new \DateTime("now"));
            $em->persist($cv);
            $em->flush();
        }        

        return $this->redirect($this->generateUrl('salesianos_main_cv'));
    }

    public function addCVAction($tipo)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $query = $repository->createQueryBuilder('c')
            ->where('c.usuario = :usuario')
            ->setParameter('usuario', $user->getId())
            ->getQuery();
        $candidato = $query->getSingleResult();

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Curriculum');
        $cv = $repository->findOneByCandidato($candidato);

        switch($tipo){
            case 'estudio':
                $form = $this->createForm(new EstudioFormType(), new Estudio(), array(
    'action' => $this->generateUrl('salesianos_main_guardarestudio')));
                break;
            case 'experiencia':
                $form = $this->createForm(new ExperienciaFormType(), new Experiencia(), array(
    'action' => $this->generateUrl('salesianos_main_guardarexperiencia')));
                break;
            case 'conocimiento':
                $form = $this->createForm(new ConocimientoFormType(), new Conocimiento(), array(
    'action' => $this->generateUrl('salesianos_main_guardarconocimiento')));
                break;
            case 'idioma':
                $form = $this->createForm(new IdiomaFormType(), new Idioma(), array(
    'action' => $this->generateUrl('salesianos_main_guardaridioma')));
                break;
            case 'otros':
                $form = $this->createForm(new OtrosDatosFormType(), $cv, array(
    'action' => $this->generateUrl('salesianos_main_guardarotrosdatos')));
                break;
        }      

        return $this->render('SalesianosMainBundle:Main:addcv.html.twig',array(
                'form' => $form->createView(),
                'tipo' => $tipo
            ));
    }

    public function guardarEstudioAction()
    {
        $request = $this->getRequest();
        $user = $this->container->get('security.context')->getToken()->getUser(); 

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $candidato = $repository->findOneByUsuario($user);

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Curriculum');
        $cv = $repository->findOneByCandidato($candidato);

        $estudio = new Estudio();
        $estudio->setCurriculum($cv);
        $form = $this->createForm(new EstudioFormType(), $estudio);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($estudio);
                $cv->setUltimaActualizacion(new \DateTime("now"));
                $em->persist($cv);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('salesianos_main_cv'));
    }

    public function guardarConocimientoAction()
    {
        $request = $this->getRequest();
        $user = $this->container->get('security.context')->getToken()->getUser(); 

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $candidato = $repository->findOneByUsuario($user);

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Curriculum');
        $cv = $repository->findOneByCandidato($candidato);

        $conocimiento = new Conocimiento();
        $conocimiento->setCurriculum($cv);
        $form = $this->createForm(new ConocimientoFormType(), $conocimiento);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($conocimiento);
                $cv->setUltimaActualizacion(new \DateTime("now"));
                $em->persist($cv);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('salesianos_main_cv'));
    }

    public function guardarExperienciaAction()
    {
        $request = $this->getRequest();
        $user = $this->container->get('security.context')->getToken()->getUser(); 

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $candidato = $repository->findOneByUsuario($user);

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Curriculum');
        $cv = $repository->findOneByCandidato($candidato);

        $experiencia = new Experiencia();
        $experiencia->setCurriculum($cv);
        $form = $this->createForm(new ExperienciaFormType(), $experiencia);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($experiencia);
                $cv->setUltimaActualizacion(new \DateTime("now"));
                $em->persist($cv);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('salesianos_main_cv'));
    }

    public function guardarIdiomaAction()
    {
        $request = $this->getRequest();
        $user = $this->container->get('security.context')->getToken()->getUser(); 

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $candidato = $repository->findOneByUsuario($user);

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Curriculum');
        $cv = $repository->findOneByCandidato($candidato);

        $idioma = new Idioma();
        $idioma->setCurriculum($cv);
        $form = $this->createForm(new IdiomaFormType(), $idioma);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($idioma);
                $cv->setUltimaActualizacion(new \DateTime("now"));
                $em->persist($cv);
                $em->flush();
            }
        }
        return $this->redirect($this->generateUrl('salesianos_main_cv'));
    }

    public function guardarOtrosDatosAction()
    {
        $request = $this->getRequest();
        $user = $this->container->get('security.context')->getToken()->getUser(); 

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato');
        $candidato = $repository->findOneByUsuario($user);

        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Curriculum');
        $cv = $repository->findOneByCandidato($candidato);

        $form = $this->createForm(new OtrosDatosFormType(), $cv);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $cv->setUltimaActualizacion(new \DateTime("now"));
                $em->persist($cv);
                $em->flush();
            }
        }
        return $this->render('SalesianosMainBundle:Main:cv.html.twig',array(
                'cv' => $cv,
            ));
    }


}
