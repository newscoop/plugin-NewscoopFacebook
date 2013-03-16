<?php
/**
 * @package Newscoop\Gimme
 * @author Paweł Mikołajczuk <pawel.mikolajczuk@sourcefabric.org>
 * @copyright 2012 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */
namespace AHS\FacebookNewscoopBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newscoop\EventDispatcher\Events\GenericEvent;


/**
 * Clear facebook cache about page after chnages in article.
 */
class UpdateArticleSubscriber implements EventSubscriberInterface
{
    public function clearCache(GenericEvent $event)
    {
        $article = $event->getArgument('article');

        $browser = new Buzz\Browser();
        $response = $browser->get('http://www.google.com');
    }

    public static function getSubscribedEvents()
    {
        return array(
            'article.update' => array('clearCache', 1),
            'article.move' => array('update', 1),
            'article.publish' => array('remove', 1),
        );
    }
}