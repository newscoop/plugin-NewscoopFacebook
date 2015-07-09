<?php
/**
 * @package Newscoop\FacebookNewscoopBundle
 * @author Paweł Mikołajczuk <pawel.mikolajczuk@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace Newscoop\FacebookNewscoopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Newscoop\Entity\Article;

/**
 * Redirects article with webcode to original article link
 */
class WebcodeController extends Controller
{
    /**
     * @Route("/f/{webcode}", requirements={"webcode" = "^\+[a-z0-9]{5}"}, name="facebook_plugin_share_url")
     */
    public function webcodeAction(Request $request, $webcode)
    {
        $em = $this->get('em');
        $linkService = $this->get('article.link');
        $response = new Response();
        $templatesService = $this->get('newscoop.templates.service');

        $article = $em->getRepository('Newscoop\Entity\Article')
            ->createQueryBuilder('a')
            ->where('a.webcode = :webcode')
            ->setParameter('webcode', str_replace('+', '', $webcode))
            ->getQuery()
            ->getOneOrNullResult();

        if (!is_null($article) && in_array($request->server->get('HTTP_USER_AGENT'), array(
          'facebookexternalhit/1.1 (+https://www.facebook.com/externalhit_uatext.php)',
          'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)'
        )) && $article->getWorkflowStatus() === Article::STATUS_SUBMITTED) {
            $smarty = $templatesService->getSmarty();
            $smarty->addTemplateDir(__DIR__.'/../Resources/views/default_templates/');

            $gimme = $smarty->getTemplateVars('gimme');
            $gimme->setPreviewMode(true);
            $articleObj = new \MetaArticle($article->getLanguageId(), $article->getNumber());
            $gimme->article = $articleObj;

            $response->setContent($templatesService->fetchTemplate('__fb_submited_article.tpl'));
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        }

        if (!is_null($article) && $article->isPublished()) {
            $link = $linkService->getLink($article);

            return $this->redirect($link, 301);
        }

        $response->setContent($templatesService->fetchTemplate('404.tpl'));
        $response->setStatusCode(Response::HTTP_NOT_FOUND);

        return $response;
    }
}
