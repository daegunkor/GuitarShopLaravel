<?php $this->setPageTitle('title', '계정 생성') ?>

<link rel="stylesheet" href="/css/memRegist.css?var=<?=filemtime('/css/memRegist.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">
<script>
  // 이메일 셀렉트에 따른 값 설정
  function setEmail(){
    var emailListArray = new Array();
    emailListArray['naver'] = "naver.com";
    emailListArray['daum'] = "daum.net";
    emailListArray['yahoo'] = "yahoo.co.kr";
    emailListArray['google'] = "google.com";
    emailListArray['ldghome'] = "ldghome.co.us";
    emailListArray['cyworld'] = "cyworld.com";
    emailListArray['typing'] = "";


    var selectedValue = document.registInputForm.emailInputSelect.value;
    var emailInput = document.registInputForm.emailInput2;
    emailInput.value = emailListArray[selectedValue];

    if (selectedValue == 'typing') {
      emailInput.readOnly = false;
      emailInput.style.backgroundColor = "#FFFFFF";
    } else {
      emailInput.readOnly = true;
      emailInput.style.backgroundColor = "#BDBDBD";
    }
  }
  // 아이디 중복 체크
  function idDupliCheck(){
    window.open("idDupliCheck?id="+document.registInputForm.idInput.value,"idCheckWindow",
    "left=200,top=200,width=200,height=60,scrollbars=no,resizable=yes");
  }
  // 닉네임 중복 체크
  function nickDupliCheck(){
    window.open("nickDupliCheck?nick="+document.registInputForm.nickInput.value,"idCheckWindow",
    "left=200,top=200,width=200,height=60,scrollbars=no,resizable=yes");
  }
  //모든 입력 정보 체크
  function checkInfos(){
    // input_id 아이디
    var input_id = document.getElementById('input_id').value;
    // input_pass 비밀번호
    var input_pass = document.getElementById('input_pass').value;
    // input_pass_confirm 비밀번호 확인
    var input_pass_confirm = document.getElementById('input_pass_confirm').value;
    // input_name 이름
    var input_name = document.getElementById('input_name').value;
    // input_hp1, input_hp2, input_hp3 핸드폰 번호
    var input_hp1 = document.getElementById('input_hp1').value;
    var input_hp2 = document.getElementById('input_hp2').value;
    var input_hp3 = document.getElementById('input_hp3').value;
    // input_email1, input_email2 이메일
    var input_email1 = document.getElementById('input_email1').value;
    var input_email2 = document.getElementById('input_email1').value;

    // 아이디 확인
    if(input_id.length <= 0){
      alert('아이디를 입력하세요.');
      return false;
    // 비밀번호, 비밀번호_확인 확인
    } else if(input_pass.length <=0){
      alert('비밀번호를 입력하세요.');
      return false;
    } else if(input_pass_confirm.length <=0){
      alert('비밀번호 확인을 입력하세요.');
      return false;
    } else if(input_pass != input_pass){
      alert('비밀번호를 다시 확인해 주세요');
      return false;

    } else if(input_pass != input_pass_confirm){
      alert('비밀번호를 다시 확인해 주세요');
      return false;
      // 이름 확인
    } else if(input_name.length <= 0){
      alert('이름을 입력해주세요.');
      return false;
      // 핸드폰 번호 확인
    } else if(input_hp1.length <= 0 || input_hp2.length <= 0 || input_hp3.length <=0) {
      alert('핸드폰 번호를 입력해 주세요.');
      return false;
      // 이메일 확인
    } else if(input_email1.length <= 0 || input_email1.length <= 0){
      alert('이메일을 입력해 주세요.');
      return false;
    }
    return true;
  }
  // form문 전송
  function send(){
    var sendForm = document.registInputForm;
    if(checkInfos())
      sendForm.submit();
  }
</script>
<br>
<div id='memRegistTopBar'>
  회원가입
</div>
<br>

<?php if(isset($errors) && count($errors) > 0): ?>
<?php print $this->render('errors', array('errors' => $errors)); ?>
<?php endif; ?>

<div id="login_wrap">
  <form  name="registInputForm" action="<?php print $base_url; ?>/account/register" method="post" >
    <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>"/>
    <?php print $this->render('account/inputs',array(
          'idInput'     => $idInput,
          'passInput'   => $passInput,
          'passConfirm' => $passConfirm,
          'nameInput'   => $nameInput,
          'nickInput'   => $nickInput,
          'hpInput1'    => $hpInput1,
          'hpInput2'    => $hpInput2,
          'hpInput3'    => $hpInput3,
          'emailInput1' => $emailInput1,
          'emailInput2' => $emailInput2)); ?>
    <br>
    <div id='memRegistfuncDiv'>
      <input type="button" id='register' onclick='send(this)' value="회원가입">
      <input type="reset" value="다시쓰기">
    </div>
    <br>
  </form>
</div>
