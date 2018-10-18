<?php

session_start();

if(isset($_SESSION[‘userid’]) and isset($_SESSION[‘username’] )){
	header("Location: mission_7talk.php");
exit;
}

?>
<!DOCTYPE html>
<html lang = "ja">
<link rel="stylesheet" href="cf0053.css" >
	<head>
		<meta charset = "UTF-8">
	</head>
	<body>
		<form action = "mission_7N3.php" method = "POST">
		

		<!--名前入力用-->
		<h2>新規登録フォーム</h2>
		<p>Gmailは使用できません。</p>
		<p>パスワード・IDは8桁で登録してください。</p>
		<p>ニックネーム・アカウントID・パスワードは英数字のみの登録になります。</p>
		<p><input type = "text" name ="Email" placeholder ="メールアドレス" ></p>
		<p><input type = "text" name ="name"  placeholder ="ニックネーム" ></p>
		<p><input type = "text" name ="userid" maxlength='8'placeholder ="アカウントID"></p>
		<p><input type = "password" name = "pass" maxlength='8' placeholder ="パスワード">
		<!--送信ボタン-->
		<input type = "submit" value ="送信"></p>
		<?php
		//ログイン
		$dsn = 'ホスト';
		$user = 'ユーザー名';
		$password = 'パスワード';
		$pdo = new PDO($dsn,$user,$password);
		if(!empty($_POST['userid']) and !empty($_POST['name']) and !empty($_POST['pass']) and !empty($_POST['Email'])){
			$sql = 'SELECT * FROM M7';//初期から使っているコメント欄
			$results = $pdo -> query($sql);
			foreach ($results as $row){
				if($row['Email'] == $_POST['Email']){
				echo "このアドレスは既に仮登録されています、メールのURLより認証を済ませてください。";
				$_POST['userid'] ="";
				$_POST['name'] ="";
				$_POST['pass'] ="";
				$_POST['Email'] ="";
				}
			}
		}
		if(!empty($_POST['userid']) and !empty($_POST['name']) and !empty($_POST['pass']) and !empty($_POST['Email'])){
			$sql = 'SELECT * FROM lMC';//初期から使っているコメント欄
			$results = $pdo -> query($sql);
			foreach ($results as $row){
				if($row['Email1'] == $_POST['Email'] or $row['userid1'] == $_POST['userid']){
				echo "このメールアドレスまたはユーザーIDは既登録されています。";
				$_POST['userid'] ="";
				$_POST['name'] ="";
				$_POST['pass'] ="";
				$_POST['Email'] ="";
				}
			}
		}
		?>
	</form>
	<?php
		//ログイン
		$dsn = 'ホスト';
		$user = 'ユーザー名';
		$password = 'パスワード';
		$pdo = new PDO($dsn,$user,$password);
		//テーブル作成
		$sql= "CREATE TABLE M7"
		. "("
		. "id INT AUTO_INCREMENT PRIMARY KEY,"
		. "date timestamp not null default current_timestamp,"
		. "limi timestamp,"
		. "key2 TEXT,"
		. "Email TEXT,"
		. "userid TEXT,"
		. "name char(32),"
		. "pass TEXT"
		.");";
		$stmt = $pdo->query($sql);
	?>
	<?php
	//ログイン
	$dsn = 'ホスト';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn,$user,$password);
	if(!empty($_POST['userid']) and !empty($_POST['name']) and !empty($_POST['pass']) and !empty($_POST['Email'])){
		if(ctype_alnum($_POST['pass']) and $_POST['userid'] and $_POST['name']){
				$sql = $pdo -> prepare("INSERT INTO M7 (key2,Email,limi,userid,name,pass) VALUES (:key2,:Email,:limi,:userid,:name,:pass)");
				//コメント欄書き込み処理
				$sql -> bindParam(':key2', $key2, PDO::PARAM_STR);
				$sql -> bindParam(':Email', $Email, PDO::PARAM_STR);
				$sql -> bindParam(':limi', $limi, PDO::PARAM_STR);
				$sql -> bindParam(':userid', $userid, PDO::PARAM_STR);
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
				//ランダム文字列生成 (英数字)
				//$length: 生成する文字数
				$str = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPUQRSTUVWXYZ';
				$str_r = substr(str_shuffle($str), 0, 5);
				$key2 = $str_r;
				$Email = $_POST['Email'];
				$limi = date('Y/m/d H:i' , strtotime('+30 minute'));
				$userid = hash("sha256",$_POST['userid']);
				$name = $_POST['name'];
				$pass = hash("sha512",$_POST['pass']);
				//文字生成ここまで
				$sql -> execute();
				mb_language("Japanese");
				mb_internal_encoding("UTF-8");
				$subject = '登録確認メール';
				$message =
				"\n"
				."====================================\n"
				."■送信日付:  ".date('Y-m-d H:i:s')."\n"
				."■送信内容：  本日新規登録を受け付けました。\n"
				."              まだ仮登録のため、下記のURLより認証を完了してください。\n"
				."              認証用のサイトURL \n"
				."              $limi までに登録を行ってください。 \n"
				."              認証キーは $key2 です。\n"
				."              メール送信テストです。\n"
				."====================================\n"; 
				$headers = "From: 自分のメールアドレス";
				if(mb_send_mail($Email, $subject, $message,$headers,'-f 自分のメールアドレス')){
					header("location: mission_7OK.php");;
				} else {
				echo "メールの送信に失敗しました<br>";
				}
				}else{
					echo'ニックネームとパスワード、アカウントIDは英字又は、数字以外入力できません';
				}
		}elseif(empty($_POST['userid']) or empty($_POST['name']) or empty($_POST['pass']) or empty($_POST['mail'])){
		echo '<h3>全項目記入後送信ボタンを押してください</h3>';
		}
	?>
	</body>
</html>