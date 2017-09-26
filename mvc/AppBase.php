<?php
abstract class AppBase{//계승을 전제로 하는 클래스로 abstract클래스로 정의
  //Request 클래스의 인스턴스를 저정하는 프로파티
  protected $_request;
  //Response 클래스의 인스턴스를 저정하는 프로파티
  protected $_response;
  //Session 클래스의 인스턴스를 저장하는 프로파티
  protected $_session;
  //ConnectModel 클래스의 인스턴스를 저장하는 프로파티
  protected $_connectModel;
  //Router 클래스의 인스턴스를 저장하는 프로파티
  protected $_router;
  //로그인 하였을 때의 컨트롤러와 액션의 조합을 저장하는 배열 프로파티
  protected $_signinAction = array();
  //에러 표시 온/오프를 저장하기 위한 프로파티 : boolean
  protected $_displayErrors;
  //Controller명의 접미사
  const CONTROLLER = 'Controller';
  //views 폴더
  const VIEWDIR = '/views';
  //model 폴더
  const MODELSDIR = '/models';
  //controller 폴더
  const CONTROLLERDIR = '/controllers';
  //도큐먼트 루프의 폴더
  const WEBDIR = '/mvc_htdocs';

  //생성자 __construct()
  public function __construct($dspErr){
    $this->setDisplayErrors($dspErr);
    $this->initialize();
    $this->doDbConnection();
  }

  //initialize()
  protected function initialize(){
    $this->_router = new Router($this->getRouteDefinition());
    $this->_connectModel = new ConnectModel();
    $this->_request = new Request();
    $this->_response = new Response();
    $this->_session = new Session();
  }

  // setDisplayErrors()
  //에러표시모드를 설정, true - 에러표시 , false- 비표시
  protected function setDisplayErrors($dspErr){
    if($dspErr){
      $this->_displayErrors = true;
      ini_set('display_errors',1);
      ini_set('error_reporting',E_ALL);
    }else{
      $this->_displayErrors=false;
      ini_set('display_errors', 0);
    }
    //http://php.net/manual/kr/function.ini-set.php
    //http://php.net/manual/kr/function.ini-get.php
    //http://php.net/manual/kr/function.error-reporting.php
    //php.ini의 설정을 php소스코드에서 제어
  }

  //***isDisplayErrors()***
  //에러표시모드가 표시모드(true)인지 비표시모드(false)인지를 반환
  public function isDisplayErrors(){
    return $this->_displayErrors;
  }

  //***run()***
  //리퀘스트된 url을 이용한 Routing하여 Controller+Action를 획득
  //Controller+Action을 Dispatch(일의 순서를 정해 실행시킴,getContent)
  //내부에서 수행)gkdu ActionMethod를 실행
  //Response객체에 저장되는 Rendeing되는 Data를 응답으로 Client로 전송
  public function run(){
    try {
      $parameters = $this->_router->getRouteParams($this->_request->getPath());

      if($parameters === false){
        throw new FileNotFoundException('NO ROUTE'.$this->_request->getPath());
      }

      $controller = $parameters['controller'];
      $action = $parameters['action'];
      $this->getContent($controller,$action,$parameters);
    } catch (FileNotFoundException $e) {
        $this->disErrorPage($e);
    } catch(AuthorizedException $e){
      echo ("
        <script>
          alert('로그인 후에 이용하세요.');
        </script>
      ");
        list($controller,$action) = $this->_indexAction;
        $this->getContent($controller,$action);
    }
    $this->_response->send();
    //http://php.net/manual/kr/function.list.php
  }
  //***getContent()***
  //컨트롤러명을 구해내어 컨트롤러 클래스의 인스탄스를 생성
  //생성된 컨트롤러 클래스 인스탄스에 액션 실행을 의뢰
  //액션의 실행(엑션메소드)의 반환값(Contents-Response화면)을 Respone의 객체에 설정
  public function getContent($controllerName,$action,$parameters = array()){
    //http://php/net/manual/kr/function.ucfirst.php:첫글자 대문자로 변환
    $controllerClass = ucfirst($controllerName).self::CONTROLLER;
    $controller = $this->getControllerObject($controllerClass);

    if($controller === false){
      throw new FileNotFoundException($controllerClass.'NOT FOUND.');
    }

    $content = $controller->dispatch($action,$parameters);
    $this->_response->setContent($content);
  }
    // *** getControllerObject() ***
  // Controller클래스를 검색하여 인스탄스화 하여 이것을 반환값으로 반환
  // http://php.net/manual/kr/function.class-exists.php
  // http://php.net/manual/kr/function.is-readable.php
  protected function getControllerObject($controllerClass) {
    if(!class_exists($controllerClass)) {
       $controllerFile = $this->getControllerDirectory().'/'.$controllerClass.'.php';

      if(!is_readable($controllerFile)) {
        return false;
      } else {
        require_once $controllerFile;

        if(!class_exists($controllerClass)) {
          return false;
        }
      }
    }

    $controller = new $controllerClass($this);

    return $controller;
  }

  // *** dispErrorPage() ***
  // 404 Error페이지
  // http://php.net/manual/kr/function.htmlspecialchars.php : 특수문자를 HTML Entity로 변환
  protected function disErrorPage($e) {
    $this->_response->setStatusCode(404, 'FILE NOT FOUND.');
    $errMessage = $this->isDisplayErrors() ? $e->getMessage() : 'FILE NOT FOUND.';
    $errMessage = htmlspecialchars($errMessage, ENT_QUOTES, 'UTF-8');
    $html = "
      <!DOCTYPE html>
       <html>

        <head>
          <meta charset='utf-8' />
          <title> HTTP 404 Error </title>
        </head>
        <body>
          {$errMessage}
        </body>
      </html>
    ";
    $this->_response->setContent($html);
  }

  // *** getRouteDefinition() ***
  // 정의된 Routing정보를 반환
  abstract protected function getRouteDefinition();

  // *** doDbConnection() ***
  protected function doDbConnection() {}

  // *** getRequestObject() ***
  public function getRequestObject() {
    return $this->_request;
  }
  public function getResponseObject(){
    return $this->_response;
  }

  // ***getSessionObject() ***
  public function getSessionObject(){
    return $this->_session;
  }

  // ***getConnectModelObject() ***
  public function getConnectModelObject(){
    return $this->_connectModel;
  }

  // ***getViewDirectory() ***
  public function getViewDirectory(){
    return $this->getRootDirectory().self::VIEWDIR;
  }

  // ***getModelDirectory() ***
  public function getModelDirectory(){
    return $this->getRootDirectory().self::MODELSDIR;
  }

  // ***getDocDirectory() ***
  public function getDocDirectory(){
    return $this->getRootDirectory().self::WEBDIR;
  }

  // ***getRootDirectory()***
  abstract public function getRootDirectory();

  // ***getControllerDorectory() ***
  public function getControllerDirectory(){
    return $this->getRootDirectory().self::CONTROLLERDIR;
  }
}
?>
