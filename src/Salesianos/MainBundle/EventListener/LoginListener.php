<?php
// src/Salesianos/MainBundle/EventListener/LoginListener.php

namespace Salesianos\MainBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\FOSUserBundle;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Salesianos\MainBundle\Entity\Candidato;
use Salesianos\MainBundle\Entity\Curriculum;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\Event;

/**
 * Listener responsible to change the redirection at the end of the login
 */
class LoginListener implements EventSubscriberInterface
{
    private $router;
    private $container;
    private $security;
    private $dispatcher;

    public function __construct(ContainerInterface $container, Router $router, SecurityContext $security, TraceableEventDispatcher $dispatcher)
    {
        $this->container = $container;
        $this->router = $router;
        $this->security = $security;
        $this->dispatcher = $dispatcher;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onSecurityInteractiveLogin',
        );
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {

        $token = $event->getAuthenticationToken();
        $request = $event->getRequest();

        $username = $token->getUsername();
        $em = $this->container->get('doctrine')->getEntityManager();
        $user = $em->getRepository('SalesianosMainBundle:Usuario')->findOneByUsername($username);

        if(!$user->hasRole("ROLE_ADMIN")){
            
            if(!$user->hasRole("ROLE_EMPRESA")){

                $candidato = $em->getRepository('SalesianosMainBundle:Candidato')->findOneByUsuario($user);

                if($candidato==null){

                    $user->addRole("ROLE_ALUMNO");           
                    $candidato = new Candidato();                
                    $cv = new Curriculum();
                    $cadiz = $em->getRepository('SalesianosMainBundle:Provincia')->find(11);
                    
                    $candidato->setUsuario($user);
                    $candidato->setProvincia($cadiz);
                    $candidato->setCurriculum($cv);
                    $cv->setCandidato($candidato);
                    $em->persist($user);
                    $em->persist($candidato);
                    $em->persist($cv);
                    $em->flush();

                    //Actualizo el usuario para que tenga el rol nuevo
                    $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
                    $this->container->get('security.context')->setToken($token);
                }
            }
        }
        
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->security->isGranted('ROLE_EMPRESA')) {
            $response = new RedirectResponse($this->router->generate('salesianos_main_datos_empresa'));
            $event->setResponse($response);
        } elseif ($this->security->isGranted('ROLE_ALUMNO')) {
            $response = new RedirectResponse($this->router->generate('salesianos_main_datos_alumno'));
            $event->setResponse($response);
        } elseif ($this->security->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('salesianos_admin_homepage'));
            $event->setResponse($response);
        }
        
    }

}