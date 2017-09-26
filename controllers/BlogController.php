<?php
class BlogController extends Controller{
  protected $_authentication = array('post'); //login필요한 action정의
  const POST = 'status/post';
  const FOLLOW = 'account/follow';


  public function indexAction(){

    $index_view = $this->render(array());

    return $index_view;
  }
}
 ?>
