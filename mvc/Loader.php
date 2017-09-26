<?php
class Loader{
  protected $_directories = array();  // autoload 대상 Directory를 저장하는 property
  public function regDirectory($dir) {
    $this->_directories[] = $dir;
    //array_push($this->_directories,$dir);
    //http://php.net/manual/kr/function.array-push.php
  }
  public function register(){
    spl_autoload_register(array($this, 'requireClsFile'));
    //http://php.net/manual/kr/function.spl-autoload-register.php
  }
  public function requireClsFile($class){

    foreach($this->_directories as $dir){
      $file = $dir . '/' . $class . '.php';
      if(is_readable($file)){
        //http://php.net/manual/kr/function.is-readable.php
        require $file;
        return;
      }
    }
  }
}
 ?>