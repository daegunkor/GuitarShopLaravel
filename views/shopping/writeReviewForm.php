
<form action="<?=$base_url?>/shopping/writeReview" method="post">
  <div id="writeReviewDiv">
    <?=$userId."[{$nick}]"?>
    <table id='writeReviewTable' align='center'>
      <tr>
        <td>내용</td>
        <td>
          <textarea name="content" id='reviewTextArea' cols='50'></textarea>
        </td>
        <td> <input type="submit" value="리뷰작성"></td>
      </tr>
    </table>
  <input type="hidden" name="_token" value="<?=$_token?>">
  <input type="hidden" name="pNum" value="<?=$pNum?>">
  <input type="hidden" name="userid" value="<?=$userId?>">
  <input type="hidden" name="nick" value="<?=$nick?>">
  </div>
</form>
