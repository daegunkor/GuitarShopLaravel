<?php
class BoardController extends Controller{
  const WRITEBOARD = 'board/writeBoard';
  const MODIFYBOARD = 'board/modifyBoard';
  const WRITEANSWER = 'board/writeAnswer';
  const BOARDCONTENT = 'board/boardContent';
  protected $_authentication = array('showBoard','boardContent');
  // 게시글 리스트
  function showBoardAction(){
    $dataDir = "./uploads/questionData/";
    // 현재 페이지 설정
    if($this->_request->getGet('page') == null)
      $curPage = 1;
    else
      $curPage = $this->_request->getGet('page');

    // 한페이지에 10개씩 출력
    $maxQCnt = 10;
    // 전체 페이지 갯수
    $totalPageCnt = $this->_connect_model->get('Board')->getTotalPageCnt($maxQCnt);
    // 현 페이지 시작 글 카운트
    $startCnt = ($curPage-1) * $maxQCnt;
    // 현 페이지 끝 글 카운트
    $lastCnt = $curPage * $maxQCnt - 1;

    $data = $this->_connect_model->get('Board')->getBoardData();//

    $showBoard_view = $this->render(array(
      'start'     => $startCnt,
      'number'    => $lastCnt,
      'data'      => $data,
      'curPage'     => $curPage,
      'totalPageCnt'  => $totalPageCnt
    ));


    return $showBoard_view;
  }

  // 게시글 내용 보기
  function boardContentAction(){
    $qNum = $this->_request->getGet('qNum');

    // 게시글 조회수 증가
    $this->_connect_model->get('Board')->hitUpper($qNum);
    // 게시글 컨텐츠 출력
    $data = $this->_connect_model->get('Board')->getBoardContent($qNum);

    // 답변 컨텐츠 출력
    $ansData = $this->_connect_model->get('Board')->getAnswerContent($qNum);

    $boardContent_view = $this->render(array(
      'qNum'     => $qNum,
      'data'      => $data,
      'ansData'   => $ansData
    ));

    return $boardContent_view;

  }

  // 게시글 입력
  function writeBoardAction(){
    $userid = $this->_session->get('userid');
    $nick = $this->_session->get('userNick');

    $writeBoard_view = $this->render(array(
      'userId'      => $userid,
      'nick'        => $nick,
      '_token'      => $this->getToken(self::WRITEBOARD)
    ));

    return $writeBoard_view;
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

    $directory = "./uploads/tmpImg/".$userId;
    if(!file_exists($directory)) mkdir($directory);
    $this->_connect_model->get('File')->fileUploadToFolder($directory."/", $temImgFile);

    $uploadTmpImg_view = $this->render(array(
      'userId'        => $userId,
      'fileInfo'      => $temImgFile,
      'template'      => ''
    ));

    return $uploadTmpImg_view;
  }
  // 게시글 업로드
  function uploadBoardAction(){
    $userId = $this->_session->get('userid');
    $date = date("Y-m-d H:i:s");

    // 유저의 아이디로 정보를 가져온다
    $info = $this->_connect_model->get('User')->getUserRecord($userId);
    $nick = $info['nick'];

    // 폼으로부터 입력받은 값
    $title = $this->_request->getPost('titleInput');
    $content = $this->_request->getPost('content');
    $file = $this->_request->getFile('fileUpload');
    $token = $this->_request->getPost('_token');

    if(!$this->checkToken(self::WRITEBOARD, $token)){
      return $this->redirect('/'.self::WRITEBOARD);
    }

    $writeCnt = $this->_connect_model->get('Board')->getHighCnt() + 1; // 현재 글의 글 번호
    $fileDir = "uploads/questionData/{$writeCnt}"; // 데이터 위치
    $content = str_replace("uploads/tmpImg/".$userId,$fileDir."/img",$content); // 컨텐츠 내 임시 이미지 태그 수정
    // QnA질문 DB저장
    $this->_connect_model->get('Board')->writeQuestionBoard([
      "num"          => $writeCnt,
      "title"        => $title,
      "content"      => $content,
      "registDay"    => $date,
      "id"           => $userId,
      "nick"         => $nick,
      "norFile"      => basename($file['name'])
    ]);

    // 임시 이미지파일 게시판 파일로 이동
    if(!file_exists("./".$fileDir)) mkdir("./".$fileDir);
    if(file_exists("./uploads/tmpImg/{$userId}")){
      $this->_connect_model->get('File')->copy_directory("./uploads/tmpImg/{$userId}","./".$fileDir."/img");
      $this->_connect_model->get('File')->rmdirAll("./uploads/tmpImg/{$userId}");
    }
    // 일반파일 저장
    if(!file_exists("./".$fileDir."/data")) mkdir("./".$fileDir."/data");
    $this->_connect_model->get('File')->fileUploadToFolder("./".$fileDir."/data/", $file);

    // 게시판 뷰로 돌아감
    $this->redirect('/board');

  }

  // 파일 다운로드
  function fileDownloadAction(){
    $fileDir = "./uploads/questionData/".$this->_request->getGet('qNum')."/data/"; // 파일경로
    $fileName = $this->_request->getGet('fileName'); // 파일명
    if(file_exists($fileDir.$fileName)){

      if (is_file($fileDir.$fileName)) {
        Header("Content-type:application/octet-stream");
        Header("Content-Length:".filesize($fileDir.$fileName));
        Header("Content-Disposition:attachment;filename=".$fileName);
        Header("Content-type:file/unknown");
        header('Content-Transfer-Encoding: binary');

        Header("Content-Description:PHP5 Generated Data");

        // 캐시방지
        Header("Pragma: no-cache");
        Header("Expires: 0");
        // header('Content-Type: application/x-octetstream');
        // header('Content-Length: '.filesize($file_dir.$real_filename));   //파일의 경로 로 사이즈를 알수있음.
        // header('Content-Disposition: attachment; filename='.$real_filename);
        // header('Content-Transfer-Encoding: binary');
        $fp = fopen($fileDir.$fileName, "r");
        if (!fpassthru($fp)) fclose($fp);
        clearstatcache();
      }
    }else{
      echo ("
        <script>
          alert('존재하지 않는 파일입니다');
          history.go(-1);
        </script>
      ");
    }
  }

  // 게시글 수정 양식
  function modifyBoardAction(){
    $userId = $this->_session->get('userid');
    // 유저의 아이디로 정보를 가져온다
    $info = $this->_connect_model->get('User')->getUserRecord($userId);
    $nick = $info['nick'];

    // 수정 글 번호
    $qNum = $this->_request->getGet('qNum');

    // 해당 글의 글쓴이가 맞는지 확인
    if($userId != $this->_connect_model->get('Board')->getUserIdByQNum($qNum)){
      throw new FileNotFoundException('잘못된 접근'.$this->_request->getPath());
    }

    // 수정전 제목, 내용, 파일첨부 내용
    $questionInfo = $this->_connect_model->get('Board')->getInfoByQNum($qNum);
    $lastTitle = $questionInfo['title'];
    $lastContent = $questionInfo['content'];
    $lastNorFile = $questionInfo['norFile'];

    $modifyBoard_view = $this->render(array(
      'userId'        => $userId,
      'nick'          => $nick,
      'qNum'          => $qNum,
      'lastTitle'     => $lastTitle,
      'lastContent'  => $lastContent,
      'lastNorFile'   => $lastNorFile,
      '_token'        => $this->getToken(self::MODIFYBOARD)
    ));

    return $modifyBoard_view;
  }

  // 게시글 수정 업로드
  function modifyBoardContentAction(){

    $userId = $this->_session->get('userid');
    $date = date("Y-m-d H:i:s");

    // 유저의 아이디로 정보를 가져온다
    $info = $this->_connect_model->get('User')->getUserRecord($userId);
    $nick = $info['nick'];

    // 폼으로부터 입력받은 값
    $title = $this->_request->getPost('titleInput');
    $content = $this->_request->getPost('content');
    $qNum = $this->_request->getGet('qNum');
    $file = $this->_request->getFile('fileUpload');
    $token = $this->_request->getPost('_token');

    // 토큰 검사
    if(!$this->checkToken(self::MODIFYBOARD, $token)){
      return $this->redirect('/board');
    }

    $data = $this->_connect_model->get('Board')->getInfoByQNum($qNum);
    $writeCnt = $data['num']; // 현재 글의 글 번호
    $fileDir = "uploads/questionData/{$writeCnt}"; // 데이터 위치
    $content = str_replace("uploads/tmpImg/".$userId,$fileDir."/img",$content); // 컨텐츠 내 임시 이미지 태그 수정

    // QnA질문 DB저장
    $this->_connect_model->get('Board')->modifyQuestion($qNum,[
      "title"        => $title,
      "content"      => $content,
      "date"         => $date,
      "norFile"      => basename($file['name'])
    ]);


    // 임시 이미지파일 게시판 파일로 이동
    if(!file_exists("./".$fileDir)) mkdir("./".$fileDir);
    if(file_exists("./uploads/tmpImg/{$userId}")){
      $this->_connect_model->get('File')->copy_directory("./uploads/tmpImg/{$userId}","./".$fileDir."/img");
      $this->_connect_model->get('File')->rmdirAll("./uploads/tmpImg/{$userId}");
    }
    // 일반파일 저장
    if(!file_exists("./".$fileDir."/data")) mkdir("./".$fileDir."/data");
    $this->_connect_model->get('File')->fileUploadToFolder("./".$fileDir."/data/", $file);

    // 게시판 뷰로 돌아감
    $this->redirect('/board');

  }
  // 게시글 삭제
  function deleteBoardAction(){
    // 로그인 유저 아이디
    $userId = $this->_session->get('userid');
    // 질문의 글 번호
    $qNum = $this->_request->getGet('qNum');
    // 글의 글쓴이인가 재확인 (참 : 삭제, 거짓 : 잘못된 접근)
    if($userId == $this->_connect_model->get('Board')->getUserIdByQNum($qNum)) {
        $this->_connect_model->get('Board')->deleteQuestion($qNum);
        $this->_connect_model->get('Board')->deleteAnswer($qNum);
    } else {
        throw new FileNotFoundException('잘못된 접근'.$this->_request->getPath());
    }
    // 게시판 뷰로 돌아감
    $this->redirect('/board');
  }

  // 게시글의 답글 작성 폼
  function writeAnswerFormAction(){


    // 로그인 유저 아이디
    $userId = $this->_session->get('userid');

    // 답글을 다는 게시글의 번호
    $qNum = $this->_request->getGet('qNum');

    // 유저의 아이디로 정보를 가져온다
    $info = $this->_connect_model->get('User')->getUserRecord($userId);
    $nick = $info['nick'];

    $writeAnswerForm_view = $this->render(array(
      'userId'        => $userId,
      'nick'          => $nick,
      'qNum'          => $qNum,
      '_token'        => $this->getToken(self::WRITEANSWER)
    ));

    return $writeAnswerForm_view;
  }

  // 게시글의 답글 업로드
  function writeAnswerAction(){
    $parent     = $this->_request->getPost('qNum');       // 답글 대상글
    $title      = $this->_request->getPost('title');      // 제목
    $content    = $title = $this->_request->getPost('content');    // 내용
    $date       = date("Y-m-d H:i:s");  // 현재 날짜
    $id         = $this->_request->getPost('userid');     // 글쓴이
    $nick       = $this->_request->getPost('nick');       // 닉네임
    $token      = $this->_request->getPost('_token');

    if(!$this->checkToken(self::WRITEANSWER, $token)){
      return $this->redirect('/board');
    }

    $this->_connect_model->get('Board')->writeAnswer([
      "parent"  => $parent,
      "title"   => $title,
      "content" => $content,
      "date"    => $date,
      "id"      => $id,
      "nick"    => $nick
    ]);

    // 게시글 내용으로 돌아감
        $this->redirect('/board/boardContent?qNum='.$parent);
  }

  // 게시글의 답글 삭제
  function cancelAnswerAction(){
    $id   = $this->_request->getGet('id');
    $date = $this->_request->getGet('date');
    $qNum = $this->_request->getGet('qNum');

    $this->_connect_model->get('Board')->cancelAnswer($id,$date);

    $this->redirect('/board/boardContent?qNum='.$qNum);
  }


}
?>
