<?php
/**
 * @package Facebook Meta Plugin
 * @author Yorick Terweijden <yorick.terweijden@sourcefabric.org>
 * @copyright 2013 Sourcefabric o.p.s.
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Newscoop facebook_meta block plugin
 *
 * Type:     block
 * Name:     facebook_meta
 * Purpose:  Generates the Facebook Meta information for a page
 *
 * @param string
 *     $params
 * @param string
 *     $p_smarty
 * @param string
 *     $content
 *
 * @return
 *
 */
function smarty_block_facebook_meta_block($params, $content, &$smarty, &$repeat)
{
    if (!isset($content)) {
        return '';
    }

    $smarty->smarty->loadPlugin('smarty_shared_escape_special_chars');
    $context = $smarty->getTemplateVars('gimme');

    $html = '';
    if ($context->article->defined) {
        $html .= '<meta property="og:title" content="'.$context->article->name.'" />'."\n";
        $html .= '<meta property="og:type" content="article" />'."\n";
        $html .= '<meta property="og:url" content="http:/'.$context->publication->site. smarty_function_uri($params, $smarty) .'" />'."\n";
        $html .= '<meta property="og:site_name" content="'. $context->publication->name .'" />'."\n";
        $html .= '<meta property="og:description" content="'.strip_tags($context->article->deck).'" />'."\n";
        if ($context->article->image->imageurl) {
            $html .= '<meta property="og:image" content="'. $context->article->image->imageurl .'" />'."\n";
        }
    } else {
        $html .= '<meta property="og:site_name" content="'. $context->publication->name .'" />'."\n";
    }

    return $html;
}