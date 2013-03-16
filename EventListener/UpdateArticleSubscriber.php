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
        if ($event->hasArgument('article')) {
            $article = $event->getArgument('article');

            if (!$article->isPublished()) {
                return true;
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
            print_r($response);die();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'article.update' => array('clearCache', 1),
            'article.move' => array('clearCache', 1),
            'article.publish' => array('clearCache', 1),
        );
    }
}