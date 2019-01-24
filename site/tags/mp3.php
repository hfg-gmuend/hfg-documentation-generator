<?php
kirbytext::$tags['mp3'] = array(
  'attr' => array(
    'class'
  ),
  'html' => function($tag) {
    $url = $tag->attr('mp3');
    $file = $tag->file($url);
    $class = $tag->attr('class', 'mp3');
    $url = $file ? $file->url() : url($url); // use the file url if available or otherwise the given url
    return '<audio class="col-11 col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-2 '.$class.'" preload="auto" controls="controls"><source src="'.$url.'" type="audio/mp3"></audio>';
  }
);
