<?php
/**
 * @package Newscoop\FacebookNewscoopBundle
 * @author Paweł Mikołajczuk <pawel.mikolajczuk@sourcefabric.org>
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\FacebookNewscoopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Newscoop\FacebookNewscoopBundle\Entity\Facebook;

class DefaultController extends Controller
{
    /**
    * @Route("/admin/newscoop/facebook-plugin/index", name="newscoop_facebook_default_index")
    * @Route("/admin/newscoop/facebook-plugin/clear-cache", name="newscoop_facebook_default_clear")
    */
    public function indexAction(Request $request)
    {
        $article = $request->get('articleNumber');
        $language = $request->get('languageId');
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('Newscoop\FacebookNewscoopBundle\Entity\Facebook')
            ->findOneBy(array(
                    'article' => $article,
                    'language' => $language,
                    'is_active' => true,
            ));

        if ($request->get('_route') === "newscoop_facebook_default_clear") {
            $facebookInfo = $this->clearpageCache($article, $language);
            if (is_array($facebookInfo)) {
                if (!array_key_exists('title', $facebookInfo)) {
                    $response = array(
                        'status' => false,
                        'message' => $this->get('translator')->trans('fb.label.noarticle')
                    );

                    return new Response(json_encode($response));
                }
            }

            if (!$info) {
                $this->insert($em, $article, $language, $facebookInfo['title'], $facebookInfo['description'], $facebookInfo['image'][0]['url']);
            } else if (
                $info->getTitle() != $facebookInfo['title'] ||
                $info->getDescription() != $facebookInfo['description'] ||
                $info->getUrl() != $facebookInfo['image'][0]['url']
            ) {
                $info->setTitle($facebookInfo['title']);
                $info->setDescription($facebookInfo['description']);
                $info->setUrl($facebookInfo['image'][0]['url']);
                $em->flush();
            }

            return new Response(json_encode(array(
                'status' => true,
                'title' => $facebookInfo['title'],
                'description' => $facebookInfo['description'],
                'url' => $facebookInfo['image'][0]['url'],
            )));
        } else {
            if (!$info) {
                $facebookInfo = $this->clearpageCache($article, $language);
                if (is_array($facebookInfo)) {
                    if (!array_key_exists('title', $facebookInfo)) {
                        $response = array(
                            'status' => false,
                            'message' => $this->get('translator')->trans('fb.label.noarticle')
                        );

                        return new Response(json_encode($response));
                    }
                }

                $this->insert($em, $article, $language, $facebookInfo['title'], $facebookInfo['description'], $facebookInfo['image'][0]['url']);

                $response = array(
                    'status' => true,
                    'title' => $facebookInfo['title'],
                    'description' => $facebookInfo['description'],
                    'url' => $facebookInfo['image'][0]['url'],
                );
            } else {
                $response = array(
                    'status' => true,
                    'title' => $info->getTitle(),
                    'description' => $info->getDescription(),
                    'url' => $info->getUrl(),
                );
            }

            return new Response(json_encode($response));
        }
    }

    /**
     * Send request to refresh article cache on Facebook
     *
     * @param int $number
     * @param int $languageId
     *
     * @return mixed response from Facebook about url, or array with error message
     */
    private function clearpageCache($number, $languageId)
    {
        $article = new \Article($languageId, $number);

        if (!$article->isPublished()) {
            return array('message' => $this->get('translator')->trans('fb.label.errornot'));
        }

        $url = \ShortURL::GetURL(
            $article->getPublicationId(),
            $article->getLanguageId(),
            $article->getIssueNumber(),
            $article->getSectionNumber(),
            $article->getArticleNumber()
        );

        try {
            $curlClient = new \Buzz\Client\Curl();
            $curlClient->setTimeout(10000);
            $browser = new \Buzz\Browser($curlClient);
            $result =  $browser->post('https://graph.facebook.com/?id='.$url.'&scrape=true');
            $urlInfo = json_decode($result->getContent(), true);
        } catch(\Buzz\Exception\ClientException $e) {
             return array('message' => $this->get('translator')->trans('fb.label.error'));
        }

        return $urlInfo;
    }

    /**
     * Insert article info into database
     *
     * @param Doctrine\ORM\EntityManager $em
     * @param int                        $articleId
     * @param int                        $languageId
     * @param string                     $title
     * @param string                     $description
     * @param string                     $url
     *
     * @return void
     */
    private function insert($em, $articleId, $languageId, $title, $description, $url)
    {
        $information = new Facebook();
        $information->setArticle($articleId);
        $information->setLanguage($languageId);
        $information->setTitle($title);
        $information->setDescription($description);
        $information->setUrl($url);
        $em->persist($information);
        $em->flush();
    }
}
