<?php
class ConnectModel{
  //PDO인스턴스를 배열로 저장해두는 프로퍼티
  protected $_dbConnections = array();
  //접속명을 저장하는 프로퍼티
  protected $_connectName;
  //실행 클래스(ExecuteModel의 서브 클래스)의 인스탄스를 저장하는 프로퍼티
  protected $_modelList = array();
  //실행 클래스(ExecuteModel의 서브 클래스)명을 지정하기 위한 상수
  const MODEL = 'Model';

  //***connect()***
  public function connect($name,$connection_strings){
    //$name : 접속을 설정하기 위한 접속명 파라미터, $_dbConnections의 key사용
    //$connection_strings:
    try{
      $cnt = new PDO( //PDO객체 생성
        $connection_strings['string'], //접속 문자열
        $connection_strings['user'], //DB사용자명
        $connection_strings['password'] //DB패스워드
      );
    }catch(PDOException $e){
      exit("데이터 베이스 연결을 실패했습니다.:{$e->getMessage()}");
    }
    //ATTR_ERRMODE-예외를 던질수 있도록 설정:ERRMODE_EXCEPTION-예외 레포트에 관한 설정
    $cnt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->_dbConnections[$name]=$cnt;
    $this->_connectName=$name;
  }


  //***getConnection()***
  public function getConnection($name =null){
    //접속명이 null인 경우 처리
    //http://php.net/manual/kr/function.current.php
    if(is_null($name)){
      //_dbConnections프로퍼티의 제일 첫요소를 반환
      return current($this->_dbConnections);
    }
    //_dbConnections 프로퍼티에 저장되어있는 key가 $name으로 되어있는 PDO객체를 반환
    return $this->_dbConnections[$name];
  }
  //***getModelconnection()***
  public function getModelconnection(){
    if(isset($this->_connectName)){ //_connectName값이 NULL이 아니면 TRUE
      //$this->_connectName 값을 $name에 저장
      $name = $this->_connectName;
      //접속명 $name과 관련되어있는 PDO객체 획득
      $cnt = $this->getConnection($name);
    }else{
      //_connectName값이 NULL이면 제일 앞쪽 PDO객체 획득
      $cnt = $this->getConnection();
    }
    //PDO객체를 반환
    return $cnt;
  }
  //***get()***
  public function get($model_name){
    //_modelList프로퍼티에 $model_name키로
    //데이터 모델명이 존재하지 않으면 PDO객체를 획득
    if(!isset($this->_modelList[$model_name])){
      //데이터 모델명에 'Model'를 연결하고 이것을 클래스명으로 대입
      $mdl_class = $model_name.self::MODEL;
      //PDO객체를 획득
      $cnt = $this->getModelconnection();
      //$mdl_class에 저장된 값으로 데이터 모델 클래스의 인스터스화
      $obj = new $mdl_class($cnt);
      //_modelList프로퍼티에 (데이터모델명 => 데이터 모델 클래스의 인스턴스)저장
      $this->_modelList[$model_name]=$obj;
    }
    //반환 값으로 데이터 모델 클래스의 인스탄스를 반환
    $modelObj = $this->_modelList[$model_name];
    return $modelObj;
  }
  //**__destruct()***
  public function __destruct(){
    foreach ($this->_modelList as $model) {
      unset($model); //$model을 파기
      # code...
    }
    foreach ($this->_dbConnections as $cnt) {
      # code...
      unset($cnt); //$cnt를 파기
    }
  }
}
 ?>
