<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>
<?php include('settings.php'); ?>
<?php include('process_settings.php'); ?>
<title><?php print($game_title); ?></title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="<?php print($data_file);?>"></script>
<script type="text/javascript" src="cite-tetris.js"></script>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<?php
     if ($audioOK) { 
         include ("audio.php");
     }
?>
<?php print($game_header); ?>
<div id="game">
     <div id="item"><?php print($item_label_cap);?>:</div>
<div id="debug">Debug:</div>
<div id="score">Level: 1<br />Score: 0</div>
<div id="controls"></div>
<div id="long-stats"></div>                                         
<div id="grid">
<table>
<tr><td id="row1"></td></tr>
<tr><td id="row2"></td></tr>
<tr><td id="row3"></td></tr>
<tr><td id="row4"></td></tr>
<tr><td id="row5"></td></tr>
<tr><td id="row6"></td></tr>
<tr><td id="row7"></td></tr>
<tr><td id="row8"></td></tr>
<tr><td id="row9"></td></tr>
<tr><td id="row10"></td></tr>
<tr><td id="row11"></td></tr>
<tr><td id="row12"></td></tr>
</table>
</div>
</div>
</html>
