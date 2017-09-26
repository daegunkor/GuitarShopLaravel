<?php
class AccountController extends Controller{
  protected $_authentication = array('index','signout'); //login에 필요한 action정의
  const SIGNUP = 'account/signup';
  const SIGNIN = 'account/signin';

  // 아이디 중복 체크
  public function idDupliCheckAction(){
    $input_id = $this->_request->getGet('id');
    if(strlen($input_id) < 4){
      $checkLength = true;
    } else {
      $checkLength = false;
    }
    $userCount = $this->_connect_model->get('User')->getUserCount($input_id);
    if($userCount > 0)
      $checkDupli = true; // 중복 아이디 존재
    else
      $checkDupli = false; // 중복 아이디 부재

      if($checkLength){
        $message = "아이디는 4자이상 입니다.";
        $this->_session->set('input_id', '');
      } else if($checkDupli){
        $message = "중복된 아이디 입니다.";
        $this->_session->set('input_id', '');
      } else {
        $message = "사용하셔도 좋습니다.";
        $this->_session->set('input_id', $input_id);
      }

    $idDupliCheck_view = $this->render(array(
      'message' => $message,
      'template' => ''
    ));
    return $idDupliCheck_view;
  }
  // 닉네임 중복 체크
  public function nickDupliCheckAction(){
    $input_nick = $this->_request->getGet('nick');
    if(strlen($input_nick) < 3){
      $checkLength = true;
    } else {
      $checkLength = false;
    }
    $nickCount = $this->_connect_model->get('User')->getNickCount($input_nick);
    if($nickCount > 0)
      $checkDupli = true; // 중복 아이디 존재
    else
      $checkDupli = false; // 중복 아이디 부재

      if($checkLength){
        $message = "닉네임은 3자이상 입니다.";
        $this->_session->set('input_nick', '');
      } else if($checkDupli){
        $message = "중복된 닉네임 입니다.";
        $this->_session->set('input_nick', '');
      } else {
        $message = "사용하셔도 좋습니다.";
        $this->_session->set('input_nick', $input_nick);
      }

    $nickDupliCheck_view = $this->render(array(
      'message' => $message,
      'template' => ''
    ));
    return $nickDupliCheck_view;
  }
  public function signupAction(){
    if($this->_session->isAuthenticated()){
      $this->redirect('/');
    }
    $this->_session->set('input_id','');
    $this->_session->set('input_nick','');

    $signup_view = $this->render(array(
      'idInput'     => '',
      'passInput'   => '',
      'passConfirm' => '',
      'nameInput'   => '',
      'nickInput'   => '',
      'hpInput1'    => '',
      'hpInput2'    => '',
      'hpInput3'    => '',
      'emailInput1' => '',
      'emailInput2' => '',
      '_token' => $this->getToken(self::SIGNUP),
      //Controller클래스의 CSRF(Cross-site request forgery,사이트간 요청위조) 대책용 Token을생성
      //http://namu.wiki/w/CSRF
    ));
    return $signup_view;
  }
  public function registerAction(){//signup.php내의 form태그 action에서의 설정
    //1>POST 전송박식으로 전달 받은 데이터에 대한 체크
    if(!$this->_request->ispost()){
      $this->httpNotFound(); //FileNotFoundException 예외객체를 생성
    }
    if($this->_session->isAuthenticated()){
      $this->redirect('/');
    }
    //2>CSRF대책의 Token 체크
    $token = $this->_request->getpost('_token');
    if(!$this->checkToken(self::SIGNUP, $token)){
      return $this->redirect('/'.self::SIGNUP);
    }
    //3>POST 전송방식으로 전달 받은 데이터를 변수에 저장
    $idInput      = $this->_request->getPost('idInput');
    $passInput    = $this->_request->getPost('passInput');
    $passConfirm  = $this->_request->getPost('passConfirm');
    $nameInput    = $this->_request->getPost('nameInput');
    $nickInput    = $this->_request->getPost('nickInput');
    $hpInput1     = $this->_request->getPost('hpInput1');
    $hpInput2     = $this->_request->getPost('hpInput2');
    $hpInput3     = $this->_request->getPost('hpInput3');
    $hpTotal      = $hpInput1.'-'.$hpInput2.'-'.$hpInput3;
    $emailInput1  = $this->_request->getPost('emailInput1');
    $emailInput2  = $this->_request->getPost('emailInput2');
    $emailTotal   = $emailInput1.'@'.$emailInput2;



    //아이디, 닉네임 중복검사 실시 확인
    $errors = array();
    if($this->_session->get('input_id') != $idInput){
      $errors[] = '아이디 중복확인 미실시';
    }

    if($this->_session->get('input_nick') != $nickInput){
      $errors[] = '닉네임 중복확인 미실시';
    }

    //http://php.net/manual/kr/function.strlen.php
    //http://php.net/manual/kr/function.preg-match.php
    // 계정 정보 등록
    if(count($errors)===0){ //에러가 없는 경우 처리
      //UserModel클래스의  insert()로 사용자 계정 등록
      $this->_connect_model->get('User')->insert($idInput, $passInput, $nameInput, $nickInput, $hpTotal, $emailTotal);
      //세션ID재생성
      $this->_session->setAuthenticateStaus(true);
      //새로 추가된 레코드를 얻어냄
      $user = $this->_connect_model->get('User')->getUserRecord($idInput);
      //얻어온 레코드를 세션에 저장
      $this->_session->set('userid', $user['id']);
      $this->_session->set('userNick', $user['nick']);
      //사용자 톱 페이지로 리다이렉트
      return $this->redirect('/');
    }
    //에러가 있는 경우 에러 정보와 함께 페이지 렌더링
    return $this->render(array(
      'idInput'     => $idInput,
      'passInput'   => $passInput,
      'passConfirm' => $passConfirm,
      'nameInput'   => $nameInput,
      'nickInput'   => $nickInput,
      'hpInput1'    => $hpInput1,
      'hpInput2'    => $hpInput2,
      'hpInput3'    => $hpInput3,
      'emailInput1' => $emailInput1,
      'emailInput2' => $emailInput2,
      'errors'      => $errors,
      '_token'      => $this->getToken(self::SIGNUP),
    ),'signup');
  }
  public function indexAction(){ // /views/account/index.php
    $user = $this->_session->get('user');
    $followingUsers = $this->_connect_model->get('user')->getFollowingUser($user['id']);

    $index_view = $this->render(array(
      'user' => $user,
      'followingUsers' => $followingUsers,
    ));
    return $index_view;
  }
  public function signinAction(){ // /views/account/signin.php
    if($this->_session->isAuthenticated()){
      return $this->redirect('/account');
    }
    $signin_view = $this->render(array(
      'user_name' => '',
      'password' => '',
      '_token' => $this->getToken(self::SIGNIN),
    ));
    return $signin_view;
    //session ID를 재생성 -> $_SESSION['_authenticated']=true -> $_SESSION에 계정 정보 저장

  }
  public function authenticateAction(){
    if(!$this->_request->isPost()){
      $this->httpNotFound();
    }
    if($this->_session->isAuthenticated()){
      return $this->redirect('/');
    }
    $user_name = $this->_request->getPost('userid');
    $password = $this->_request->getPost('userpass');

    if(count($errors)===0){
      $user = $this->_connect_model->get('User')->getUserRecord($user_name);
      //http://php.net/manual/en/function.password-hash.php
      //http://php.net/manual/en/function.password-verify.php
      if(!$user || $password != $user['password']){
      }else{
        $this->_session->setAuthenticateStaus(true);
        $this->_session->set('userid', $user['id']);
        $this->_session->set('userNick', $user['nick']);
        return $this->redirect('/');
      }
    }
    return $this->redirect('/');
}
    public function signoutAction(){
      $this->_session->clear();
      $this->_session->setAuthenticateStaus(false);
      return $this->redirect('/');
    }

    public function followAction(){
      if(!$this->_request->isPost()){
        $this->httpNotFound();
      }
      $follow_user_name = $this ->_request->getPost('follow_user_name');
      if(!$follow_user_name){
        $this->httpNotFound();
      }
      $token = $this ->_request->getPost('_token');

      if(!$this->checkToken(self::FOLLOW,$token)){
        return $this->redirect('/user/'.$follow_user_name);
      }
      $follow_user = $this->_connect_model->get('User')->getUserRecord($follow_user_name);
      if(!$follow_user){
        $this->httpNotFound();
      }
      $user = $this->_session->get('user');

      $followTblConnection = $this->_connect_model->get('Following');

      if($user['id'] !== $follow_user['id'] && !$followTblConnection->isFollowedUser($user['id'],
      $follow_user['id'])){
        $followTblConnection->registerFollowUser($user['id'],$follow_user['id']);
      }
      return $this->redirect('/account');
    }


}
 ?>
