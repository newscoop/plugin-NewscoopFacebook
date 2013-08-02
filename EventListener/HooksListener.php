<?php

namespace Newscoop\FacebookNewscoopBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Newscoop\EventDispatcher\Events\PluginHooksEvent;

class HooksListener
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function sidebar(PluginHooksEvent $event)
    {
        $response = $this->container->get('templating')->renderResponse(
            'NewscoopFacebookNewscoopBundle:Hooks:sidebar.html.twig',
            array(
                'pluginName' => 'Facebook article cache',
                'article' => $event->getArgument('article')
            )
        );

        $event->addHookResponse($response);
    }
}