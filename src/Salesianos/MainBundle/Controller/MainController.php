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

class MainController extends Controller
{
	
    public function indexAction()
    {
        //Recupera las 3 últimas ofertas
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta');
        $query = $repository->createQueryBuilder('o')
                ->orderBy('o.fecha_ini','DESC')
                ->where('o.visible = 1')
                ->setMaxResults(4)              
                ->getQuery();
        $ofertas = $query->getResult();

        //Recupera el último artículo
        $articulo = $this->getDoctrine()->getRepository('SalesianosMainBundle:Articulo')->findLastPublished();    
        return $this->render('SalesianosMainBundle:Main:index.html.twig', array(
                'ofertas' => $ofertas,
                'articulo' => $articulo,
            ));
    }

    // Muestra la página de contacto con el formulario
    public function contactoAction()
    {
        $securityContext = $this->container->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $user = $this->container->get('security.context')->getToken()->getUser();
            $form = $this->createFormBuilder()
                    ->setAction($this->generateUrl('salesianos_main_contactoSend'))
                    ->add('nombre', 'text')
                    ->add('email', 'email', array('data' => $user->getEmail()))
                    ->add('mensaje', 'textarea')
                    ->add('enviar', 'submit')
                    ->getForm();
        }else{
            $form = $this->createFormBuilder()
                    ->setAction($this->generateUrl('salesianos_main_contactoSend'))
                    ->add('nombre', 'text')
                    ->add('email', 'email')
                    ->add('mensaje', 'textarea')
                    ->add('enviar', 'submit')
                    ->getForm();
        }
        return $this->render('SalesianosMainBundle:Main:contacto.html.twig', array('form' => $form->createView()));
    }


    // Procesa el formulario de contacto, envía un correo a administración y muestra un mensaje de 'Envío realizado'
    public function contactoSendAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('nombre', 'text')
            ->add('email', 'email')
            ->add('mensaje', 'textarea')
            ->getForm();         
        $form->handleRequest($request);
        $data = $form->getData();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if($user != null){
            $username = $user->getUsername();
        }else{
            $username = "Sin loguear";
        }
        // Realizar el envío de correo a administración
        $mensaje = \Swift_Message::newInstance()
                ->setSubject('CONTACTO boscoempleo.com')
                ->setTo('soporte.boscoempleo@gmail.com')
                ->setFrom('soporte.boscoempleo@gmail.com')
                ->setBody($this->renderView('SalesianosMainBundle:Admin:mail.html.twig', array(
                    'usuario' => $username,
                    'nombre' => $data['nombre'],
                    'mail' => $data['email'],
                    'mensaje' => $data['mensaje'])))
                ->setContentType('text/html');
        $this->get('mailer')->send($mensaje);

        return $this->render('SalesianosMainBundle:Main:mensaje.html.twig', array(
                    'mensaje' => 'Tu mensaje ha sido enviado. Contestaremos lo más rápido posible.'));
    }


    //Se encarga del cambio de contraseña del usuario
    public function miperfilAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.change_password.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        if ($request->isMethod('POST')) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_profile_show');
                    $response = new RedirectResponse($url);
                }
                $dispatcher->dispatch(FOSUserEvents::CHANGE_PASSWORD_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
                return $response;
            }
        }
        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:ChangePassword:changePassword.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView())
        );
    }

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
                $em->flush();
            }
        }
        return $this->render('SalesianosMainBundle:Main:datos_alumno.html.twig',array(
                'form' => $form->createView(),
                'nif' => $user->getUsername(),
            ));
    }

    //Muestra los datos de la empresa y permite modificarlos
    public function datosempresaAction()
    {
        $request = $this->getRequest();

        $user = $this->container->get('security.context')->getToken()->getUser();         
        $empresa = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->findByUser($user);

        $form = $this->createForm(new EmpresaFormType(), $empresa);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $empresa->upload();
                $em->persist($empresa);
                $em->flush();
            }
        }

        return $this->render('SalesianosMainBundle:Main:datos_empresa.html.twig', array(
                'form' => $form->createView(),
                'empresa' => $empresa
            ));
    }

    //Muestra el CV del candidato y permite modificarlo
    public function cvAction()
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

    public function eliminarcvAction($tipo, $id)
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

    public function addcvAction($tipo)
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

    public function descargarCVAction($id_oferta, $id_candidato)
    {
        $fs = new Filesystem();
        $candidato = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato')->find($id_candidato);
        $path = __DIR__.'/../../../../web/temp/cv_'.$this->quitar_tildes($candidato->getApellidos()).', '.$this->quitar_tildes($candidato->getNombre()).'.pdf';
        if($fs->exists($path)){
           $fs->remove($path);
        }
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'SalesianosMainBundle:Main:candidato.html.twig',
                array(
                    'candidato'  => $candidato
                )
            ), $path,
                array('encoding' => 'UTF-8', 
                      'minimum-font-size' => '20')
            );     
        $content = file_get_contents($path);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="cv_'.$candidato->getApellidos().', '.$candidato->getNombre().'.pdf"');
        $response->setContent($content);
        return $response;
    }

    public function descargartodosCVAction($id_oferta)
    {
        $fs = new Filesystem();
        $oferta = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->find($id_oferta);
        $candidatos = $oferta->getCandidatos();
        if($candidatos == null){
            return $this->redirect($this->generateUrl('salesianos_main_verinscritos',array('oferta' => $oferta)));
        }
        $zip = new \ZipArchive();
        $zipPath =  __DIR__.'/../../../../web/temp/';
        $zipName =  date('dmY').'cvs-'.substr($oferta->getPuesto(), 0, 30).".zip";
        if($fs->exists($zipPath.$zipName)){
            $fs->remove($zipPath.$zipName); 
        }
        $zip->open($zipPath.$zipName,  \ZipArchive::CREATE);

        foreach ($candidatos as $candidato) {
            $path = 'cvs_'.$id_oferta.'/cv_'.$this->quitar_tildes($candidato->getApellidos()).', '.$this->quitar_tildes($candidato->getNombre()).'.pdf';
            if($fs->exists($path)){
               $fs->remove($path); 
            }
            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView(
                    'SalesianosMainBundle:Main:candidato.html.twig',
                    array(
                        'candidato'  => $candidato
                    )
                ), $path,
                    array('encoding' => 'UTF-8', 
                          'minimum-font-size' => '20')
                );
            $i=0;
            $zip->addFile($path);
        }
        $zip->close();

        $content = file_get_contents($zipPath.$zipName);
        
        $response = new Response();        
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$zipName.'"');
        $response->setContent($content);
        return $response;
    }        

    public function empresasAction()
    {
        return $this->render('SalesianosMainBundle:Main:empresas.html.twig');
    }

    public function blogAction()
    {
        return $this->render('SalesianosMainBundle:Main:blog.html.twig');
    }

    public function changePasswdAction(Request $request)
    {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(new ChangePasswordType(), $changePasswordModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            return $this->redirect($this->generateUrl('change_passwd_success'));
        }

          return $this->render('SalesianosMainBundle:Main:miperfil.html.twig', array(
              'form_pwd' => $form->createView(),
          ));      
    }

    public function quitar_tildes($cadena) 
    {
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }


}
