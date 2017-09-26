<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">
    <link rel="stylesheet" href="/css/menus.css?var=<?=filemtime('/css/menus.css')?>">
    <link rel="stylesheet" href="/css/question.css?var=<?=filemtime('/css/question.css')?>">
    <link rel="stylesheet" href="/css/inputForm.css?var=<?=filemtime('/css/inputForm.css')?>">


  </head>
  <body>
    <div id="QnATopBar">
      &nbsp;&nbsp;묻고 답하기
    </div>
    <div id="QnABoard">
      <?php
              echo ("
                <table id='QnABoardTable' align='center'>
                  <tr class='QnABoardTableTr' id='QnATableField'>
                    <td id='QnANum'>번호</td>
                    <td id='QnATitle'>제목</td>
                    <td id='QnADate'>날짜</td>
                    <td id='QnAHit'>조회수</td>
                    <td id='QnAWriter'>글쓴이</td>
                    <td id='QnAFile'>첨부</td>
                  </tr>
              ");

              for($i = $start; $i <= $number; $i++){
                if(isset($data[$i])){
                  echo("
                    <tr class='QnABoardTableTr' id='QnATableRecordTr'>
                      <td class='QnATableRecord'>{$data[$i]['num']}</td>
                      <td class='QnATableRecord'><a href='{$base_url}/board/boardContent?qNum={$data[$i]['num']}'>{$data[$i]['title']}</a></td>
                      <td class='QnATableRecord'>{$data[$i]['regist_day']}</td>
                      <td class='QnATableRecord'>{$data[$i]['hit']}</td>
                      <td class='QnATableRecord'>{$data[$i]['nick']}</td>
                     ");
                  if($data[$i]['norFile'] != "")
                    echo ("
                        <td class='QnATableRecord'><a href='{$base_url}/board/fileDownload?fileName={$data[$i]['norFile']}&qNum={$data[$i]['num']}'><img src='/img/disk.jpg' width='20' height='20'></a></td>
                      </tr>
                    ");
                  else
                    echo ("
                        <td></td>
                      </tr>
                    ");
                }
              }
              echo ("</table>");

        // 게시물 페이지(인덱스)
      ?>
      <div id="QnAWriteDiv">
        <a href='<?=$base_url?>/board/writeBoard' id='questionWriteBtn'>글쓰기</a>
      </div>
    </div>
    <div id="QnAPageDiv">
      <?php
      // 페이지 인덱스 생성
        // ◀이전 12<b><u>3</u></b>45다음▶
        $frontArea = 2;
        $backArea = 2;
        // 이전 페이지가 2개를 초과하지 않으면 이전 버튼 없음 1..
        if($curPage > 1){
          $goFront = $curPage-1;
          echo "<a href=$base_url/board?page={$goFront}>◀이전 </a>";
        }

        // 현재 페이지로부터 앞뒤로 2개씩 번호 출력
        for($i = 1; $i <= $totalPageCnt; $i++){
          if($i == $curPage)
            echo "<b><u>$i</u></b>";
          else if(($curPage-$frontArea) <= $i && $i <= ($curPage+$backArea))
            echo "<a href=$base_url/board?page={$i}> {$i} </a>";
        }
      ?>
    </div>
  </body>
</html>
