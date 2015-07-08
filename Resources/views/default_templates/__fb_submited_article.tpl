<!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ $gimme->article->name }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="description" content="{{ $gimme->article->deck|strip_tags:false|strip|escape:'html':'utf-8' }}">
        <meta name="keywords" content="{{ $gimme->article->keywords }}" />
        {{ facebook_meta_block }}{{ /facebook_meta_block }}
        <meta property="article:publisher" content="https://www.facebook.com/{{ $gimme->publication->site }}" />
        <meta property="og:author" content="http://facebook.com/{{ $gimme->publication->site }}" />
        <meta name="author" content="{{ $gimme->publication->name }}" />
    </head>
</html>
