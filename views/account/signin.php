<!-- 39페이지 -->

<?php $this->setPageTitle('title', '로그인'); ?>
<h2>로그인</h2>
<p>
  <a href="<?php print $base_url; ?>/account/signup">계정 등록</a>
  <!-- AccountController의 signupAction 메소드 -->
</p>
<form action="<?php print $base_url; ?>/account/authenticate" method="post">
<!-- AccountController의 authenticateAction 메소드 -->
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />

  <?php if(isset($errors) && count($errors) > 0): ?>
    <?php print $this->render('errors', array('errors' => $errors)); ?>
  <?php endif; ?>
<!-- /views/errors.php를 Rendering -->

  <?php print $this->render('account/inputs', array('user_name' => $user_name, 'password' => $password,
  )); ?>
<!-- /views/account/inputs.php를 Rendering -->
  <p>
    <input type="submit" value="로그인" />
  </p>
</form>
