<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
<link rel="stylesheet" href="/css/question.css?var=<?=filemtime('/css/question.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">
<?php
  echo ("
    <table align='center' id='QnAContentTable'>
      <tr>
        <td class='QnAContentfirstTd'>제목</td>
        <td class='QnASubjectTd'>{$data['title']}</td>
      </tr>
      <tr>
        <td class='QnAContentfirstTd'>항목</td>
        <td class='QnASubjectTd'>글쓴이 : {$data['nick']} | 날짜 : {$data['regist_day']} | 조회수 : {$data['hit']}</td>
      </tr>
      <tr>
  ");
  if($data['norFile'] == "")
    echo("
        <td class='QnAContentfirstTd'>자료</td>
        <td class='QnASubjectTd'>자료없음</td>
      </tr>
    ");
  else
    echo("
        <td class='QnAContentfirstTd'>자료</td>
        <td class='QnASubjectTd'><a href='{$base_url}/board/fileDownload?qNum={$qNum}&fileName={$data['norFile']}'>{$data['norFile']}</a></td>
      </tr>
    ");
  echo("
      <tr>
        <td class='QnAContentfirstTd'>내용</td>
        <td>{$data['content']}<td>
      </tr>
    </table>
  ");
  echo("<div id='QnAContentfuncDiv'>");
  if($session->get('userid') == $data['id']){
    //수정 삭제
    echo ("<a href='{$base_url}/board/modifyBoard?qNum={$qNum}'>수정</a>");
    echo ("<a href='{$base_url}/board/deleteBoard?qNum={$qNum}'>삭제</a>");
  }
  echo ("<a href='{$base_url}/board/writeAnswerForm?qNum=$qNum'>답변달기</a>");
  echo("<a href='$base_url/board'>목록</a>");
  echo("</div>");


  foreach($ansData as $data){
    echo ("
      <table id='answerTable' align='center'>
        <tr>
          <td class='answerfirstTd'>제목</td>
          <td class='answerSubjectTd'>{$data['title']}</td>
        </tr>
        <tr>
          <td class='answerfirstTd'>항목</td>
          <td class='answerSubjectTd'>글쓴이 : {$data['id']}[{$data['nick']}] | 날짜 : {$data['regist_day']}
        ");
    if($data['id'] == $_SESSION['userid'])
      echo(" | <a href='{$base_url}/board/cancelAnswer?id={$data['id']}&date={$data['regist_day']}&qNum={$qNum}'>삭제</a>");
    echo ("
          </td>
        </tr>
        <tr>
          <td class='answerfirstTd'>내용</td>
          <td>{$data['content']}<td>
        </tr>
      </table>
    ");
  }
?>
