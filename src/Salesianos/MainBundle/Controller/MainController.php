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
        //Recupera las 4 últimas ofertas
        $fecha = new \DateTime('now');
        $repository = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta');
        $query = $repository->createQueryBuilder('o')
                ->orderBy('o.fecha_ini','DESC')
                ->where('o.visible = 1')
                ->andWhere('o.fecha_fin > :fecha_fin')->setParameter('fecha_fin', $fecha->format('Y-m-d'))
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
        if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')){
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

}
