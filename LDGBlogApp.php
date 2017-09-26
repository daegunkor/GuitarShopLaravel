<?php
class LDGBlogApp extends AppBase {

  protected $_indexAction = array('blog', 'index');

  //DB접속 실행
  protected function doDbConnection() {
    $a = $this->_connectModel->connect('master', //접속이름
    array(
      'string'    => 'mysql:dbname=ldg_db;host=localhost;charset=utf8',  //DB이름 - weblog
      'user'      => 'root',                                            //DB사용자명
      'password'  => '1234'                                             //DB사용자의 패스워드
    ));

  }//doDbConnection - function

  //Root Directory 경로를 반환
  public function getRootDirectory() {
    return dirname(__FILE__); //BlogApp.php가 저장되어 있는 디렉토리 or 호출 디렉토리
    //http://php.net/menual/en/function.dirname.php
  }//getRootDirectory - function

  //Blog APP에서 사용되는 Controller, Action
  //Contorller  - action    - path정보                    - 내용
  //1)account   - index     - /account                    - 계정 정보의 톱페이지
  //2)account   - signin    - /account/:action            - 로그인
  //3)account   - signout   - /account/:action            - 로그아웃
  //4)account   - signup    - /account/:action            - 계정등록
  //5)account   - follow    - /follow                     - 계정등록(회원가입)
  //6)blog      - index     - /                           - 블로그의 톱페이지
  //7)blog      - post      - /status/post                - 글작성
  //8)blog      - user      - /user/:user_name            - 사용자 작성글 일람
  //9)blog      - specific  - /user/:user_name/status/:id - 작성글의 상세보기


  //Routiong 정의를 반환
  protected function getRouteDefinition() {
    return array(

      //AccountController클래스 관련 Routing
      '/account'          => array('controller' => 'account', 'action' => 'index'),
      '/account/:action'  => array('controller' => 'account'),

      //BlogController 클래스 관련 Routing
      '/'                           => array('controller' => 'blog', 'action' => 'index'),
      '/user/:user_name'            => array('controller' => 'blog', 'action' => 'user'),
      '/user/:user_name/status/:id' => array('controller' => 'blog', 'action' => 'specific'),

      //introController 클래스 관련 Routing
      '/intro'            => array('controller' => 'intro', 'action' => 'index'),

      //BoardController 클래스 관련 Routing
      '/board'            => array('controller' => 'board', 'action' => 'showBoard'),
      '/board/:action'    => array('controller' => 'board'),
      //ShoppingController 클래스 관련 Routing
      '/shopping'         => array('controller' => 'shopping', 'action' => 'shopContent'),
      '/shopping/:action' => array('controller' => 'shopping'),
      //MyPageController 클래스 관련 Routing
      '/myPage'           => array('controller' => 'myPage', 'action' => 'myPageContent'),
    );

  }//getRouteDefinition - function
  //var_dump(getRouteDefinition()); 디버깅 코드

}//BlogApp -class

 ?>
