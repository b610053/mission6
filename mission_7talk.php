<?php

session_start();

if(!isset($_SESSION[‘userid’]) or !isset($_SESSION[‘username’] )){
	header("Location: mission_7login.php");
exit;
}
?>

<!DOCTYPE html>
<html lang = "ja">
	<form action = "mission_7talk.php" method = "POST">
	<head>
	<meta charset = "UTF-8">
	<title>意見交換掲示板</title>
	<script src="mycalendar.js" charset="UTF-8" defer></script>
	<link rel="stylesheet" href="mypage.css">
	</head>
	<body>
	<header>
		<h1>共有したい情報を載せよう！</h1>
		<!--投稿内容保存-->
		<?php
		//ログイン
		$dsn = 'ホスト';
		$user = 'ユーザー名';
		$password = 'パスワード';
		$pdo = new PDO($dsn,$user,$password);
		//テーブル作成
		$sql= "CREATE TABLE talk"//テーブルの作成
		." ("
		. "id INT AUTO_INCREMENT PRIMARY KEY,"
		. "userid text," 
		. "name char(32),"
		. "message TEXT,"
		. "date timestamp not null default current_timestamp"
		.");";
		$stmt = $pdo->query($sql);
		if(!empty($_POST['message']) and !empty($_POST['ok'])){
			$sql = $pdo -> prepare("INSERT INTO talk (name, message,userid) VALUES (:name,:message,:userid)");//コメント欄書き込み処理
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':message', $message, PDO::PARAM_STR);
			$sql -> bindParam(':userid', $userid, PDO::PARAM_STR);
			$name = $_SESSION[‘username’];
			$message = $_POST['message'];
			$userid = $_SESSION[‘userid’];
			$sql -> execute();
		}
		?>
	</header>

	<div id="sidebar">
		<?php
		echo "ようこそ<br>\n"." $_SESSION[‘username’]<br>\n<br>\n";
		echo "今日は<br>\n".date(Y年n月j日D)."<br>\n<br>\n";
		?>
		<p><input type = "submit" name ="lout" value ="ログアウト"></p>
		<?php
		if ( isset( $_POST[ 'lout' ] ) ){
		unset($_SESSION[‘username’] );
		unset($_SESSION[‘userid’] );
		header("Location: mission_7login.php");
		}
		?>
		
	</div>

	<div id="sid">
		<h3>コメント投稿フォーム</h3>
		<p>投稿</p>
		<!--コメント入力用-->
		<p><textarea name ="message" placeholder ="メッセージ" ></textarea></p>
		<!--投稿ボタン-->
		<p><input type = "submit" name = "ok" value ="投稿"></p>

	</div>

	<article>
	<?php
	//ログイン
	$dsn = 'ホスト';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn,$user,$password);
	//message表示
	$sql = 'select * from talk ORDER BY id DESC';//初期から使っているコメント欄
	$results = $pdo -> query($sql);
	foreach ($results as $row){
		echo "<br>\n";
		echo $row['id'].' ';
		echo "<p1>"."投稿日:".$row['date']."</p1>".' ';
		echo "名前:".$row['name'].' ';
		echo "ID:".$row['userid']."<br>\n";
		echo $row['message']."<br>\n";
	}
	?>





	</article>

	</body>
	</form>
</html>