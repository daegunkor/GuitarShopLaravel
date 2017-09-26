<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
<link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
<link rel="stylesheet" href="/css/buying.css?var=<?=filemtime('/css/buying.css')?>">
<link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div id="goodsTopBar">
      &nbsp;&nbsp;기타 장터
    </div>
    <div id="goodsList">
      <?php
        // 게시물 표시(테이블)
        for($i = $start; $i <= $number; $i++){
          $width = 300;
          $height = 450;
          $brLine = 4;
          if(isset($data[$i])){
            $imgDir = $dataDir.$data[$i]['num']."/data/".$data[$i]['mainImg'];
            echo("

              <div class='goodsListDiv'>
                <a href='{$base_url}/shopping/goodsContent?pNum={$data[$i]['num']}'><img src='{$imgDir}' width='100%' height='80%'></a>
                <div class='goodsTitle'>
                  {$data[$i]['title']}
                </div>
                <div class='goodsPrice'>
                {$data[$i]['price']}원
                </div>
              </div>
            ");
          }
        }
      ?>
      <br>
      <div id="goodsRegistDiv">
          <a href='<?=$base_url?>/shopping/registGoodsForm' id='goodsRegistBtn'>상품등록</a>
      </div>
    </div>
    <div id="goodsPageDiv">
        <?php
          // 게시물 페이지(인덱스)
          // ◀이전 12<b><u>3</u></b>45다음▶
          $frontArea = 2;
          $backArea = 2;
          $startPage = 1;
          $endPage = $totalPageCnt;
          // 이전 페이지가 2개를 초과하지 않으면 이전 버튼 없음 1..
          if($curPage > $startPage){
            $goFront = $curPage-1;
            echo "<a href='{$base_url}/shopping?page={$goFront}'>◀이전 </a>";
          }

          // 현재 페이지로부터 앞뒤로 2개씩 번호 출력
          for($i = $startPage; $i <= $endPage; $i++){
            if($i == $curPage)
              echo "<b><u>$i</u></b>";
            else if(($curPage-$frontArea) <= $i && $i <= ($curPage+$backArea))
              echo "<a href='{$base_url}/shopping?page={$i}'>{$i}</a>";
          }
          // 다음 페에지가 2개를 초과하지 않으면 다음 버튼 없음 ..endPage
          if($endPage > $curPage){
            $goBack = $curPage+1;
            echo "<a href='{$base_url}/shopping?page={$goBack}'> 다음▶</a>";
          }
        ?>
    </div>
  </body>
</html>
