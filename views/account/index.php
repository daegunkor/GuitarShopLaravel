<?php
  $this->setPageTitle('title','계정정보')
 ?>
 <div class="account">
   <h2>
     계정정보
   </h2>
   <p>
     사용자 ID:
      <a href="<?php print $base_url;?>/user/<?php print $this->escape($user['user_name']); ?>">
        <?php print $this->escape($user['user_name']); ?>
      </a>
   </p>
   <ul>
     <li>
       <a href="<?php print $base_url; ?>/account/signout">로그아웃</a>
    </li>
   </ul>
 </div>
 <div class="f_user">
   <h3>Follow하고 있는 사용자</h3>
   <?php if(count($followingUsers)>0): ?>
     <ul>
       <?php foreach ($followingUsers as $f_user): ?>
       <li>
         <a href="<?php print $base_url; ?>/user/<?php print $this->escape($f_user['user_name']); ?>">
         </a>
       </li>
     <?php endforeach; ?>
     </ul>
 </div>
 <?php endif; ?>
