<?php
    session_start();
    require('dbconnect.php');

    if($_COOKIE['email'] !== ''){
        $email = $_COOKIE['email'];
    }

    if(!empty($_POST)){
        $email =$_POST['email'];
        if($_POST['email'] !== '' && $_POST['password'] !== ''){
            $login = $db->prepare('SELECT * FROM users WHERE email_address=?');
            $login->execute(array($_POST['email']));
            $result = $login->fetch(PDO::FETCH_ASSOC);
            if(password_verify($_POST['password'], $result['password'])){
                $_SESSION['id'] = $result['id'];
                $_SESSION['time'] = time();
                if($_POST['save'] === 'on'){
                    setcookie('email', $_POST['email'], time()+60*60*24*14);
                }
                header('location: index.php');
                exit();
            }else{
                $error['login'] = 'failed';
            }
        }else{
            $error['login'] = 'blank';
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
    <div id="wrap">
        <h1>ログインする</h1>
    </div>
    <div id="content">
        <div id=lead>
            <p>登録したメールアドレスとパスワードを入力してください</p>
            <p><a href="join/index.php">入会手続きをする</a></p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>メールアドレス</dt>
                <dd>
                    <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($email,ENT_QUOTES); ?>"/>
                    <?php if($error['login'] === 'failed'): ?>
                    <p class="error">パスワードが違います</p>
                    <?php endif; ?>
                    <?php if($error['login'] === 'blank'): ?>
                    <p class="error">メールアドレスとパスワードを入力してください</p>
                    <?php endif; ?>
                </dd>
                <dt>パスワード</dt>
                <dd>
                    <input type="password" name="password" size="35" maxlength="255">
                </dd>
                <dd>
                    <input type="checkbox" name="save" value="on">
                    <label for="save">次回からこのメールアドレスを使用する</label>
                </dd>
            </dl>
            <div>
                <input type="submit" value="ログインする">
            </div>
        </form>
    </div>
</body>
</html>