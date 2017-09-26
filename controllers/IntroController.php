<?php
class IntroController extends Controller{
  function indexAction(){
    $index_view = $this->render(array());
    return $index_view;
  }
}
?>
