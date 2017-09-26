<?php
  abstract class Controller { //추상 클래스 이므로 사용하려면 계승해야함
    protected $_application; //App본체 클래스의 인스턴스저장
    protected $_controller; //컨트롤러의 클래스 이름을 저장
    protected $_action; //액션명을 저장
    protected $_request; //Request 클래스의 인스턴스 저장
    protected $_response; //Response클래스의 인스턴스 저장
    protected $_session; //Session 클래스의 인스턴스 저장
    protected $_connect_model; // connectionModel 클래스(DB연결)의 인스턴스 저장
    protected $_authentication=array(); //보안인증이 필요한 페이지인지 (true/false)를 저장함
    const PROTOCOL = 'http://';
    const ACTION = 'Action';

    //***생성자***
    public function __construct($application){
      $this->_controller = strtolower(substr(get_class($this),0,-10));
      //ㅇㅇcontroller의 oo만 잘라내어 소문자로 변환하여 저장
      $this->_application = $application;
      $this->_request = $application->getRequestObject();
      $this->_response = $application -> getResponseObject();
      $this->_session = $application -> getSessionObject();
      $this->_connect_model = $application -> getConnectModelObject();
    }

    //***dispatch()***
    public function dispatch($action,$params = array()){
      //$action : 액션명
      //$params : Routing정보
      $this->_action = $action; //액션명을 현재 컨트롤러의 프로퍼티 $_action에 저장
      $action_method=$action.self::ACTION; //액션 메소드 저장:액션명 + action

      if(!method_exists($this, $action_method)){ //액션메소드가 존재하지 않으면 에러화면
        //http://php.net/manual/kr/function.metod-exists.php
        $this->httpNotFound();

      }
      if($this->isAuthentication($action) && !$this->_session->isAuthenticated()){
        //요청된 액션에 대해 인증이 필요한지 여부와 인증이 완료되었는지 여부체크
        throw new AuthorizedException(); //AuthorizedException 예외 발생
      }
      $content = $this->$action_method($params); //액션 메소드를 실행하여 컨텐츠를 App클래스의 getcontent로 반환
      return $content;

    }
    //***httpNotFound()***
    protected function httpNotFound(){
      throw new FileNotFoundException('FILE NOT FOUND'
      .$this->_controller.'/'.$this->_action);
    }
    //***isAuthentication()***
    protected function isAuthentication($action){
      if($this->_authentication === true || (is_array($this->_authentication) &&
      in_array($action,$this->_authentication))
    ){
    //http://php.net/manual/kr/function.is-array.php
    //http://php.net/manual/kr/function.in-array.php
    //$this -> _authentiation 이 배열이고 $this->_authentication 안에 $action이 존해가는지
      return true;
    }
    return false;
    }
    //***render()***
    //컨트롤러 서브 클래스의 액션메소드 (OOAction())에서 호출
    protected function render(
      $param = array(),$viewFile = null, $template = null
    ){//$param : 템플릿에 전달하는 변수 (연상배열)
      //$viewFile : 뷰파일명(null이면 액션명으로 대체)
      //$template : 레이아웃 파일명
      $info = array(
        'request' => $this ->_request, //Request인스탄스
        'base_url' => $this ->_request->getBaseUrl(), //Base URL정보
        'session' => $this->_session, //Session정보
      );
      $view = new View($this->_application // view 클래스 인스탄스화
                      ->getViewDirectory(), //AppBase 클래스의 메소드
                            //뷰파일이 저장되어있는 폴더의 경로 반환
                            $info);
      if(is_null($viewFile)){
        $viewFile = $this->_action;
      }
      if(is_null($template)){
        $template = 'template';
      }
      $path = $this ->_controller.'/'.$viewFile; //뷰파일의 경로 정보
      $contents = $view -> render($path,
                                  $param,
                                  $template); //view 클래스의 render()
      return $contents;

    }

    //***redirect()***
    protected function redirect($url){
      $host = $this->_request->getHostName();
      $base_url = $this->_request->getBaseUrl();
      $url = self::PROTOCOL.$host.$base_url.$url;
      $this->_response
            ->setStatusCode(302,'FOUND'); //상태코드 302 리다이렉트
      $this ->_response
              ->setHeader('Location',$url);
    }
    //***getToken(): CSRF를 위한 one time Password 생성 ***
    //CSRF : Cross-site Request Forgery, XSS를 이용한 요청 위조
    //Action method 에서 render()호추시 파라메타 전달을 위해 실행
    protected function getToken($form){

      $key = 'token/'.$form; //$_SESSION변수에서 token을 읽어옴
      $token = $this->_session->get($key,array()); // $_SESSION변수에 Token 저장하기
      if(isset($tokens) && count($tokens)>=10){ //토큰의 수가 10개를 넘지 않도록 조절
        array_shift($tokens);
        //http://php.net/manual/kr/function.array-shift.php
      }
      $password =session_id().$form;
      $token = password_hash($password, PASSWORD_DEFAULT);
      //http://php.net/manual/kr/function.password-hash.php
      //password_hash()으로 생성한 패스워드 hash정보를 $tokens에 저장
      $tokens[]=$token;

      $this->_session->set($key,$tokens);

      return $token;
    }

    //***checkToken()***
    //세션이 유효한지 체크,폼 입력데이터가 전송될때 사용하는 action에서 호출
    //토큰의 키를 작성, 작성한 키에 해당하느 토클을 $_session으로 부터 획득
    //획득한 토큰들로부터 $token이 존재하는지 검색
    //검색하여 있으면 일단 토큰을 삭제
    //다시 토큰을 재설정
    protected function checkToken($form,$token){
      $key = 'token/'.$form;
      $tokens = $this->_session->get($key,array());
      //http://php.net/manual/kr/function.array_search.php
      if(false !== ($present = array_search($token,$tokens,true))
    ){
      unset($tokens[$present]);
      $this->_session->set($key,$token);
      return true;
    }
    return false;
    }
  }
 ?>
