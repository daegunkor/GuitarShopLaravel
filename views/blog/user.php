<?php $this->setPageTitle('title', $user['user_name']) ?>
<h2> <?php print $this->escape($user['user_name']); ?> </h2>
<?php if(!is_null($followstate)): ?>
  <?php if($followstate): ?>
    <p>Follow하고 있는 사용자입니다.</p>
  <?php else: ?>
    <form action="<?php print $base_url; ?>/follow" method="post">
      <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
      <input type="hidden" name="follow_user_name" value="<?php print $this->escape($user['user_name']); ?>" />
      <input type="submit" value="독자가됨" />
    </form>
  <?php endif; ?>
<?php endif; ?>
<div id="statuses">
  <?php foreach ($statuses as $status): ?>
  <?php print $this->render('blog/status', array('status'=>$status)); ?>
  <?php endforeach; ?>
</div>
