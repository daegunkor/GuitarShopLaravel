<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <!-- 각 action에 따른 views폴더내의 view파일들에서 설정하여 보내줌-->
    <title>
        <?php if (isset($title)): print $this->escape($this).'-'; endif; ?>
        Weblog
    </title>
    <!-- { endfor; endwhile; endswitch; endforeach;} -->
    <link rel="stylesheet"
          type="text/css"
          href="/css/style.css?var=<?=filemtime('/css/style.css')?>"/>
    <script>
      function checkIdInfos(){
        var idBox = document.getElementById('userid');
        var passBox = document.getElementById('userpass');

        if(idBox.value.length <= 0){
          alert('아이디를 입력하세요');
          return false;
        } else if(passBox.value.length <= 0){
          alert('비밀번호를 입력하세요');
          return false;
        }
        return true;
      }

      function sendForm(){
        var form = document.getElementById('loginForm');
        if(checkIdInfos())
          form.submit();
      }
    </script>
</head>
<body>
<div id="wrapper">
  <div id="top_wrap">
    <div id="top_left">
    </div>
    <div id="top_logo">
      <a href="<?php print $base_url; ?>/"><img src="/img/topimg.jpg"/ border="0"></a>
    </div>
    <div id="top_right">
      <?php
        if($session->isAuthenticated()){
        echo ("
          <div id='login_box'>
            {$session->get('userNick')}님 반갑다.
            <a href='{$base_url}/account/signout'>로그아웃</a>
            <a href='{$base_url}/myPage'>마이페이지</a>
          </div>
        ");
      } else {
        echo("
        <div id='login_box'>
          <form id='loginForm' action='{$base_url}/account/authenticate' method='post'>
            ID <input type='text' id='userid' name='userid' size='8' value=''>
            PW <input type='password' id='userpass' name='userpass' size='8' value=''>
            <input type='button' onclick='sendForm()' value='로그인'>
          </form>
          <a href='{$base_url}/account/signup'>회원가입</a>
        </div>
        ");
      }
    ?>
    </div>
  </div>
  <?php require 'menubar.php'; ?>
  <!-- <div id="nav">
      <?php if ($session->isAuthenticated()): ?>
          <a href="<?php print $base_url; ?>/">
              Top Page
          </a>
          <a href="<?php print $base_url; ?>/account">
              계정
          </a>
      <?php else: ?>
          <a href="<?php print $base_url; ?>/account/signin">
              로그인
          </a>
          <a href="<?php print $base_url; ?>/account/signup">
              계정 등록(회원가입)
          </a>
      <?php endif; ?>
  </div> -->

  <div id="main">
      <?php print $_content; ?>
      <!-- $_content: View 객체의 render()메서드에서 전달해줌 -->
  </div>
</div>
</body>
</html>
