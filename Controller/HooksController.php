<?php

namespace AHS\FacebookNewscoopBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Newscoop\EventDispatcher\Events\PluginHooksEvent;

class HooksController
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function sidebarAction(PluginHooksEvent $event)
    {
        $response = $this->container->get('templating')->renderResponse(
            'AHSFacebookNewscoopBundle:Hooks:sidebar.html.twig',
            array(
                'pluginName' => 'Facebook article cache',
                'article' => $event->getArgument('article')
            )
        );

        $event->addHookResponse($response);
    }
}