<?php
session_start();
require('../dbconnect.php');
if(!empty($_POST)){
    if($_POST['name'] === ''){
        $error['name'] = 'blank';
    }
    if($_POST['email'] === ''){
        $error['email'] = 'blank';
    }
    if($_POST['password'] === ''){
        $error['password'] = 'blank';
    }
    $filename = $_FILES['image']['name'];
    if(!empty($filename)){
        $extension = substr($filename, -3);
        if($extension != 'jpg' && $extension != 'gif' && $extension != 'png'){
            $error['image'] = 'type';
        }
    }
    if(empty($error)){
        $image = date(YmdHis) . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../user_icon/' . $image);
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('location: check.php');
        exit();
    }
}





?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
</head>
<body>
    <div id="head">
        <h1>会員登録</h1>
    </div>
    <div id="content">
        <p>必要事項を入力してください</p>
        <form action="" method="POST" enctype="multipart/form-data">
            <dl>
                <dt>ニックネーム<span>必須</span></dt>
                <dd><input type="text" name="name" size35 maxlength="255"></dd>
            </dl>
            <dl>
                <dt>メールアドレス<span>必須</span></dt>
                <dd><input type="text" name="email" size="35" maxlength="255"></dd>
            </dl>
            <dl>
                <dt>パスワード</dt>
                <dd><input type="password" name="password" size="10" maxlength="20"></dd>
            </dl>
            <dl>
                <dt>画像</dt>
                <dd><input type="file" name="image" size="35"></dd>
            </dl>
            <div>
                <input type="submit" value="入力内容を確認する">
            </div>
        </form>
        <div>
            <form action="" method="POST">
                <input type="submit" value="ゲストアカウントでログインする">
            </form>
        </div>
    </div>
</body>
</html>