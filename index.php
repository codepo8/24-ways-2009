<?php
/* YouTube RSS */
$query = 'select description from rss(5) where url="http://gdata.youtube.com/feeds/base/users/chrisheilmann/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile";';

/* Flickr search by user id */
$query .= 'select farm,id,owner,secret,server,title from flickr.photos.search where user_id="11414938@N00";';

/* Delicious RSS */
$query .= 'select title,link from rss where url="http://feeds.delicious.com/v2/rss/codepo8?count=10";';

/* Blog RSS */
$query .= 'select title,link from rss where url="http://feeds.feedburner.com/wait-till-i/gwZf"';

/* The YQL web service root with JSON as the output */
$root = 'http://query.yahooapis.com/v1/public/yql?format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

/* Assemble the query */
$query = "select * from query.multi where queries='".$query."'";
$url = $root . '&q=' . urlencode($query);

/* Do the curl call (access the data just like a browser would) */
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch); 
curl_close($ch);
$data = json_decode($output);
$results = $data->query->results->results;

/* YouTube output */
$youtube = '<ul id="youtube">';
foreach($results[0]->item as $r){
  $cleanHTML = undoYouTubeMarkupCrimes($r->description);
  $youtube .= '<li>'.$cleanHTML.'</li>';
}
$youtube .= '</ul>';

/* Flickr output */
$flickr = '<ul id="flickr">';
foreach($results[1]->photo as $r){
  $flickr .= '<li>'.
             '<a href="http://www.flickr.com/photos/codepo8/'.$r->id.'/">'.
             '<img src="http://farm' .$r->farm . '.static.flickr.com/'.
             $r->server . '/' . $r->id . '_' . $r->secret . 
             '_s.jpg" alt="'.$r->title.'"></a></li>';
}
$flickr .= '</ul>';

/* Delicious output */
$delicious = '<ul id="delicious">';
foreach($results[2]->item as $r){
  $delicious .= '<li><a href="'.$r->link.'">'.$r->title.'</a></li>';
}
$delicious .= '</ul>';

/* Blog output */
$blog = '<ul id="blog">';
foreach($results[3]->item as $r){
  $blog .= '<li><a href="'.$r->link.'">'.$r->title.'</a></li>';
}
$blog .= '</ul>';

function undoYouTubeMarkupCrimes($str){
  $cleaner = preg_replace('/555px/','100%',$str);
  $cleaner = preg_replace('/width="[^"]+"/','',$cleaner);
  $cleaner = preg_replace('/<tbody>/','<colgroup><col width="20%"><col width="50%"><col width="30%"></colgroup><tbody>',$cleaner);
  return $cleaner;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
 "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Christian Heilmann on the web</title>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.8.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <style type="text/css" media="screen">
html,body{background:#369;color:#fff;}
#doc2{border:1em solid #fff;background:#fff; color:#000;font-family:calibri,helvetica,sans-serif;-moz-border-radius:5px;-webkit-border-radius:5px}
li{padding:.2em 0;line-height:1.3em}
a:link{color:#369;}
a:visited{color:#666;}
a:hover,a:active{color:#69c;}
h1{font-size:200%;font-weight:bold;padding:.2em 0;}
h2{margin:.5em 0;font-size:150%;background:#69c;padding:.2em;-moz-border-radius:5px;color:#fff;font-weight:bold;-moz-box-shadow: 0px 4px 2px -2px #333;-moz-border-radius:5px;-webkit-border-radius:5px;text-shadow: #333 1px 1px;}
#flickr li{float:left;}
#flickr img,#youtube img{display:block;padding:2px;border:1px solid #ccc;margin:2px;}
#flickr{overflow:auto;}
#ft p{border-top:1px solid #999;margin-top:2em;padding:.5em;}
  </style>
</head>
<body>
<div id="doc2" class="yui-t7">
  <div id="hd" role="banner"><h1>Christian Heilmann on the web</h1></div>
  <div id="bd" role="main">
    <div class="yui-g">
      <div class="yui-u first">
        <h2>My blog</h2>
        <?php echo $blog; ?>
        <h2>My links</h2>
        <?php echo $delicious; ?>
        <h2>My Photos</h2>
        <?php echo $flickr; ?>
      </div>
      <div class="yui-u">
        <h2>My videos</h2>
        <?php echo $youtube; ?>
      </div>
    </div>
  </div>
  <div id="ft" role="contentinfo"><p>Written by <a href="http://wait-till-i.com">Chris Heilmann</a>, powered by <a href="http://developer.yahoo.com/yql">YQL</a> and <a href="http://developer.yahoo.com/yui">YUI</a>.</p></div>
</div>
</body>
</html>