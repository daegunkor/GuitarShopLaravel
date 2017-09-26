<?php
	class BoardModel extends ExecuteModel {
    function getTotalPageCnt($maxQCnt){
      $sql = "SELECT count(*) as count FROM qBoardQuestion ORDER BY num DESC";
      $result = $this->getRecord($sql);
      $rowCount = $result['count'];

      // 페이지의 수 계산
      if($rowCount % $maxQCnt == 0)
        $totalPageCnt = floor($rowCount / $maxQCnt);
      else
        $totalPageCnt = floor($rowCount / $maxQCnt) + 1;
      return $totalPageCnt;
    }

    // 게시판 데이터 출력
    function getBoardData(){
      $sql = "SELECT num,title,regist_day,hit,nick,norFile ";
      $sql.= "FROM qBoardQuestion ORDER BY num DESC";
      $data = $this->getAllRecord($sql);
      return $data;
    }

    // 게시글의 조회수 증가
    function hitUpper($qNum){
      // 글의 조회수 검색
      $sql = "SELECT hit FROM qBoardQuestion WHERE num = :num";
      $data = $this->getRecord($sql,[":num" => $qNum]);
      $hit = $data['hit'];
      $hit++;

      $sql = "UPDATE qBoardQuestion SET hit = :hit WHERE num = :num";
      $this->execute($sql,[
        ":hit" => $hit,
        ":num" => $qNum
      ]);
    }

    // 게시글의 데이터 출력
    function getBoardContent($qNum){
      $sql = "SELECT num,title,content,regist_day,hit,id,nick,norFile ";
      $sql.= "FROM qBoardQuestion WHERE num = :num";
      $data = $this->getRecord($sql,[":num" => $qNum]);
      return $data;
    }

    function getAnswerContent($ansNum){
      $sql = "SELECT parent,title,content,regist_day,id,nick ";
      $sql.= "FROM qBoardAnswer WHERE parent = :parent";
      $data = $this->getAllRecord($sql,[":parent" => $ansNum]);
      return $data;
    }

		// 다음에 써질 질문글의 번호를 가져온다
    function getHighCnt(){
      $sql = "SELECT num FROM qBoardQuestion ORDER BY num DESC";
      $result = $this->getRecord($sql);
      $num = $result['num'];
      return $num;
    }
		// 게시판 작성
		function writeQuestionBoard($uploadArr){
      $sql = "INSERT INTO qBoardQuestion(num,title,content,regist_day,hit,id,nick,norFile)";
      $sql.= "VALUES (:num,:title,:content,:regist_day,:hit,:id,:nick,:norFile)";

      $this->execute($sql,[
        ":num"          => $uploadArr['num'],
        ":title"        => $uploadArr['title'],
        ":content"      => $uploadArr['content'],
        ":regist_day"   => $uploadArr['registDay'],
        ":hit"          => 0,
        ":id"           => $uploadArr['id'],
        ":nick"         => $uploadArr['nick'],
        ":norFile"      => $uploadArr['norFile']
      ]);
    }

		// 게시글 번호로 유저의 아이디를 찾는다
		function getUserIdByQNum($qNum){
      $sql = "SELECT id FROM qBoardQuestion WHERE num = :num";
      $result = $this->getRecord($sql,[":num"=> $qNum]);
      return $result['id'];
    }

		// 글번호로 질문글의 정보를 가져온다
    function getInfoByQNum($qNum){
      $sql = "SELECT * FROM qBoardQuestion WHERE num = :num";
      $row = $this->getRecord($sql,["num" => $qNum]);
      return $row;
    }

		// 질문 수정
    function modifyQuestion($qNum,$data){
      $sql = "UPDATE qBoardQuestion SET ";
      $sql.= "title = :title, ";
      $sql.= "content = :content, ";
      $sql.= "norFile = :norFile, ";
      $sql.= "regist_day = :regist_day ";
      $sql.= "WHERE num = :num";

      $this->execute($sql,[
        ":title"      => $data['title'],
        ":content"    => $data['content'],
        ":regist_day" => $data['date'],
        ":norFile"    => $data['norFile'],
        ":num"        => $qNum
      ]);
    }

		// 질문 삭제
		function deleteQuestion($qNum){
			$sql = "DELETE FROM qBoardQuestion WHERE num = :num";
			$this->execute($sql,[":num" => $qNum]);
		}

		// 해당 부모글 번호의 댓글을 모두 지움
		function deleteAnswer($pNum){
			$sql = "DELETE FROM qBoardAnswer WHERE parent = :parent";
			$this->execute($sql,[":parent" => $pNum]);
		}

		// 답글 업로드
		function writeAnswer($data){
			$sql = "INSERT INTO qBoardAnswer(parent,title,content,regist_day,id,nick)";
			$sql.= "VALUES (:parent,:title,:content,:regist_day,:id,:nick)";
			$this->execute($sql,[
				":parent"          => $data['parent'],
				":title"           => $data['title'],
				":content"         => $data['content'],
				":regist_day"      => $data['date'],
				":id"              => $data['id'],
				":nick"            => $data['nick']
			]);
		}

		// 해당 아이디, 날짜에 해당하는 댓글을 삭제
    function cancelAnswer($id, $date){
      $sql = "DELETE FROM qBoardAnswer WHERE id=:id AND regist_day=:regist_day";
      $this->execute($sql,[
        ":id" => $id,
        ":regist_day" => $date
      ]);
    }
	}
?>
