<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Тестовое задание</title>
	<link rel="shortcut icon" href="/i/favicon.png" type="image/png">
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link href="<?=$this->_path?>/css/style.css" rel="stylesheet" />
</head>
<body>
	<div id="wrap">
<?php if(is_array($_mainMenu)){?>
		<header>
			<nav id="main-menu">
				<div class="bg"></div>
				<a class="dat-menu-button"><i class="fa fa-bars"></i><span>Меню</span></a>
				<ul class="main-menu-placeholder">
<?php
					$_sel = ((isset($_REQUEST["act"]) && array_key_exists("/".$_REQUEST["act"],$_mainMenu))?"/".$_REQUEST["act"]:"/");
					foreach($_mainMenu as $_dir => $_name){?>
					<li<?=(($_sel==$_dir && !$this->_s404)?" class=\"sel\"":"")?>><a href="<?=$this->_path.$_dir?>"><span><?=$_name?></span></a></li>
					<?php
}?>
				</ul>
			</nav>
		</header>
<?php }?>
		<div id="container">
			<section id="main">
<?php if(!$this->_s404){?>				<h1><?=$_mainMenu[$_sel]?></h1><?php }?>
