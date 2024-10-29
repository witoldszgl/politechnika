<?php
header("Cache-Control: no-cache, ,no-store, must-revalidate, max-age=0");
header("Age: 0");
$path = explode('/',dirname(__FILE__));
$username = array_pop($path);
$zadania = array();
foreach(scandir(dirname(__FILE__)) as $dir) if( is_dir($dir) and $dir!='.' and $dir!='..' and substr($dir,0,7)=='zadanie' ) $zadania[] = $dir; 
?><!DOCTYPE html>
<html>
<head>
  <meta http-equiv='content-type' content='text/html; charset=utf-8'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />   
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon.png">   
  <link rel="stylesheet" href="w3.css">
  <title>Serwis przedmiotu WWWiJS dla: <?=$username?></title>
</head>
<body class="w3-continer w3-sand">
<header class="w3-panel">
<h1>Serwis przedmiotu WWWiJS dla: <?=$username?></h1>
</header>
<nav class="w3-bar w3-blue w3-border w3-card-4">
<a class="w3-bar-item w3-button w3-mobile w3-aqua" href="/<?=$username?>/" title="Strona pocz¹tkowa serwisu" target="_self" >Strona pocz¹tkowa serwisu</a>&nbsp 
<?php $n=1; foreach($zadania as $zadanie){ ?>
<a class="w3-bar-item w3-button w3-mobile" href="<?=$zadanie?>/" title="<?=$zadanie?>" target="_self" >Z<?=$n?></a>&nbsp 
<?php $n+=1; } ?>
</nav>
<article  class="w3-panel">
<header><h1>Tytu³ wiadomoœci</h1></header>
<p>
Zawartoœæ wiadomoœci......
</p>
<footer>Stopka wiadomoœci:</footer>
</article>
<footer class="w3-panel w3-border-top w3-text-gray w3-center">
<p>Stopka dokumentu: IR - WIiT PP - <?=date("Y")?></p>
</footer>
</body>
</html>