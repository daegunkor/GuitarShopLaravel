<?php
foreach($allData as $data){
  echo ("
    <table align='center' id='reviewTable'>
      <tr>
        <td class='reviewContentfirstTd'>항목</td>
        <td>글쓴이 : {$data['id']}[{$data['nick']}] | 날짜 : {$data['regist_day']}
  ");
  if($session->get('userid') == $data['id'])
    echo(" | <a href='{$base_url}/shopping/deleteReview?id={$data['id']}&date={$data['regist_day']}'>삭제</a>");
  echo("
        </td>
      </tr>
      <tr>
        <td class='reviewContentfirstTd'>내용</td>
        <td>{$data['content']}<td>
      </tr>
    </table>
  ");
}
?>
