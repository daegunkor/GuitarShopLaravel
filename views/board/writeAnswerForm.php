<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
<link rel="stylesheet" href="/css/question.css?var=<?=filemtime('/css/question.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">

<!--질문글 업로드 폼-->
<form action="<?=$base_url?>/board/writeAnswer" method="post">
  <table id='writeQuestionTable' align='center'>
    <tr>
      <td class='answerContentfirstTd'>제목</td>
      <td><input type="text" name="title" size='80'></td>
    </tr>
    <tr>
      <td class='answerContentfirstTd'>글쓴이</td>
      <td><?=$userId."[{$nick}]"?></td>
    </tr>
    <tr>
      <td class='answerContentfirstTd'>내용</td>
      <td>
        <textarea name="content" cols='80' rows='10'></textarea>
      </td>
    </tr>
  </table>
  <div class="registAnswerFunc">
    <input type="submit" value="글쓰기">
    <input type="reset" value="다시쓰기">
  </div>
  <input type="hidden" name="_token" value="<?=$_token?>">
  <input type="hidden" name="qNum" value="<?=$_GET['qNum']?>">
  <input type="hidden" name="userid" value="<?=$userId?>">
  <input type="hidden" name="nick" value="<?=$nick?>">
</form>
<br>
