<?php
class Response {
	protected 	$_content;					//컨텐츠를 저장하는 프로파티
	protected 	$_statusCode 	= 200;		//상태코드 저장 200대: 정상, 400대:서버-클라이언트, 500대: 서버
	protected 	$_statusMsg		= 'OK';		//상태메시지 저장
	protected 	$_headers 		= array();	//응답헤더의 필드를 저장
	const 		HTTP 			= 'HTTP/1.1';

	// status code, status message를 지정
	public function setStatusCode($code, $msg = '') {
		$this->_statusCode 	= $code; 	//상태코드를 저장시킴
		$this->_statusMsg 	= $msg;		//상태메시지를 저장시킴
	}

	// response header 설정
	public function setHeader($name, $value) {	//필드명(key) $name => 값 $value
		$this->_headers[$name] = $value;
	}

	// 컨텐츠의 설정
	public function setContent($content) {
		$this->_content = $content;
	}

	// http://php.net/manual/ke/function.header.php
	// response를 전송
	public function send() {
		// 상태코드와 메시지 전송: HTTP/1.1 200 OK
		header(self::HTTP.$this->_statusCode.''.$this->_statusMsg);

		foreach ($this->_headers as $name => $value) {
			header($name.':'.$value);	// $_header의 내용을 $name:$value 형태로 송신
		}

		print $this->_content; 	// 컨텐츠의 출력
	}
}


?>
