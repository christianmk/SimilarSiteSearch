<?
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);
require("vendor/autoload.php");

$simsite = Unirest::get(
  "https://similarsitecheck-similarsite.p.mashape.com/simsites?key=5RhqLf5wnnKzUvhcXUdN&domain=".$_GET['site'],
  array(
    "X-Mashape-Authorization" => "iG6xxMgcfeREFq3W5r6dF2skWd6P0zKd"
  ),
  null
);
//echo '<pre>';
//var_dump($simsite);
//echo '</pre>';
?>
<!Doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet" />
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<style>
.tile:hover{
background-color:#e1e1e1;
}
</style>

</head>
<body>

<form class="form-horizontal" method="get" action="index.php">
<fieldset>

<!-- Form Name -->
<legend>Similar Site Search</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput"></label>
  <div class="col-md-4">
  <input id="site" name="site" type="text" placeholder="nbc.com" class="form-control input-md">
  <span class="help-block"></span>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn btn-primary col-sm-12">Search!</button>
  </div>
</div>

</fieldset>
</form>


<div class="container">
<?

if ($simsite->body->similarDomains[0]->domain){
for($website=0;$website<18;$website++){
  if($website%3==0){
//   echo "<div class='row'>";
  }
    $page2image = Unirest::get("http://api.page2images.com/restfullink?p2i_url=www.".$simsite->body->similarDomains[$website]->domain."&p2i_device=6&p2i_screen=1024x768&p2i_size=300x300&p2i_imageformat=jpg&p2i_key=584941e6a64b3cc3"
);

$status = "processing";
$timeout = 120;
$start_time = time();
$spendTime = 0;
while($page2image->body->status == "processing" && $spendTime < $timeout){
                $page2image = Unirest::get("http://api.page2images.com/restfullink?p2i_url=www.google.com&p2i_screen=1024x768&p2i_size=300x300&p2i_imageformat=jpg&p2i_key=584941e6a64b3cc3");
                $spendTime = time()-$start_time;
}
switch ($page2image->body->status) {
                case "error":
   echo "<a href=http://www.'".$simsite->body->similarDomains[$website]->domain."'><div class='col-sm-4 tile'><p>title: ".$simsite->body->similarDomains[$website]->title."<br>domain: ".$simsite->body->similarDomains[$website]->domain."</p></div></a>";
         break;
      
  case "finished":
      
      
    echo "<a href=http://www.'".$simsite->body->similarDomains[$website]->domain."'><div class='col-sm-4 tile'><img src='".$page2image->body->image_url."' /><p>title: ".$simsite->body->similarDomains[$website]->title."<br>domain: ".$simsite->body->similarDomains[$website]->domain."</p></div></a>";

      
      break;
}


  
}
}

//var_dump($page2image);
?>
</div>

</body>
</html>