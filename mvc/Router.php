<?php
class Router{
    //Routing 정보를 저장하는 프로파티
    protected $_convertedRoutes;

    // ***Constructor**
    // $routedef:Routing 정보를 정의한 배열(APP클래스에서 정의)
    public function __construct($routedef){
        $this->_convertedRoutes = $this->routeConverter($routedef);
    }
    // 정규표현식 : https://opentutorials.org/module/622
    //             https://opentutorials.org/course/62/5141
    // *** routeConverter()***
    // 1> Routing 정보 정의한 배열 $routedef의 key를 $url에 저장
    // 2> $url에서 선두의 '/'를 제거(ltrim()) http://php.net/manual/kr/function.ltrim.pp
    // 3> 2>처리결과를 '/' 기준으로 분할하여 $converts배열에 저장
    // http://php.net/manual/kr/function.explode.php//
    // 4> $converts 배열 요소들을 하나씩 $i => convert 처리
    // 5> $convert의 선두에 ':' 있으면 제거하여 $bar에 저장
    // 6> $convert에 '(?<'.$bar'>[^/]+)'형태로 저장
    // 7> 다시 $converts 배열 구성한 후 $pattern 문자열 작성 http://php.net/manual/kr/function.implode.php
    // 8> $converted에 key($pattern):val($par-Array)로 저장

    // ex>$url: /user/:username
    // 2> /user/:user_name ===> user/:username
    // 3> $converts에 user,: user_name 분할 저장
    // 7> $converts[0] = user, $converts[1] = '(?<user_name>[^/]+)' ====>
    // $pattern = user/(?<user_name>)[^/]+)
    // 8> $converted[user/(?<user_name>[^/]+)] <= Array([controller]=>blog, [action]=>user)

    /*
            $routedef: Array(
                [/]=>Array(
                    [controller]=>blog
                    [action]=>index
                )
            [/status/post]=>Array(
                [controller]=>blog
                [action]=>post
            )
            [/user/:user_name]=>Array(
                [controller]=>blog
                [action]=>user
            )
            ...
        )
        안쪽 foreach문 완료시
        $converts
            Array([0]=> )
            Array([0]=> status [1] => post)
            Array([0]=> user [1]=>(?<user_name>[^/]+) )
        $converted: Array(
        [/]=>Array(
        [controller]=>blog
        [action]=>index
        )
[/status/post] =>Array(
        [controller] => blog
        [action] => post
        )
  [/user/(?<user_name>[^/]+)]=>Array(
          [controller]=>blog
          [action]=>user
    )
    )
$_convertedRoutes <=== $converted
*/

public function routeConverter($routedef){
  $converted = array();
  foreach ($routedef as $url => $par){
    $converts = explode('/', ltrim($url,'/'));
    foreach($converts as $i => $convert){
      if(0 ===strpos($convert, ':')){
        $bar = substr($convert,1);
        $convert = '(?<'.$bar.'>[^/]+)';
      }
      $converts[$i] = $convert;
    }
    $pattern = '/'.implode('/',$converts);
    $converted[$pattern]=$par;
  }
  return $converted;


}
//***getRouteParams()***
//http://php.net/manual/kr/functionn.preg-match.php
//preg_match(정규표현 탐색패턴,검색대상문자열,매칭결과)
//     매칭결과[0]-매칭 전체결과 , 매칭결과[1]=첫번째 매칭결과
//http://php.net/manual/kr/function.array-merge.php
//1>$path의 선두에 '/'가 없으면 선두에 '/'추가
//2>$_convertedRoutes에 저장되어있는 Routing정보를 읽어옴
//3> $pattern => $par (key => value)형태에서 $pattern과 일치하는 부분을
// $path 에서 찾아 $p_match에 저장
// $pattern값이 /status/post인경우 $p_match값 :[0] => /status/post
// $pattern값이 /user/(?<user_name>[^/]+)인 경우 $p_match 값:[0] =>/user/:user_name
  //                                       [user_name] =>:user_name
  //                                        [1] => :user_name
//4> $par 에 $p_match에 추가 (합병, 같은 키값은 나중에 나온값으로 업데이트)
// $pattern값이 /status/post인경우 $par값 : [controller] => blog
//                                          [action] => user
//                                          [0]=> /status/post
// $pattern값이 /user/(?<user_name>[^/]+)인경우 $par값 : [controller] => blog
//                                    [action] =>user
//                                    [0] => /user/:user_name
//                                    [user_name] => :user_name
//                                    [1] => :user_name

  public function getRouteParams($path){
    if('/' !== substr($path,0,1)){
      $path = '/'.$path;
    }
    foreach ($this ->_convertedRoutes as $pattern => $par){
      if(preg_match('#^'.$pattern.'$#',$path,$p_match)){
        //$pattern을 반드시 만족하도록 ^시작종료$
        $par = array_merge($par, $p_match);
        return $par;
      }
    }
    return false;
  }
}
