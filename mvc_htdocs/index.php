<?php
	require '../bootstrap.php';
	require '../LDGBlogApp.php';

	$app = new LDGBlogApp(false);// error 출력 여부(true-표시,false-미표시)
	$app->run();


?>
