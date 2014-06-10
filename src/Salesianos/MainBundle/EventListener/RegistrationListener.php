<?php
// src/Salesianos/MainBundle/EventListener/LoginListener.php

namespace Salesianos\MainBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\FOSUserBundle;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Salesianos\MainBundle\Entity\Candidato;
use Salesianos\MainBundle\Entity\Empresa;
use Salesianos\MainBundle\Entity\Curriculum;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
/**
 * Listener responsible to change the redirection at the end of the login
 */
class RegistrationListener implements EventSubscriberInterface
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
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();

        $em = $this->container->get('doctrine')->getEntityManager();

        $user->addRole("ROLE_EMPRESA");
        $empresa = new Empresa();
        $empresa->setUsuario($user);
        $cadiz = $em->getRepository('SalesianosMainBundle:Provincia')->find(11);
        $empresa->setProvincia($cadiz);
        $em->persist($empresa);
        $em->flush();

        $token = new UsernamePasswordToken($user, null, 'secured_area', $user->getRoles());
        $this->container->get('security.context')->setToken($token);

        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = new RedirectResponse($this->router->generate('salesianos_main_datos_empresa'));
        $event->setResponse($response);
    }

}