<?php

namespace AHS\FacebookNewscoopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
    * @Route("/admin/ahs/facebook-plugin/clear-cache")
    */
    public function indexAction(Request $request)
    {   
        $facebookInfo = $this->clearpageCache($request->get('articleNumber'), $request->get('languageId'));

        if (is_array($facebookInfo)) {
            if (array_key_exists('message', $facebookInfo)) {
                return new Response(json_encode(array(
                    'status' => false, 
                    'message' => $facebookInfo['message']
                )));
            }
        }

        return new Response(json_encode(array(
            'status' => true, 
            'facebookInfo' => json_decode($facebookInfo)
        )));
    }

    /**
     * Send request for refresh cache in Facebook
     * @param  [int $number
     * @param  int $languageId
     * @return mixed             response from Facebook about url, or array with error message
     */
    private function clearpageCache($number, $languageId)
    {
        $article = new \Article($languageId, $number);

        if (!$article->isPublished()) {
            return array('message' => getGS('Article is not plublished'));
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
        } catch(\Buzz\Exception\ClientException $e) {
             return array('message' => getGS('Connection with facebook failed'));
        }

        return $urlInfo->getContent();
    }
}