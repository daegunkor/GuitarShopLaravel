<?php
class ShoppingController extends Controller{
  const REGISTGOODS = 'shopping/registGoods';
  const MODIFYGOODS = 'shopping/modifyGoods';
  const WRITEREVIEW = 'shopping/writeReview';
  protected $_authentication = array('shopContent');
  // 상품 리스트 출력
  function shopContentAction(){
    // 현재 페이지 설정
    if($this->_request->getGet('page') == null)
      $curPage = 1;
    else
      $curPage = $this->_request->getGet('page');

    // 한페이지에 10개씩 출력
    $maxQCnt = 6;
    // 전체 페이지 갯수
    $totalPageCnt = $this->_connect_model->get('Shop')->getTotalPageCnt($maxQCnt);
    // 현 페이지 시작 글 카운트
    $startCnt = ($curPage-1) * $maxQCnt;
    // 현 페이지 끝 글 카운트
    $lastCnt = $curPage * $maxQCnt - 1;

    // DB *****
    $data = $this->_connect_model->get('Shop')->getGoodsList();

    $dataDir = "/BuyUploads/goodsData/";

    $shopContent_view = $this->render(array(
      'start'         => $startCnt,
      'number'        => $lastCnt,
      'data'          => $data,
      'curPage'       => $curPage,
      'dataDir'       => $dataDir,
      'totalPageCnt'  => $totalPageCnt
    ));


    return $shopContent_view;
  }
  // 상품 내용 출력
  function goodsContentAction(){
    // 유저 정보
    $userId = $this->_session->get('userid');
    $nick = $this->_session->get('userNick');

    // 상품번호
    $pNum = $this->_request->getGet('pNum');

    // 상품정보
    $data = $this->_connect_model->get('Shop')->getGoodsContent($pNum);

    // 이미지 경로
    $dataDir = "/buyUploads/goodsData/";
    $imgDir = $dataDir.$data['num']."/data/".$data['mainImg'];

    // 답글 정보
    $reviewData = $this->_connect_model->get('Shop')->getReviewContent($pNum);

    $goodsContent_view = $this->render(array(
      'pNum'          => $pNum,
      'data'          => $data,
      'dataDir'       => $dataDir,
      'imgDir'        => $imgDir,
      'userId'        => $userId,
      'nick'          => $nick,
      'reviewData'    => $reviewData,
      '_token'        => $this->getToken(self::WRITEREVIEW)
    ));


    return $goodsContent_view;
  }

  // 상품 구매
  function buyGoodsAction(){
    $pNum = $this->_request->getGet('pNum');
    $userId = $this->_session->get('userid');

    // 상품 구매처리
    if($this->_connect_model->get('Shop')->buyGoods($userId, $pNum))
      echo("
        <script>
          alert('상품을 구매하셨습니다.');
          history.go(-1);
        </script>
      ");
    else
      echo("
        <script>
          alert('상품이 0개 이하입니다');
          history.go(-1);
        </script>
      ");
    //$this->redirect("/shopping/goodsContent?pNum={$pNum}");
  }

  // 장바구니 추가
  function insertWishListAction(){
    // 구매 상품의 번호
    $pNum = $this->_request->getGet('pNum');
    $userId = $this->_session->get('userid');

    // 상품 구매처리
    $this->_connect_model->get('Shop')->insertWishList($userId, $pNum);

    echo("
      <script>
        alert('상품을 장바구니에 담았습니다');
        history.go(-1);
      </script>
    ");
  }

  // 상품 리뷰 등록
  function writeReviewAction(){
    $pNum     = $this->_request->getPost('pNum');       // 상품 번호
    $content  = $this->_request->getPost('content');    // 리뷰 내용
    $date     = date("Y-m-d H:i:s");  // 게시날짜
    $userId   = $this->_request->getPost('userid');     // 사용자 아이디
    $nick     = $this->_request->getPost('nick');       // 사용자 닉네임
    $token    = $this->_request->getPost('_token');

    if(!$this->checkToken(self::WRITEREVIEW, $token)){
      return $this->redirect('/shopping');
    }

    $this->_connect_model->get('Shop')->writeReview([
      "parent"  => $pNum,
      "content" => $content,
      "date"    => $date,
      "id"      => $userId,
      "nick"    => $nick
    ]);

    echo("
      <script>
        alert('리뷰가 작성되었습니다.');
        history.go(-1);
      </script>
    ");

  }

  // 상품 리뷰 삭제
  function deleteReviewAction(){
    $id = $this->_request->getGet('id');;
    $date = $this->_request->getGet('date');

    $this->_connect_model->get('Shop')->deleteReview($id,$date);

    echo("
      <script>
        alert('해당 댓글을 삭제하였습니다.');
        history.go(-1);
      </script>
    ");
  }
  // 상품 등록 입력 양식
  function registGoodsFormAction(){
    // 유저 정보
    $userId = $this->_session->get('userid');
    $nick = $this->_session->get('userNick');


    $registGoodsForm_view = $this->render(array(
      'userId'   => $userId,
      'nick'     => $nick,
      '_token'   => $this->getToken(self::REGISTGOODS)
    ));

    return $registGoodsForm_view;
  }

  // 임시 이미지 업로드 양식
  function popImgUploadAction(){
    $userId = $this->_session->get('userid');
    $writeBoard_view = $this->render(array(
      'userId'        => $userId,
      'template'      => ''
    ));

    return $writeBoard_view;
  }

  // 임시 이미지 업로드
  function uploadTmpImgAction(){
    $userId = $this->_session->get('userid');
    $temImgFile = $this->_request->getFile('temImgUp');

    $directory = "./buyUploads/tmpImg/".$userId;
    if(!file_exists($directory)) mkdir($directory);
    $this->_connect_model->get('File')->fileUploadToFolder($directory."/", $temImgFile);

    $uploadTmpImg_view = $this->render(array(
      'userId'        => $userId,
      'fileInfo'      => $temImgFile,
      'template'      => ''
    ));

    return $uploadTmpImg_view;
  }

  // 상품 등록
  function registGoodsAction(){

    // 유저 정보
    $userId = $this->_session->get('userid');
    $nick = $this->_session->get('userNick');

    $date = date("Y-m-d H:i:s");

    // 폼으로부터 입력받은 값
    $title    = $this->_request->getPost('titleInput');
    $price    = $this->_request->getPost('priceInput');
    $name     = $this->_request->getPost('nameInput');
    $quantity = $this->_request->getPost('quantityInput');
    $file     = $this->_request->getFile('mainImgUpload');
    $content  = $this->_request->getPost('content');
    $token    = $this->_request->getPost('_token');

    if(!$this->checkToken(self::REGISTGOODS, $token)){
      return $this->redirect('/'.self::REGISTGOODS."form");
    }

    $writeCnt = $this->_connect_model->get('Shop')->getHighCnt() + 1; // 현재 글의 글 번호
    $fileDir = "buyUploads/goodsData/{$writeCnt}"; // 데이터 위치
    $content = str_replace("buyUploads/tmpImg/".$userId,$fileDir."/img",$content); // 컨텐츠 내 임시 이미지 태그 수정

    // QnA질문 DB저장
    $this->_connect_model->get('Shop')->registGoods([
      "num"          => $writeCnt,
      "title"        => $title,
      "name"         => $name,
      "content"      => $content,
      "price"        => $price,
      "quantity"     => $quantity,
      "regist_day"   => $date,
      "id"           => $userId,
      "nick"         => $nick,
      "mainImg"      => basename($file['name'])
    ]);


    // 임시 이미지파일 게시판 파일로 이동
    if(!file_exists("./".$fileDir)) mkdir("./".$fileDir);
    if(file_exists("./buyUploads/tmpImg/{$userId}")){
      $this->_connect_model->get('File')->copy_directory("./buyUploads/tmpImg/{$userId}",$fileDir."/img");
      $this->_connect_model->get('File')->rmdirAll("./buyUploads/tmpImg/{$userId}");
    }

    // 일반파일 저장
    if(!file_exists("./".$fileDir."/data")) mkdir("./".$fileDir."/data");
    $this->_connect_model->get('File')->fileUploadToFolder("./".$fileDir."/data/", $file);

    // 상품 리스트 뷰로 돌아감
    $this->redirect('/shopping');
  }

  // 상품 수정 양식
  function modifyGoodsFormAction(){
    // 유저 정보
    $userId = $this->_session->get('userid');
    $nick = $this->_session->get('userNick');
    $date = date("Y-m-d H:i:s");

    // 수정 글 번호
    $pNum = $this->_request->getGet('pNum');

    // 해당 글의 글쓴이가 맞는지 확인
    if( $userId != $this->_connect_model->get('Shop')->getUserIdByPNum($pNum)){
      echo ("
        <script>
          alert('잘못된 접근입니다.');
          history.go(-1);
        </script>
      ");
    }

    // 수정전 제목, 내용, 파일첨부 내용
    $goodsInfo    = $this->_connect_model->get('Shop')->getInfoByPNum($pNum);
    $lastTitle    = $goodsInfo['title'];
    $lastName     = $goodsInfo['name'];
    $lastPrice    = $goodsInfo['price'];
    $lastQuantity = $goodsInfo['quantity'];
    $lastContent  = $goodsInfo['content'];

    $modifyGoodsForm_view = $this->render(array(
      'goodsInfo'         => $goodsInfo,
      'lastTitle'         => $lastTitle,
      'lastName'          => $lastName,
      'lastPrice'         => $lastPrice,
      'lastQuantity'      => $lastQuantity,
      'lastContent'       => $lastContent,
      'userId'            => $userId,
      'nick'              => $nick,
      'pNum'              => $pNum,
      '_token'            => $this->getToken(self::MODIFYGOODS)
    ));
    return $modifyGoodsForm_view;
  }

  // 상품 수정
  function modifyGoodsAction(){
    // 유저 정보
    $userId = $this->_session->get('userid');
    $nick = $this->_session->get('userNick');
    $date = date("Y-m-d H:i:s");

    // 폼으로부터 입력받은 값
    $title = $this->_request->getPost('titleInput');
    $name = $this->_request->getPost('nameInput');
    $price = $this->_request->getPost('priceInput');
    $quantity = $this->_request->getPost('quantityInput');
    $content = $this->_request->getPost('content');
    $pNum = $this->_request->getGet('pNum');
    $mainImg = $this->_request->getFile('mainImgUpload');
    $token    = $this->_request->getPost('_token');

    if(!$this->checkToken(self::MODIFYGOODS, $token)){
      return $this->redirect('/shopping');
    }

    $data = $this->_connect_model->get('Shop')->getInfoByPNum($pNum);
    $writeCnt = $data['num']; // 현재 글의 글 번호
    $fileDir = "buyUploads/goodsData/{$writeCnt}"; // 데이터 위치
    $content = str_replace("buyUploads/tmpImg/".$userId,$fileDir."/img",$content); // 컨텐츠 내 임시 이미지 태그 수정

    // QnA질문 DB저장
    $this->_connect_model->get('Shop')->modifyGoods($pNum,[
      "title"        => $title,
      "name"         => $name,
      "price"        => $price,
      "quantity"     => $quantity,
      "content"      => $content,
      "date"         => $date,
      "mainImg"      => basename($mainImg['name'])
    ]);


    // 임시 이미지파일 게시판 파일로 이동
    if(!file_exists("./".$fileDir)) mkdir("./".$fileDir);
    if(file_exists("./buyUploads/tmpImg/{$userId}")){
      $this->_connect_model->get('File')->copy_directory("./buyUploads/tmpImg/{$userId}","./".$fileDir."/img");
      $this->_connect_model->get('File')->rmdirAll("./buyUploads/tmpImg/{$userId}");
    }

    // 메인이미지 저장
    if(!file_exists("./".$fileDir."/data")) mkdir("./".$fileDir."/data");
    $this->_connect_model->get('File')->fileUploadToFolder("./".$fileDir."/data/", $mainImg);

    // 상품글뷰로 돌아감
    $this->redirect("/shopping/goodsContent?pNum={$pNum}");
  }

  // 상품 삭제
  function deleteGoodsAction(){
    // 질문의 글 번호
    $pNum = $this->_request->getGet('pNum');

    // 글의 글쓴이인가 재확인 (참 : 삭제, 거짓 : 잘못된 접근)
    if($this->_connect_model->get('Shop')->getUserIdByPNum($pNum) == $this->_session->get('userid')){
        $this->_connect_model->get('Shop')->deleteGoods($pNum);
        $this->_connect_model->get('Shop')->deleteAllReview($pNum);
    } else {
      throw new FileNotFoundException('잘못된 접근'.$this->_request->getPath());
    }

    // 상품리스트로 돌아감
    $this->redirect("/shopping");
  }

}
?>
