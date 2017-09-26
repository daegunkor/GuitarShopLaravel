<?php

	class View{
		protected $_bashUrl; //view 파일의 폴더 경로 정보
		protected $_initialValue; // 컨트롤러 render()에서의 전달정보 (리퀘스트,리퀘스트의 Base Url, Session)
		protected $_passValues = array(); // View에 전달할 정보

		public function __construct($bashUrl,$initialValue=array()){
			$this->_bashUrl = $bashUrl;
			$this->_initialValue = $initialValue;
		}

		//레이아웃 페이지에 제목으로 보낼 데이터 설정
		public function setPageTitle($name,$value){
			$this->_passValues[$name] = $value;
		}

		//뷰파일을 읽어 들이는 메서드
		//뷰파일명,뷰파일에전달하는정보(액션메서드로부터 전달받은),레이아웃 페이지
		public function render($filename,$parameters=array(),$template=false){
			$view = $this->_bashUrl.'/'.$filename.'.php';
			extract(array_merge($this->_initialValue,$parameters));//뷰파일 디렉토리 경로+리퀘스트객체+세션객체,액션메서드로부터 받은 배열 정보
						//extract : 연상배열의 키 - 값을 변수- 값으로 선언해줌

			//http://php.net/manual/kr/function.array-merge.php
			//array_merge($arr1,$arr2),$arr1과 $arr2를 병합하여 배열반환(같은 키는 $arr2 우선)
			//http://php.net/manual/kr/function.ob-start.php
			//출력버퍼링을 유효화(사용가능하게함)
			//ob_implict_flush()
			//자동출력의 여부를 결정(기본값은 TRUE or 1 - on, FALSE or 0 - off)
			//ob_get_clean()
			//현재 버퍼 내용을 반환하고 출력 버퍼를 삭제
			ob_start();
			ob_implicit_flush(0);
			require $view;
			$content = ob_get_clean();

			if($template){ //레이아웃 파일명이 존재하면 실행
										//레이아웃 파일명, $parameters['title'],parameters[')content']되도록 배열머지
				$content = $this->render($template,array_merge($this->_passValues, array('_content'=>$content)
					));
			}
			return $content;
		}

		//HTML Escape를 수행
		//스크립트를 이용한 해킹 방지
		public function escape($string){
			//특수문자를 HTML 엔티티로 변경
			//ENT_QUOTES 설정되면 ':&#039;값'
			return htmlspecialchars($string,ENT_QUOTES,'UTF-8');
		}
	}
 ?>
