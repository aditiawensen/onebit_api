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
	<link rel="stylesheet" href="aw-css/w3.css">
	<link rel="stylesheet" href="aw-css/aw.css">
</head>

<script>
$(document).ready(function(){
	var header = '';
	header += '<div id="header" class="w3-row w3-blue w3-center w3-card-2 w3-padding">',
	header += 	'<div><img src="aw-images/c3.png" width="20px" height="20px"> <span><b><i>Bitung Smart City System</i><b></span></div>',
	header += '</div>';
	$('body').prepend(header);
	var menu = '';
	menu += `<script src="aw-js-plugins/aw-menu.js"><\/script>`,
	$('body').prepend(menu);
	var page = '';
	page += `<div id="page" style="overflow:auto"><? include "body.php";?></div>`;
	$('body').append(page);
});
</script>

<body style="background:url('aw-images/GIS.jpg');background-size:cover;height:100%;"></body>
</html>