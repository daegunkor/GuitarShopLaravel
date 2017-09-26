<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
<link rel="stylesheet" href="/css/question.css?var=<?=filemtime('/css/question.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">
<script>
  // content내 내용을 hidden태그로 복사
  function copyDivToHidden(){
    var contentDir = document.getElementById("contentDiv");
    var hiddenInput = document.getElementById("divCopyHidden");
    hiddenInput.value = contentDir.innerHTML;
  }
  // Content 내 내용 제거
  function resetContent(){
    var contentDir = document.getElementById("contentDiv");
    contentDir.innerHTML = "";
  }

  function popImgUploadWindow(){
    var popOption = 'top=' + (screen.availHeight/2-511/2) + ', left=' + (screen.availWidth/2-700/2) + ', width=500, height=400';
    window.open('<?=$base_url?>/board/popImgUpload','wrongLogin',popOption);
  }
</script>

<!--질문글 업로드 폼-->
<form enctype="multipart/form-data" action="<?=$base_url?>/board/modifyBoardContent?qNum=<?=$qNum?>" method="post">
  <table id='QuestionWriteTable' align='center'>
    <tr>
      <td class='questionWritefirstTd'>제목</td>
      <td>&nbsp;&nbsp;<input type="text" name="titleInput" value="<?=$lastTitle?>" size='100'></td>
    </tr>
    <tr>
      <td class='questionWritefirstTd'>글쓴이</td>
      <td>&nbsp;&nbsp;<?=$userId."[{$nick}]"?></td>
    </tr>
    <tr>
      <td class='questionWritefirstTd'>내용</td>
      <td>
        <div id="contentDiv" contenteditable="true" style="border:solid 1px black"><?=$lastContent?></div>
        <textarea id="divCopyHidden" name="content" style="display:none"></textarea>
      </td>
    </tr>
    <tr>
      <td class='questionWritefirstTd'>이미지 첨부</td>
      <td>&nbsp;&nbsp;<input type="button" value="이미지 업로드" onclick="popImgUploadWindow()" /></td>
    </tr>
    <tr>
      <td class='questionWritefirstTd'>파일첨부</td>
      <td>
        &nbsp;&nbsp;수정하지 않으시면 전의 파일로 유지됩니다.<br>
        &nbsp;&nbsp;<input type="file" name="fileUpload">
      </td>
    </tr>
    <tr>
      <td colspan='2' id='imageUploadList'></td>
    </tr>
  </table>
  <input type="hidden" name="_token" value="<?=$_token?>">
  <div id ='questionWritefuncDiv'>
    <input type="submit" onclick="copyDivToHidden()" value="글쓰기">
    <input type="reset" onclick="resetContent()" value="다시쓰기">
  </div>
</form>
