<?php
class UserModel extends ExecuteModel {
  // **insert()***
  //http://php.net/manual/kr/function.password-hash.php
  //패스워드의 해쉬 처리 : 암호화
  //http://php.net/manual/kr/datetime.format.php
  //DateTime::format
  public function insert($idInput, $passInput, $nameInput, $nickInput, $hpTotal, $emailTotal) {
    $sql = "INSERT INTO users(id, password, name, nick, hp, email)";
    $sql.= "VALUES(:id, :password, :name, :nick, :hp, :email);";
    $stmt = $this->execute($sql, array(
      ':id'       =>  $idInput,
      ':password' =>  $passInput,
      ':name'     =>  $nameInput,
      ':nick'     =>  $nickInput,
      ':hp'       =>  $hpTotal,
      ':email'    =>  $emailTotal
    ));

    // execute(); 추상 클래스 ExecuteModel의 메소드
  }

  // ***getUserRecord() ***
  public function getUserRecord($user_name) {
    $sql = "SELECT *
          FROM users
          WHERE id = :input_id";

          $userData = $this->getRecord(
                      $sql,
                      array(':input_id' => $user_name));
   // getRecord(); 추상 클래스 ExecuteModel의 메소드
    return $userData;
  }

  public function getUserCount($user_name) {
    $sql = "SELECT count(*) as count
          FROM users
          WHERE id = :input_id";

          $userCount = $this->getRecord(
                      $sql,
                      array(':input_id' => $user_name));
   // getRecord(); 추상 클래스 ExecuteModel의 메소드
    return $userCount['count'];
  }

  public function getNickCount($user_nick) {
    $sql = "SELECT count(*) as count
          FROM users
          WHERE nick = :input_nick";

          $nickCount = $this->getRecord(
                      $sql,
                      array(':input_nick' => $user_nick));
   // getRecord(); 추상 클래스 ExecuteModel의 메소드
    return $nickCount['count'];
  }

  // ***isOverlapUserName() ***
  public function isOverlapUserName($user_name) {
    $sql = "SELECT COUNT(id) as count
            FROM user
            WHERE user_name = :user_name";

    $row = $this->getRecord(
            $sql,
            array(':user_name' => $user_name));
    if($row['count']==='0') { // $user_name의 유저가 미동륵이면
    return true;
  }
      return false;
    }

  // *** getFollowingUser() ***
  public function getFollowingUser($user_id){
    $sql = "SELECT u.*
          FROM user u
          LEFT JOIN followingUser f ON f.following_id = u.id
          WHERE f.user_id = :user_id";

  $follows = $this->getAllRecord(
              $sql,
              array(':user_id' => $user_id));
              return $follows;
    }
  }
?>
