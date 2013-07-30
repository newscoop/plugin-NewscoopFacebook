<?php
/**
 * @package AHS\FacebookNewscoopBundle
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
    * @Route("/admin/ahs/facebook-plugin/clear-cache")
    */
    public function indexAction(Request $request)
    {   
        //$this->container->get('dispatcher')->dispatch('plugin.install', new \Newscoop\EventDispatcher\Events\GenericEvent($this, array( 'Facebook Plugin' => '' )));
        $create = false;
        $informations = new Facebook();
        $em = $this->getDoctrine()->getManager();
        $informations = $em->getRepository('AHS\FacebookNewscoopBundle\Entity\Facebook')
            ->findOneBy(array(
                    'article' => $request->get('articleNumber'),
                    'language' => $request->get('languageId'),
                    'is_active' => true,
            ));
        if (!$informations) {
            $create = true;
        }
        if ($informations) {
            $facebookInfo = $this->clearpageCache($request->get('articleNumber'), $request->get('languageId'));

            if (is_array($facebookInfo)) {
                if (array_key_exists('message', $facebookInfo)) {
                    return new Response(json_encode(array(
                        'status' => false, 
                        'message' => $facebookInfo['message']
                    )));
                }
            }
            $informations->setArticle($request->get('articleNumber'));
            $informations->setLanguage($request->get('languageId'));
            $informations->setTitle($facebookInfo['title']);
            $informations->setDescription($facebookInfo['description']);
            $informations->setUrl($facebookInfo['picture']['data']['url']);
            if ($create) {
                $em->persist($informations);
            }
            $em->flush();
        }

        return new Response(json_encode(array(
            'status' => true, 
            'title' => $informations->getTitle(),
            'description' => $informations->getDescription(),
            'url' => $informations->getUrl(),
        )));
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
            return array('message' => getGS('Article is not plublished'));
        }

        /*$url = \ShortURL::GetURL(
            $article->getPublicationId(),
            $article->getLanguageId(),
            $article->getIssueNumber(),
            $article->getSectionNumber(),
            $article->getArticleNumber()
        );*/
        
        $url = "http://miedzyrzecsiedzieje.pl/pl/miedzyrzec_sie_dzieje/sport/590/Przygotowania-pi%C5%82karzy-do-sezonu-63-z-%C5%81KS-%C5%81azy-ks-mosir-huragan-mi%C4%99dzyrzec-podlaski-%C5%82ks-%C5%82azy-sparing-runda-jesienna-cel-puchar-polski-Jacka-Syryjczyka-Pi%C5%82ka-no%C5%BCna.htm?v=2";

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
             return array('message' => getGS('Connection with facebook failed'));
        }

        return $info;
    }
}