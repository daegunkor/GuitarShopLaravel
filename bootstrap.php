<?php
  require 'mvc/Loader.php';

  $loader = new Loader();
  $loader->regDirectory(dirname(__FILE__).'/mvc');
  $loader->regDirectory(dirname(__FILE__).'/models');
  //__FILE__ : 현재 파일의 directory를 저장하고 있는정수
  //dirname() : 지정한 파일 경로의 부모 directory의 경로를 반환
  //http://php.net/manual/kr/fnction.dirname.php
  $loader->register();
 ?>
