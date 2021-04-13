<?php
session_start();
require('../dbconnect.php');
if(!isset($_SESSION['join'])){
    header('location: index.php');
    exit();
}

if(!empty($_POST)){
    $profile_Registration = $db->prepare('INSERT INTO users SET name=?, password=?, image=?, email_address=?, created_at=NOW()');
    $profile_Registration->execute(array($_SESSION['join']['name'], password_hash($_SESSION['join']['password'],PASSWORD_DEFAULT), $_SESSION['join']['image']), $_SESSION['join']['email']);
    unset($_SESSION['join']);
    header('join_done.php');
}
$password_length = strlen($_SESSION['join']['password']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認画面</title>
</head>
<body>
    <div id="head">
        <h1>会員登録</h1>
    </div>
    <div id="content">
        <p>記入した内容を確認してください</p>
        <form action="" method="POST">
            <dl>
                <dt>ニックネーム</dt>
                <dd>
                    <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES); ?>
                </dd>
            </dl>
            <dl>
                <dt>メールアドレス</dt>
                <dd>
                    <?php echo htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES); ?>
                </dd>
            </dl>
            <dl>
                <dt>パスワード</dt>
                <dd>
                    <?php for($i=0; $i<$password_length; $i++){
                        echo '*';
                    }?>
                </dd>
            </dl>
            <dl>
                <dt>画像</dt>
                <?php if($_SESSION['join']['image'] != ''): ?>
                    <img src="../user_icon/<?php echo htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES); ?>">
                <?php endif; ?>
                <dd>
                </dd>
            </dl>
            <div>
                    <a href="index.php?action=rewrite">&lt;&nbsp;書き直す</a>
                    <input type="submit" value="登録する">
            </div>
        </form>
    </div>

</body>
</html>