<!DOCTYPE html>
<html>

<?
session_start();
$_SESSION["level"] = 'admin';
?>

<head>
	<title>Bitung Smart City</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Bitung Smart City - Menjadikan kota yang cerdas dengan kemajuan teknologi demi kesejahteraan rakyat. Ayo Bergabunglah!">
	<meta name="keywords" content="bitung smart city, Bitung Smart City, smart city bitung, Smart City Bitung, smart city, bitung, kota bitung">
	<meta name="author" content="Aditia Wensen">
	<script src="aw-js-plugins/jquery.min.js"></script>
	<link rel="stylesheet" href="aw-css/aw.css">
	<link rel="stylesheet" href="aw-css/w3.css">
</head>

<script>
$(document).ready(function(){
	var header = '';
	header += '<div id="header" class="aw-row aw-d3 aw-center aw-shadow aw-padding">',
	header += 	'<div><img src="aw-images/c3.png" width="20px" height="20px"> <span><b><i>Bitung Smart City System</i><b></span></div>',
	header += '</div>';
	$('body').prepend(header);
	var menu = '';
	menu += `<div id="menu"><span class="aw-small"><b>MENU</b></span></div>`,
	menu += `<div id="menu-items"></div>`,
	menu += `<script src="aw-js-plugins/aw-menu.js"><\/script>`,
	menu += `<script>createMenu('<?=$_SESSION["level"]?>')<\/script>`;
	$('body').prepend(menu);
});
</script>

<body>
	<div class="w3-row" style="height:92%;overflow:auto"><? include "aw-pages/".$_GET['page']."/index.php";?></div>
</body>

</html>