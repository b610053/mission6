<?php

session_start();

if(isset($_SESSION[‘userid’]) and isset($_SESSION[‘username’] )){
	header("Location: mission_7talk.php");
exit;
}

?>
<!DOCTYPE html>
<html lang = "ja">
<link rel="stylesheet" href="mission_7.css" >
	<head>
		<meta charset = "UTF-8">
	</head>
				<form action = "mission_7login.php" method = "POST">
	<body>


  <div class="holder">
    <div class="first"></div>
    <div class="second"></div>
    <div class="third"></div>
<div class="txt">
		<form>
			<!--login-->
			<p>ログインをしてください</p>
			<p><input type = "text" name ="userid1" placeholder ="アカウントID"></p>
			<p><input type = "password" name = "pass1"placeholder ="パスワード"></p>
			<!--送信ボタン-->
			<input type = "submit" name ="login" value ="ログイン"></p>
			<p><input type = "submit" name ="lognew" value ="新規アカウント作成はこちら"></p>
</div>
</div>
		</form>
		<?php
			//ログイン
			$dsn = 'ホスト';
			$user = 'ユーザー名';
			$password = 'パスワード';
			$pdo = new PDO($dsn,$user,$password);
			//認証
			if(!empty($_POST['userid1']) and !empty($_POST['pass1'])and !empty($_POST['login'])){
				$sql = 'SELECT * FROM lMC';
				$results = $pdo -> query($sql);
				foreach ($results as $row){
					if($row['userid1'] ==hash("sha256",$_POST['userid1']) and $row['pass1'] ==hash("sha512",$_POST['pass1'])){
					
					$_SESSION[‘userid’] = $_POST['userid1'];
					$_SESSION[‘username’] = $row['name1'];

					header("location: mission_7talk.php");
					}
				}
			echo "認証失敗";
			}
			//新規登録
			if(!empty($_POST['lognew'])){
			header("location: mission_7N3.php");
			}
		?>

	</body>
</html>