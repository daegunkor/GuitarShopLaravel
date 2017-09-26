<?php
//추상클래스
	abstract class ExecuteModel{
		//http://php.net/manual/kr/book.pdo.php
		//PDO 객체를 저장해 두는 프로파티
		protected $_pdo;

		// ***Constructor***
		public function __construct($pdo){
			//ConnectionModel 클래스 get()내 $obj = new $mdl_class($cnt); 코드에서 실행
			$this->setPdo($pdo);
		}

		// ***setPdo()***
		public function setPdo($pdo){
			$this->_pdo = $pdo;
		}

		// ***execute()***
		public function execute($sql,$parameter = array()){
			//Prepared Statement를 생성
			$stmt = $this->_pdo->prepare(
					$sql,
					array(PDO::ATTR_CURSOR=>PDO::CURSOR_SCROLL));
			//Prepared Statement의 실행
			$stmt->execute($parameter);
			//실행 결과를 PDOStatement 객체로 반환
			return $stmt;
		}

		// ***getAllRecord()***
		public function getAllRecord($sql,$parameter = array()){
			$all_rec = $this->execute($sql,$parameter)->fetchAll(PDO::FETCH_ASSOC);
			return $all_rec;
		}

		// ***getRecord()***
		public function getRecord($sql,$parameter = array()){
			$rec = $this->execute($sql,$parameter)->fetch(PDO::FETCH_ASSOC);
			return $rec;
		}
	}
 ?>
