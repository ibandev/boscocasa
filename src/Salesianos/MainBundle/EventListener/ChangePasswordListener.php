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
class ChangePasswordListener implements EventSubscriberInterface
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
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'fos_user.change_password.edit.completed',
        );
    }

    public function onChangePasswordCompleted(FilterUserResponseEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('salesianos_admin_homepage'));
            $event->setResponse($response);
        } else{
            $response = new RedirectResponse($this->router->generate('salesianos_main_miperfil'));
            $event->setResponse($response);
        }
        
    }

}