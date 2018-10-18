<!DOCTYPE html>
<html lang = "ja">
	<head>
		<meta charset = "UTF-8">
	</head>
				<form method = "POST">
	<body>
		<form>
			<!--認証コード入力用-->
			<p>メールが送信されました</p>
			<p>仮登録したIDとパスワード、メールで届いた認証コードでログインをしてください</p>
			<p><input type = "text" name ="userid" placeholder ="アカウントID"></p>
			<p><input type = "password" name = "pass"placeholder ="パスワード"></p>
			<p><input type = "password" name ="key2" placeholder ="認証コード" ></p>
			<!--送信ボタン-->
			<input type = "submit" value ="送信"></p>

		</form>
		<?php
			//ログイン
			$dsn = 'ホスト';
			$user = 'ユーザー名';
			$password = 'パスワード';
			$pdo = new PDO($dsn,$user,$password);
			//テーブル作成
			$sql= "CREATE TABLE lMC"//テーブルの作成
			." ("
			. "id INT AUTO_INCREMENT PRIMARY KEY," 
			. "name1 char(32),"
			. "Email1 TEXT,"
			. "userid1 TEXT,"
			. "pass1 TEXT"
			.");";
			$stmt = $pdo->query($sql);
			//認証
			if(!empty($_POST['userid']) and !empty($_POST['pass']) and !empty($_POST['key2'])){
				$sql = 'SELECT * FROM M7';//初期から使っているコメント欄
				$results = $pdo -> query($sql);
				foreach ($results as $row){
					if($row['userid'] ==hash("sha256",$_POST['userid']) and $row['pass'] ==hash("sha512",$_POST['pass']) and $row['key2'] == $_POST['key2']){
						if(strtotime($row['limi']) >= strtotime(date("Y/m/d H:i:s"))){
						$sql = $pdo -> prepare("INSERT INTO lMC (Email1,userid1,name1,pass1) VALUES (:Email1,:userid1,:name1,:pass1)");
						//コメント欄書き込み処理
						$sql -> bindParam(':Email1', $Email, PDO::PARAM_STR);
						$sql -> bindParam(':userid1', $userid, PDO::PARAM_STR);
						$sql -> bindParam(':name1', $name, PDO::PARAM_STR);
						$sql -> bindParam(':pass1', $pass, PDO::PARAM_STR);
						$userid =$row['userid'];
						$name =$row['name'];
						$Email =$row['Email'];
						$pass =$row['pass'];
						$sql -> execute();

						mb_language("Japanese");
						mb_internal_encoding("UTF-8");
						$subject = '登録完了';
						$message =
						"\n"
						."====================================\n"
						."■送信日付:  ".date('Y-m-d H:i:s')."\n"
						."■送信内容：  本日新規登録が完了しました。\n"
						."              認証が完了しました。\n"
						."              引き続き当サイトをご利用ください。 \n"
						."              メール送信テストです。\n"
						."====================================\n"; 
						$headers = "From: root@x74.cocospace.com";
						if(mb_send_mail($Email, $subject, $message,$headers,'-f root@x74.cocospace.com')){
							header("location: mission_7login.php");
						} else {
							echo "メールの送信に失敗しました<br>";
						}
						}else{
							echo '認証期限切れです。手続きを最初からやり直してください。';
						}
					}
				}
			}

		?>

	</body>
</html>