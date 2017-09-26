<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
<link rel="stylesheet" href="/css/myPage.css?var=<?=filemtime('/css/myPage.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?<?=filemtime('/css/inputForm.css')?>">
<br>
<div id="buyListWrap">
  <?php
    // 구매 상품 리스트 출력
      echo("
        <div id='buyListHeader'>
          구매 상품
        </div>
        <div id='buyListDiv'>
      ");
      foreach($buyListData as $row){
        $imgDir = "/buyUploads/goodsData/".$row['num']."/data/".$row['mainImg'];  // 이미지 경로
        echo("
          <div class='buyInfoDiv'>
            <a href='{$base_url}/shopping/goodsContent?pNum={$row['num']}' class='buyListLink'>
              <div class='buyInfoImg'><img src='{$imgDir}' width='100%' height='100%'></div>
              <div class='buyInfoContent'>상품명 : {$row['title']} <br> 가격 : {$row['price']}</div>
            </a>
          </div>
        ");
      }
      echo("
        </div>
      ");
  ?>
</div>
<br>
<div id="wishListWrap">
  <?php

    // 장바구니 출력 ( + 총액 계산, 전부 구매, 장바구니 제거)
    // 장바구니 리스트 출력
      echo("
        <div id='wishListHeader'>
          장바구니
        </div>
        <div id='wishListDiv'>
      ");
      foreach($wishListData as $row){
        $imgDir = "/buyUploads/goodsData/".$row['num']."/data/".$row['mainImg'];  // 이미지 경로
        echo("
          <div class='wishInfoDiv'>
            <a href='{$base_url}/shopping/goodsContent?pNum={$row['num']}' class='wishListLink'>
              <div class='wishListInfoImgDiv'><img src='{$imgDir}' height='100%' width='100%'></div>
              <div class='wishListInfoContentDiv'>상품명 : {$row['title']} <br> 가격 : {$row['price']} | <a class='wishListCancel' href='deleteWishList.php?pNum={$row['num']}'>취소</a></div>
            </a>
          </div>
        ");
      }

      echo("
        </div>
        <div class='buyWishListDiv'>
          총 금액 : {$priceSum}
        </div>
      ");
  ?>
</div>
<br>
