<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
<link rel="stylesheet" href="/css/buying.css?var=<?=filemtime('/css/buying.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">
<script>
  // 폼을 전송하기전 확인절차
  function sendForm(){
    // 폼 객체
    var formIns = document.getElementById('goodsInputForm');
    // 모든 값이 정확히 입력 되었을 경우
    if(confirmInputValue()){
      copyDivToHidden();
      formIns.submit();
    }
  }
  // 각 입력 내용 에러 확인
  function confirmInputValue(){
    var titleInput    = document.getElementById('titleInput');
    var nameInput     = document.getElementById('nameInput');
    var priceInput    = document.getElementById('priceInput');
    var quantityInput = document.getElementById('quantityInput');
    var mainImgUpload = document.getElementById('mainImgUpload');

    if(titleInput.value == ""){
      alert("제목을 입력하세요");
      return false;
    } else if (nameInput.value == ""){
      alert("상품명을 입력하세요");
      return false;
    } else if(priceInput.value == ""){
      alert("가격을 입력하세요");
      return false;
    } else if(!isNumber(priceInput.value)){
      alert("가격은 숫자값입니다.");
      return false;
    } else if(quantityInput.value == ""){
      alert("수량을 입력하세요");
      return false;
    } else if(!isNumber(quantityInput.value)){
      alert("수량은 숫자값입니다");
      return false;
    } else if(mainImgUpload.value == ""){
      alert("메인이미지를 첨부해주세요");
      return false;
    } else {
      return true;
    }
  }
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
  // 이미지 업로드 윈도우
  function popImgUploadWindow(){
    var popOption = 'top=' + (screen.availHeight/2-511/2) + ', left=' + (screen.availWidth/2-700/2) + ', width=500, height=400';
    window.open('<?=$base_url?>/shopping/popImgUpload','goodsImgUpload',popOption);
  }

  // 숫자 확인 1이상 숫자 판단
  function isNumber(argNum){
    if(isNaN(argNum) || argNum < 1){
      return false;
    }
    return true;
  }
</script>

<!--상품글 업로드 폼-->
<form id='goodsInputForm' enctype="multipart/form-data" action="<?=$base_url?>/shopping/registGoods" method="post">
  <table id='registGoodsTable' align='center'>
    <tr>
      <td class='registGoodsfirstTd'>제목</td>
      <td><input type="text" name="titleInput" id="titleInput"></td>
    </tr>
    <tr>
      <td class='registGoodsfirstTd'>글쓴이</td>
      <td><?=$userId."[{$nick}]"?></td>
    </tr>
    <tr>
      <td class='registGoodsfirstTd'>제품명</td>
      <td><input type="text" name="nameInput" id="nameInput"></td>
    </tr>
    <tr>
      <td class='registGoodsfirstTd'>가격</td>
      <td><input type="text" name="priceInput" id="priceInput"></td>
    </tr>
    <tr>
      <td class='registGoodsfirstTd'>수량</td>
      <td><input type="text" name="quantityInput" id="quantityInput"></td>
    </tr>
    <tr>
      <td class='registGoodsfirstTd'>메인이미지</td>
      <td><input type="file" name="mainImgUpload" id="mainImgUpload"></td>
    </tr>
    <tr>
      <td class='registGoodsfirstTd'>내용</td>
      <td>
        <div id="contentDiv" contenteditable="true"></div>
        <textarea id="divCopyHidden" name="content" style="display:none"></textarea>
      </td>
    </tr>
    <tr>
      <td class='registGoodsfirstTd'>이미지 첨부</td>
      <td><input type="button" value="이미지 업로드" onclick="popImgUploadWindow()" /></td>
    </tr>
  </table>
  <input type="hidden" name="_token" value="<?=$_token?>">
  <div class="registGoodsFunc">
    <input type="button"  onclick="sendForm()" value="상품등록">
    <input type="reset" onclick="resetContent()" value="다시쓰기">
  </div>
</form>
