<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
  <title>Hot Topics example (PHP)</title>
  <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
  <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
</head>
<body>
<div id="doc" class="yui-t7">
  <div id="hd" role="banner"><h1>Hot Topics example (PHP)</h1></div>
  <div id="bd" role="main">
    <ul id="hottopics"><li>
      <?php
      $url = 'http://query.yahooapis.com/v1/public/yql?q=select%20content'.
             '%20from%20search.termextract%20where%20context%20in'.
             '%20(select%20content%20from%20html%20where%20url%3D%22'.
             'http%3A%2F%2Fnews.bbc.co.uk%22%20and%20xpath%3D%22%2F%2F'.
             'table%5B%40width%3D800%5D%2F%2Fa%22)&format=json';
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, $url); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      $output = curl_exec($ch); 
      curl_close($ch);
      $data = json_decode($output);
      $topics = array_unique($data->query->results->Result);
      echo join('</li><li>',$topics);
      ?>
    </li></ul>
  </div>
  <div id="ft" role="contentinfo"><p></p></div>
</div>
</body>
</html>
