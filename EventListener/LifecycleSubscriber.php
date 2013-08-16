<?php
/**
 * @package Newscoop\FacebookNewscoopBundle
 * @author Paweł Mikołajczuk <pawel.mikolajczuk@sourcefabric.org>
 * @copyright 2012 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\FacebookNewscoopBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newscoop\EventDispatcher\Events\GenericEvent;

/**
 * Event lifecycle management
 */
class LifecycleSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function install(GenericEvent $event)
    {
        if ($event->getArgument('plugin_name') != 'newscoop/facebook-newscoop-bundle') {
            return;
        }

        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function update(GenericEvent $event)
    {
        if ($event->getArgument('plugin_name') != 'newscoop/facebook-newscoop-bundle') {
            return;
        }


        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->updateSchema($this->getClasses(), true);

        // Generate proxies for entities
        $this->em->getProxyFactory()->generateProxyClasses($this->getClasses(), __DIR__ . '/../../../../library/Proxy');
    }

    public function remove(GenericEvent $event)
    {
        if ($event->getArgument('plugin_name') != 'newscoop/facebook-newscoop-bundle') {
            return;
        }
        
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $tool->dropSchema($this->getClasses(), true);
    }

    public static function getSubscribedEvents()
    {
        return array(
            'plugin.install' => array('install', 1),
            'plugin.update' => array('update', 1),
            'plugin.remove' => array('remove', 1),
        );
    }

    private function getClasses(){
        return array(
          $this->em->getClassMetadata('Newscoop\FacebookNewscoopBundle\Entity\Facebook'),
        );
    }
}