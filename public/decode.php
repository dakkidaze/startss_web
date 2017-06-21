<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['key']) && isset($_POST['code'])) {

  $key = base64_encode($_POST['key']);
  $code = base64_decode($_POST['code']);
  $key = str_replace('=','', $key);
  $code = str_replace($key,'',$code);
  echo base64_decode($code);
  die();
}
?>

<html>
<body>
  解碼工具
<form method="post">
  Key: <input type="text" name="key"></input><br/>
  Code: <input type="text" name="code"></input><br/>
  <input type="submit" name="submit" value="解碼"></input><br/>
</form>
</body>
</html>
