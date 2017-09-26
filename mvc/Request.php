<?php

class Request{
  //***isPost(): 요청 방식이 POST인지 조사 ***
  public function isPost(){
    if($_SERVER['REQUEST_METHOD']==='POST'){
      return true;
    }
    return false;
  }

  //***getGet(): GET방식으로 요청된 데이터를 획득 ***
  public function getGet($name, $param = null){
    if(isset($_GET[$name])){
      return $_GET[$name];
    }
    return $param;
  }

  //***getPost(): POST방식으로 요청된 데이터를 획득 ***
  public function getPost($name, $param = null){
    if(isset($_POST[$name])){
      return $_POST[$name];
    }
    return $param;
  }

  //***getFile(): File방식으로 요청된 데이터를 획득 ***
  public function getFile($name, $param = null){
    if(isset($_FILES[$name])){
      return $_FILES[$name];
    }
    return $param;
  }


  //***getHostName(): 호스트이름을 획득 ***
  public function getHostName(){
    if(!empty($_SERVER['HTTP_HOST'])){ // Request Header에 호스트명이 있다면
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME']; // 서버측에 설정되어 있는 호스트명
  }

  //***getRequestUri(): URL에서 HOST명 뒷부분을 획득 ***
  public function getRequestUri(){
    return $_SERVER['REQUEST_URI'];
  }

  //***getBaseUrl(): URL에서 Front Controller까지의 경로를 반환 ***
  // 1) Front Controller가 포함된 경우
  // 2) Front Controller가 생략된 경우
  public function getBaseUrl(){
    $scriptName = $_SERVER['SCRIPT_NAME']; //스크립트의 경로
    $requestUri = $this->getRequestUri(); //리퀘스트된 URL의 호스트부분에서 뒷부분
    // http://php.net/manual/kr/function.strpos.php
    if(0 === strpos($requestUri, $scriptName)){ // $requestUri의 선두에 $scriptName가 있다면 0
      return $scriptName;
    }else if(0 === strpos($requestUri, dirname($scriptName))){  // $requestUri의 선두에 $scriptName의 부모폴더가 있다면 0
      // http://php.net/manual/kr/function.dirname.php
      return rtrim(dirname($scriptName),'/');   // $scriptName의 우측끝의 '/'를 삭제
      // http://php.net/manual/kr/function.rtrim.php
    }
    return '';
  }
  // ***getPath(): Front Controller 뒤에 계속되는 경로를 반환 ***
  public function getPath() {

    //Base URL 획득 : 리퀘스트된 URL에서 호스트부분에서 Front Controller까지의 경로
    $base_url = $this->getBaseUrl();

    //리퀘스트된 URL에서 호스트 부분의 뒷부분 모두 다 획득
    $requestUri = $this->getRequestUri();

    if(false !== ($sp = strpos($requestUri, '?'))) {
      //http://php.net/manual/kr/function.substr.php
      $requestUri = substr($requestUri, 0, $sp);
        // substr(문자열, 시작, 길이);
    }
    // http://php.net/manual/kr/function.strlen.php
    $path = (string)substr($requestUri, strlen($base_url));
    return $path;
  }
}
