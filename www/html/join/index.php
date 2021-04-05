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
    if(strlen($_post['password']) < 4 && strlen($_POST['password']) > 20){
        $error['password'] = 'length';
    }
    if(!preg_match("/[a-zA-Z0-9]/", $_POST['password'])){
        $error['password'] = 'string';
    }
    $filename = $_FILES['image']['name'];
    if(!empty($filename)){
        $extension = substr($filename, -3);
        if($extension != 'jpg' && $extension != 'gif' && $extension != 'png'){
            $error['image'] = 'type';
        }
    }
    if(empty($error)){
        $image = date('YmdHis') . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], '../user_icon/' . $image);
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
        header('location: /join/check.php');
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
        <form action="" method="post" enctype="multipart/form-data">
            <dl>
                <dt>ニックネーム<span>必須</span></dt>
                <dd>
                    <input type="text" name="name" size35 maxlength="255" value="<?php echo htmlspecialchars($_post['name'],ENT_QUOTES) ?>">
                    <?php if($error['name'] === 'blank'): ?>
                        <p class="error">※ニックネームを入力してください</p>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl>
                <dt>メールアドレス<span>必須</span></dt>
                <dd>
                    <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_post['email'],ENT_QUOTES); ?>">
                    <?php if($error['email'] === 'blank'): ?>
                        <p class="error">※メールアドレスを入力してください</p>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl>
                <dt>パスワード[半角英数字4~20文字]</dt>
                <dd>
                    <input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'],ENT_QUOTES); ?>">
                    <?php if($error['password'] === 'blank'): ?>
                        <p class="error">※パスワードを入力してください</p>
                    <?php elseif($error['password'] === 'length'): ?>
                        <p class="error">※4文字以上20文字以下で入力してください</p>
                    <?php elseif($error['password'] === 'string'): ?>
                        <p class="error">※半角英数字のみで入力してください</p>
                    <?php endif; ?>
                </dd>
            </dl>
            <dl>
                <dt>画像</dt>
                <dd>
                    <input type="file" name="image" size="35">
                    <?php if($error['image'] === 'type'): ?>
                        <p class="error">※「.jpg」または「.gif」「.png」の画像を指定してください</p>
                    <?php endif; ?>
                    <?php if(!empty($error)): ?>
                        <p class="error">※画像を改めて指定してください</p>
                    <?php endif; ?>
                </dd>
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