<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
<link rel="stylesheet" href="/css/buying.css?var=<?=filemtime('/css/buying.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">
<div id='goodsContentDiv'>
  <?php
    // 게시글 컨텐츠 출력
    echo ("
        <div id='goodsTitleDiv'>
          {$data['title']}
        </div>
        <div id='goodsPurchaseDiv'>
          <div id='mainImgDiv' >
            <img src='{$imgDir}' width='100%' height='100%'>
          </div>
          <div id='purchaseDiv'>
            <table id='purchaseTable' align='center' height='100%''>
              <tr>
                <td class='goodsContentfirstTd'>판매자</td>
                <td class='goodsSubjectTd'>{$data['id']}[{$data['nick']}]</td>
              </tr>
              <tr>
                <td class='goodsContentfirstTd'>가격</td>
                <td class='goodsSubjectTd'>{$data['price']}</td>
              </tr>
              <tr>
                <td class='goodsContentfirstTd'>수량</td>
                <td class='goodsSubjectTd'>{$data['quantity']}</td>
              </tr>
              <tr>
                <td colspan='2'>
                  <a class='purchaseFunc' href='{$base_url}/shopping/buyGoods?pNum={$data['num']}'>바로구매</a>
                  <a class='purchaseFunc' href='{$base_url}/shopping/insertWishList?pNum={$data['num']}'>장바구니</a>
                </td>
              </tr>
            </table>
          </div>
        </div>

      ");
      echo ("<div id='funcBtn'>");
      if( $session->get('userid') == $data['id'] ){
        //수정 삭제

        echo ("<a class='purchaseFunc' href='{$base_url}/shopping/modifyGoodsForm?pNum={$pNum}'>수정</a>");
        echo ("<a class='purchaseFunc' href='{$base_url}/shopping/deleteGoods?pNum={$pNum}'>삭제</a>");
      }
      echo ("<a class='purchaseFunc' href='{$base_url}/shopping'>목록</a>");
      echo ("
        <div id='goodsContentDiv'>
          {$data['content']}
        </div>
      </div>");

  ?>
</div>
<div id='ReviewContentDiv'>
  <?php
    // 답변 입력
    print $this->render('shopping/writeReviewForm', array(
                                              'pNum'    => $pNum,
                                              'userId'  => $userId,
                                              'nick'    => $nick,
                                              '_token'  => $_token));
    // 답변 컨텐츠 출력
    print $this->render('shopping/showReviewContent', array(
                                              'allData' => $reviewData ));
  ?>
</div>
