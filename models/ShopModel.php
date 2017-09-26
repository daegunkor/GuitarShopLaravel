<?php
	class ShopModel extends ExecuteModel {
    // 페이지 카운트 출력
    function getTotalPageCnt($maxQCnt){
      $sql = "SELECT count(*) as count FROM goods ORDER BY num DESC";
      $result = $this->getRecord($sql);
      $rowCount = $result['count'];


      // 페이지의 수 계산
      if($rowCount % $maxQCnt == 0)
        $totalPageCnt = floor($rowCount / $maxQCnt);
      else
        $totalPageCnt = floor($rowCount / $maxQCnt) + 1;
      return $totalPageCnt;
    }
    // 상품 리스트 정보
    function getGoodsList(){
      $sql = "SELECT num, title, price, mainImg ";
      $sql.= "FROM goods ORDER BY num DESC";
      $data = $this->getAllRecord($sql);
      return $data;
    }

		// 상품 정보
		function getGoodsContent($pNum){
			$sql = "SELECT num, title, name, content, price,quantity, regist_day, id, nick, mainImg ";
      $sql.= "FROM goods WHERE num = :num";
      $data = $this->getRecord($sql,[":num" => $pNum]);

			return $data;
		}

		// 상품 리뷰 정보
		function getReviewContent($pNum){
      $sql = "SELECT parent,content,regist_day,id,nick ";
      $sql.= "FROM goodsReview WHERE parent = :parent";
      $allData = $this->getAllRecord($sql,[":parent" => $pNum]);
			return $allData;
		}

		// 상품 구매 (갯수 0이하일시 구매실패)
		function buyGoods($id, $pNum){
			// 상품의 기존 수량
			$sql = "SELECT quantity FROM goods WHERE num = :num";
			$data = $this->getRecord($sql,[":num" => $pNum]);
			$lastQuantity = $data['quantity'];

			// 상품수량이 0일경우 false
			if($lastQuantity <= 0)
				return false;

			// 상품 수량 수정
			$sql = "UPDATE goods SET quantity = :quantity WHERE num = :num";
			$this->execute($sql,[
				":quantity" => ($lastQuantity-1),
				":num" => $pNum
			]);

			// 구매자의 구매항목 추가
			$sql = "INSERT INTO buyList(id, num) VALUES(:id,:num)";
			$this->execute($sql,[
				":id"    => $id,
				":num"   => $pNum
			]);
			// 정상 구매시 true
			return true;
		}

		// 장바구니 추가
		function insertWishList($id, $pNum){
			// 구매자의 구매항목 추가
			$sql = "INSERT INTO wishList(id, num) VALUES(:id,:num);";
			$this->execute($sql,[
				":id"    => $id,
				":num"   => $pNum
			]);
		}

		// 상품 리뷰 등록
		function writeReview($data){
      $sql = "INSERT INTO goodsReview(parent,content,regist_day,id,nick) ";
      $sql.= "VALUES (:parent,:content,:regist_day,:id,:nick)";


      $this->execute($sql,[
        ":parent"          => $data['parent'],
        ":content"         => $data['content'],
        ":regist_day"      => $data['date'],
        ":id"              => $data['id'],
        ":nick"            => $data['nick']
      ]);

    }

		// 상품 리뷰 삭제
		function deleteReview($id, $date){
			$sql = "DELETE FROM goodsReview WHERE id=:id AND regist_day=:regist_day";
			$this->execute($sql,[
				":id" => $id,
				":regist_day" => $date
			]);
		}

		// 다음에 써질 질문글의 번호를 가져온다
    function getHighCnt(){
      $sql = "SELECT num FROM goods ORDER BY num DESC";
      $result = $this->getRecord($sql);
			$num = $result['num'];
      return $num;
    }

		// 상품 등록
		function registGoods($uploadArr){
      $sql = "INSERT INTO goods(num,title,name,content,price,quantity,regist_day,id,nick,mainImg)";
      $sql.= "VALUES (:num,:title,:name,:content,:price,:quantity,:regist_day,:id,:nick,:mainImg)";

      $this->execute($sql,[
        ":num"          => $uploadArr['num'],
        ":title"        => $uploadArr['title'],
        ":name"         => $uploadArr['name'],
        ":content"      => $uploadArr['content'],
        ":price"        => $uploadArr['price'],
        ":quantity"     => $uploadArr['quantity'],
        ":regist_day"   => $uploadArr['regist_day'],
        ":id"           => $uploadArr['id'],
        ":nick"         => $uploadArr['nick'],
        ":mainImg"      => $uploadArr['mainImg']
      ]);

    }

		// 게시글 번호로 아이디를 가져온다
		function getUserIdByPNum($qNum){
			$sql = "SELECT id FROM goods WHERE num = :num";
			$result = $this->getRecord($sql,[":num"=> $qNum]);
			return $result['id'];
		}
		// 글번호로 상품글 정보를 가져온다
		function getInfoByPNum($pNum){
			$sql = "SELECT * FROM goods WHERE num = :num";
			$row = $this->getRecord($sql,["num" => $pNum]);
			return $row;
		}

		// 상품글 수정
    function modifyGoods($pNum,$data){
      $sql = "UPDATE goods SET ";
      $sql.= "title       = :title, ";
      $sql.= "name        = :name, ";
      $sql.= "price       = :price, ";
      $sql.= "quantity    = :quantity, ";
      $sql.= "content     = :content, ";
      $sql.= "mainImg     = :mainImg, ";
      $sql.= "regist_day  = :regist_day ";
      $sql.= "WHERE num   = :num";

      // 자료가 빈칸인 경우 이전값
      if($data['mainImg'] == ''){
        $lastData = $this->getInfoByPNum($pNum);
        $data['mainImg'] = $lastData['mainImg'];
      }



      $this->execute($sql,[
        ":title"      => $data['title'],
        ":name"       => $data['name'],
        ":price"      => $data['price'],
        ":quantity"   => $data['quantity'],
        ":content"    => $data['content'],
        ":regist_day" => $data['date'],
        ":mainImg"    => $data['mainImg'],
        ":num"        => $pNum
      ]);
    }

		// 상품글 삭제
		function deleteGoods($pNum){
			$sql = "DELETE FROM goods WHERE num = :num";
			$this->execute($sql,[":num" => $pNum]);
		}

		// 해당 부모글 번호의 댓글을 모두 지움
		function deleteAllReview($pNum){
			$sql = "DELETE FROM goodsReview WHERE parent = :parent";
			$this->execute($sql,[":parent" => $pNum]);
		}

		// 구매 정보 반환
		function getBuyListInfo($userId){
			$sql = "SELECT b.id as buyer, b.num, g.title, g.price, g.mainImg FROM buyList b,goods g WHERE b.num = g.num AND b.id=:id";
			$data = $this->getAllRecord($sql,[":id" => $userId]);
			return $data;
		}

		// 장바구니 정보 반환
		function getWishListInfo($userId){
			// 장바구니 정보
			$sql = "SELECT w.id as buyer, w.num, g.title, g.price, g.mainImg FROM wishList w, goods g WHERE w.num = g.num AND w.id=:id";
			$data = $this->getAllRecord($sql,[":id" => $userId]);
			return $data;
		}
	}

?>
