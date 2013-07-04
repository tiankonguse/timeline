<?php session_start(); ?>
<!DOCTYPE HTML>
<html lang="zh-cn">
	<head>
		<?php
		$title = "tiankonguse 的时间轴";
		include_once('inc/header.inc.php');
		?>
	</head>
	
	<body>
		<header id="header">
			<?php include_once('inc/index.top.php'); ?>
		</header>
		
		<section id="section">
			<?php include_once("inc/index.body.php") ?>	
		</section>
		
		<footer id="footer" style="position:fixed;bottom: 0px; height: 22px; width: 100%;margin:87px 10px 0px 10px;">
			<?php  include_once('inc/index.footer.php'); ?>
		</footer>
		
		<script src="js/jquery.js"></script>
		<script src="js/underscore-min.js"></script>
		
		<?php include_once("inc/index.backTop.php") ?>	
	</body>
</html>