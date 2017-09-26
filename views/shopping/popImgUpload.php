<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="/css/imgUploadPop.css?var=<?=filemtime('/css/imgUploadPop.css')?>">
    <script>
      // 확인 후 파일 전송
      function sendForm(){
        var imgInputForm = document.getElementById("imgInputForm");
        var uploadImg = document.getElementById("uploadImg");
        if(uploadImg.value == "") alert("파일 경로를 선택해주세요.");
        else {
          imgInputForm.submit();
        }
      }

    </script>
  </head>
  <body>
    <div class="imgInputTopDiv">
      이미지 업로드
    </div>
    <div class="imgInputDiv">
      <form id="imgInputForm" enctype="multipart/form-data" action="<?=$base_url?>/shopping/uploadTmpImg" method="post">
        <input type="file" id="uploadImg" name="temImgUp">
        <input type="hidden" id="userId" value="<?=$userId?>">
        <br><br>
        <input type="button" onclick="sendForm()" value="올리기">
        <input type="button" onclick="window.close()" value="취소">
      </form>
    </div>
  </body>
</html>
