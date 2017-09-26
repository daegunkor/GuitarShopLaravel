<?php
class MyPageController extends Controller{
  function myPageContentAction(){
    $userId = $this->_session->get('userid');
    $buyListData = $this->_connect_model->get('Shop')->getBuyListInfo($userId);
    $wishListData = $this->_connect_model->get('Shop')->getWishListInfo($userId);
    // 장바구니 총 가격
    $priceSum = 0;
    foreach($wishListData as $row){
        $priceSum += $row['price'];
    }

    $myPageContent_view = $this->render(array(
      'userId'        => $userId,
      'buyListData'   => $buyListData,
      'wishListData'  => $wishListData,
      'priceSum'      => $priceSum
    ));
    return $myPageContent_view;
  }
}
?>
