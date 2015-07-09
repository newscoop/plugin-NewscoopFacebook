<?php
/**
 * @package Facebook Meta Plugin
 * @author Yorick Terweijden <yorick.terweijden@sourcefabric.org>
 * @author Rafał Muszyński <rafal.muszynski@sourcefabric.org>
 * @author Paweł Mikołajczuk <pawel.mikolajczuk@sourcefabric.org>
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
 * example usage: 
 *     {{ facebook_meta_block admins="123422,234223432,234234234,23423423" }}{{ /facebook_meta_block }}
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

    $smarty->smarty->loadPlugin('smarty_function_uri');
    $context = $smarty->getTemplateVars('gimme');
    $systemPreferences = \Zend_Registry::get('container')->get('preferences');

    $html = '';
    if ($context->article->defined) {
        $html .= '<meta property="og:title" content="'.$context->article->name.'" />'."\n";
        $html .= '<meta property="og:type" content="article" />'."\n";
        $html .= '<meta property="og:url" content="http://'.$context->publication->site. smarty_function_uri($params, $smarty) .'" />'."\n";
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        $html .= '<meta property="og:description" content="'.strip_tags($context->article->deck).'" />'."\n";
        $html .= '<meta property="article:section" content="'.$context->section->name.'" />'."\n";
        if ($context->article->is_published) {
            $html .= '<meta property="article:published_time" content="'.$context->article->publish_date.'" />'."\n";
        }
        if ($systemPreferences->get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.$systemPreferences->get('facebook_appid').'" />'."\n";
        }
        if (array_key_exists('admins', $params)) {
            foreach ($params as $key => $value) {
                $html .= '<meta property="fb:admins" content="'.$value.'" />'."\n";
            }
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
        $html .= '<meta property="og:url" content="http://'.$context->publication->site. smarty_function_uri($params, $smarty) .'" />'."\n";
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        if ($context->section->description) {
            $html .= '<meta property="og:description" content="'.strip_tags($context->section->description).'" />'."\n";
        }
        $html .= '<meta property="article:section" content="'.$context->section->name.'" />'."\n";
        if ($systemPreferences->get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.$systemPreferences->get('facebook_appid').'" />'."\n";
        }
        if (array_key_exists('admins', $params)) {
            foreach ($params as $key => $value) {
                $html .= '<meta property="fb:admins" content="'.$value.'" />'."\n";
            }
        }
    } else if ($context->issue->defined) {
        $html .= '<meta property="og:title" content="'.$context->issue->name.'" />'."\n";
        $html .= '<meta property="og:type" content="article" />'."\n";
        $html .= '<meta property="og:url" content="http:/'.$context->publication->site. smarty_function_uri($params, $smarty) .'" />'."\n";
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        $html .= '<meta property="article:section" content="'.$context->issue->name.'" />'."\n";
        if ($context->issue->is_published) {
            $html .= '<meta property="article:published_time" content="'.$context->issue->publish_date.'" />'."\n";
        }
        if ($systemPreferences->get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.$systemPreferences->get('facebook_appid').'" />'."\n";
        }
        if (array_key_exists('admins', $params)) {
            foreach ($params as $key => $value) {
                $html .= '<meta property="fb:admins" content="'.$value.'" />'."\n";
            }
        }
    } else {
        $html .= '<meta property="og:site_name" content="'.$context->publication->name.'" />'."\n";
        if ($systemPreferences->get('facebook_appid')) {
            $html .= '<meta property="fb:app_id" content="'.$systemPreferences->get('facebook_appid').'" />'."\n";
        }
        if (array_key_exists('admins', $params)) {
            foreach ($params as $key => $value) {
                $html .= '<meta property="fb:admins" content="'.$value.'" />'."\n";
            }
        }
    }

    return $html;
}
