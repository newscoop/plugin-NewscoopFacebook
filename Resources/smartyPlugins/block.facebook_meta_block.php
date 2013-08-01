<?php
/**
 * @package Facebook Meta Plugin
 * @author Yorick Terweijden <yorick.terweijden@sourcefabric.org>
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
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
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        $html .= '<meta property="og:locale" content="'. $context->article->language->code .'" />'."\n";
        $html .= '<meta property="og:description" content="'.strip_tags($context->article->deck).'" />'."\n";
        $html .= '<meta property="article:section" content="'.$context->section->name.'" />'."\n";
        $html .= '<meta property="article:author" content="'.$context->article->author->first_name.' '.$context->article->author->last_name.'" />'."\n";
        $html .= '<meta property="article:published_time" content="'.$context->article->publish_date.'" />'."\n";
        if (\SystemPref::Get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.\SystemPref::Get('facebook_appid').'" />'."\n";
        }
        if ($context->article->keywords) {
            $html .= '<meta property="article:tag" content="'.$context->article->keywords.'" />'."\n";
        }
        if ($context->article->image->imageurl) {
            $html .= '<meta property="og:image" content="'. $context->article->image->imageurl .'" />'."\n";
        }
    } else if ($context->section->defined) {
        $html .= '<meta property="og:title" content="'.$context->section->name.'" />'."\n";
        $html .= '<meta property="og:type" content="article" />'."\n";
        $html .= '<meta property="og:url" content="http:/'.$context->publication->site. smarty_function_uri($params, $smarty) .'" />'."\n";
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        $html .= '<meta property="og:locale" content="'. $context->section->language->code .'" />'."\n";
        if ($context->section->description) {
            $html .= '<meta property="og:description" content="'.strip_tags($context->section->description).'" />'."\n";
        }
        $html .= '<meta property="article:section" content="'.$context->section->name.'" />'."\n";
        if (\SystemPref::Get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.\SystemPref::Get('facebook_appid').'" />'."\n";
        }
    } else if ($context->issue->defined) {
        $html .= '<meta property="og:title" content="'.$context->issue->name.'" />'."\n";
        $html .= '<meta property="og:type" content="article" />'."\n";
        $html .= '<meta property="og:url" content="http:/'.$context->publication->site. smarty_function_uri($params, $smarty) .'" />'."\n";
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        $html .= '<meta property="og:locale" content="'. $context->issue->language->code .'" />'."\n";
        $html .= '<meta property="article:section" content="'.$context->issue->name.'" />'."\n";
        $html .= '<meta property="article:published_time" content="'.$context->issue->publish_date.'" />'."\n";
        if (\SystemPref::Get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.\SystemPref::Get('facebook_appid').'" />'."\n";
        }
    } else {
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        if (\SystemPref::Get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.\SystemPref::Get('facebook_appid').'" />'."\n";
        }
    }

    return $html;
}