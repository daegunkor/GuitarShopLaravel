<?php echo $this->escape($idInput)?>
<table id='memRegistTable' align='center'>
  <tr>
    <td class='memRegistfirstTd'>아이디</td>
    <td class='memRegistTd'>
      <input type="text" id='input_id' name="idInput" value="<?php echo $this->escape($idInput);?>">
      <a class='dupliBtn' href='#' onclick = 'idDupliCheck()'>중복확인</a>
    </td>
  </tr>
  <tr>
    <td class='memRegistfirstTd'>비밀번호</td>
    <td class='memRegistTd'><input type="password" id='input_pass' name="passInput" value="<?=
$passInput?>"></td>
  </tr>
  <tr>
    <td class='memRegistfirstTd'>비밀번호 확인</td>
    <td class='memRegistTd'><input type="password" id='input_pass_confirm' name="passConfirm" value="<?=$passConfirm?>"></td>
  </tr>
  <tr>
    <td class='memRegistfirstTd'>이름</td>
    <td class='memRegistTd'><input type="text" id='input_name' name="nameInput" value="<?=$nameInput?>"></td>
  </tr>
  <tr>
    <td class='memRegistfirstTd'>닉네임</td>
    <td class='memRegistTd'>
      <input type="text" id='input_nick' name="nickInput" value="<?=$nickInput?>">
      <a class='dupliBtn' href='#' onclick = 'nickDupliCheck()'>중복확인</a>
    </td>
  </tr>
  <tr>
    <td class='memRegistfirstTd'>휴대폰</td>
    <td class='memRegistTd'>
      <select id='input_hp1' name="hpInput1">
        <option value="010">010</option>
        <option value="011">011</option>
        <option value="016">016</option>
        <option value="017">017</option>
        <option value="018">018</option>
        <option value="019">019</option>
      </select>
      - <input type="text" id='input_hp2' name="hpInput2" value="<?=$hpInput2?>">
      - <input type="text" id='input_hp3' name="hpInput3" value="<?=$hpInput3?>">
    </td>
  </tr>
  <tr>
    <td class='memRegistfirstTd'>이메일</td>
    <td class='memRegistTd'>
      <input type="text" id='input_email1' name="emailInput1" value="<?=$emailInput1?>"> @
      <select name="emailInputSelect" onchange="setEmail()">
        <option value="naver">네이버</option>
        <option value="daum">다음</option>
        <option value="yahoo">야후</option>
        <option value="google">구글</option>
        <option value="ldghome">대건홈</option>
        <option value="cyworld">싸이월드</option>
        <option value="typing">직접입력</option>
      </select>
      <input type="text" id="input_email2" name="emailInput2" readonly="readonly" value="naver.com">
    </td>
  </tr>
</table>
