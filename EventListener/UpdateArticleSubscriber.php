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
        if ($event->getName() == 'article.publish') {
            $article = $article = new \Article($event->getArgument('number'), $event->getArgument('language'));
        } else if ($event->getName() == 'article.update' || $event->getName() == 'Articles.update') {
            $articleData = $event->getArgument('id');
            $article = new \Article($articleData['Number'], $articleData['IdLanguage']);
        } else if ($event->getName() == 'article.move') {
            $article = $event->getArgument('article');
        }

        $url = \ShortURL::GetURL(
            $article->getPublicationId(),
            $article->getLanguageId(),
            $article->getIssueNumber(),
            $article->getSectionNumber(),
            $article->getArticleNumber()
        );

        $browser = new \Buzz\Browser(new \Buzz\Client\Curl());
        $response = $browser->post('http://developers.facebook.com/tools/debug', array(
            'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13'
        ), http_build_query(array(
            'q' => $url
        )));
    }

    public static function getSubscribedEvents()
    {
        return array(
            'Articles.update' => array('clearCache', 1),
            'article.update' => array('clearCache', 1),
            'article.move' => array('clearCache', 1),
            'article.publish' => array('clearCache', 1)
        );
    }
}