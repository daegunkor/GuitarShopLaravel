<?php
class Session {
    protected static $_session_flag = false; //세션 시작 여부 저장하는 정적 프로파티
    protected static $_generated_flag = false; //세션 ID가 생성되었는지 여부를 저장하는 정적 프로파티

    //constructor
    public function __construct() { //세션 객체를 생성하면 세션이 시작됨
        if(!self::$_session_flag) { //세션 flag가 false이면
            session_start(); //세션 시작 session_start() - 세션 데이터의 초기화
                                //http://php.net/manual/kr/function.session-start.php
            self::$_session_flag = true; //세션 flag를 true로 설정
        }
    }

    // *** set() ***
    // $_SESSION에 세션값 설정
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    // *** get() ***
    // $_SESSION에서 값을 획득
    public function get($key, $par = null) {
        if(isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return $par;
    }

    // *** generateSession() ***
    // 세션ID생성
    public function generateSession($del = true) {
        if(!self::$_generated_flag) { //세션 생성 여부 확인 flag가 false이면
             session_regenerate_id($del); //http://php.net/manual/kr/function.session-regenerate-id.php
                                            //현재 세션 id를 새로 생성해서 갱신 $del == true >>> 이전 ID를 삭제
            self::$_generated_flag = true;
        }
    }

    // *** setAuthenticateStaus() ***
    // 로그인 상태 등록
    public function setAuthenticateStaus($flag) {
        $this->set('_authenticated', (bool)$flag); //$_SESSION['_authenticated']=$flag 처리
        $this->generateSession();
    }

    // *** isAuthenticated() ***
    // 인증완료인지 판단
    public function isAuthenticated() {
        return $this->get('_authenticated', false); //$_SESSION['_authenticated']값이 존재하면
                                                        //$_SESSION['_authenticated']을 반환
                                                        //$_SESSION['_authenticated']--- 로그인 중 => true
                                                        //$_SESSION['_authenticated']--- 로그아웃 중 => false
    }

//------------------------------------------------------------------------------------------------------
//38

    // *** clear() ***
    // $_SESSION을 지움
    public function clear() {
        $_SESSION = array(); // $_SESSION의 초기화
    }

    //세션 파괴, session_destroy();
    //현재 세션 id를 얻거나 설정 session_id()
    //현재 세션 이름을 얻거나 설정 session_name()
    //http://php.net/manual/kr/function.session-reset.php
    //void session_reset()
    //모든 세션 변수 해제 : session_unset()
}
?>
