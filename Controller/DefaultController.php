<?php
/**
 * @package AHS\FacebookNewscoopBundle
 * @author Paweł Mikołajczuk <pawel.mikolajczuk@sourcefabric.org>
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace AHS\FacebookNewscoopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AHS\FacebookNewscoopBundle\Entity\Facebook;

class DefaultController extends Controller
{
    /**
    * @Route("/admin/ahs/facebook-plugin/index", name="ahs_facebook_newscoop_default_index")
    * @Route("/admin/ahs/facebook-plugin/clear-cache", name="ahs_facebook_newscoop_default_clear")
    */
    public function indexAction(Request $request)
    {   
        $article = $request->get('articleNumber');
        $language = $request->get('languageId');
        $em = $this->getDoctrine()->getManager();
        $info = $em->getRepository('AHS\FacebookNewscoopBundle\Entity\Facebook')
            ->findOneBy(array(
                    'article' => $article,
                    'language' => $language,
                    'is_active' => true,
            ));

        if ($request->get('_route') === "ahs_facebook_newscoop_default_clear") {
            $facebookInfo = $this->clearpageCache($article, $language);
            if (is_array($facebookInfo)) {
                if (array_key_exists('message', $facebookInfo)) {
                    return new Response(json_encode(array(
                        'status' => false, 
                        'message' => $facebookInfo['message']
                    )));
                }
            }

            if (!$info) {
                $this->insert($em, $article, $language, $facebookInfo['title'], $facebookInfo['description'], $facebookInfo['picture']['data']['url']);
            } else if (
                $info->getTitle() != $facebookInfo['title'] || 
                $info->getDescription() != $facebookInfo['description'] || 
                $info->getUrl() != $facebookInfo['picture']['data']['url']
            ) {
                $info->setTitle($facebookInfo['title']);
                $info->setDescription($facebookInfo['description']);
                $info->setUrl($facebookInfo['picture']['data']['url']);
                $em->flush();
            }

            return new Response(json_encode(array(
                'status' => true, 
                'title' => $facebookInfo['title'],
                'description' => $facebookInfo['description'],
                'url' => $facebookInfo['picture']['data']['url'],
            )));
        } else {
            if (!$info) {
                $facebookInfo = $this->clearpageCache($article, $language);
                if (is_array($facebookInfo)) {
                    if (array_key_exists('message', $facebookInfo)) {
                        return new Response(json_encode(array(
                            'status' => false, 
                            'message' => $facebookInfo['message']
                        )));
                    }
                }

                $this->insert($em, $article, $language, $facebookInfo['title'], $facebookInfo['description'], $facebookInfo['picture']['data']['url']);

                return new Response(json_encode(array(
                    'status' => true, 
                    'title' => $information->getTitle(),
                    'description' => $information->getDescription(),
                    'url' => $information->getUrl(),
                )));
            }

            return new Response(json_encode(array(
                'status' => true, 
                'title' => $info->getTitle(),
                'description' => $info->getDescription(),
                'url' => $info->getUrl(),
            )));
        }   
    }

    /**
     * Send request to refresh article cache on Facebook
     * @param  int $number
     * @param  int $languageId
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
            $browser = new \Buzz\Browser(new \Buzz\Client\Curl());
            $response =  $browser->post('http://developers.facebook.com/tools/debug', array(
                'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13'
            ), http_build_query(array(
                'q' => $url
            )));

            preg_match_all('/graph.facebook.com\/[0-9]+"/', $response->getContent(), $matches);
            
            $pageId = str_replace('graph.facebook.com/', '', str_replace('"', '', $matches[0][0]));
            $urlInfo = $browser->get('http://graph.facebook.com/'.$pageId);
            $urlPicture = $browser->get('http://graph.facebook.com/'.$pageId.'?fields=picture');
            $info = array_merge_recursive(
                json_decode($urlInfo->getContent(), true), 
                json_decode($urlPicture->getContent(), true)
            );
        } catch(\Buzz\Exception\ClientException $e) {
             return array('message' => $this->get('translator')->trans('fb.label.error'));
        }

        return $info;
    }

    /**
     * Insert article info into database
     * @param Doctrine\ORM\EntityManager $em
     * @param  int $article
     * @param  int $language
     * @param  string $title
     * @param  string $description
     * @param  string $url
     * @return void
     */
    private function insert($em, $articleId, $languageId, $title, $description, $url) {
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