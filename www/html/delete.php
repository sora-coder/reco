<?php
    session_start();
    require('dbconnect.php');

    $sentences = $db->prepare('SELECT * FROM message WHERE id=?');
    $sentences->execute(array($_REQUEST['id']));
    $sentence = $sentences->fetch();

    if($_SESSION['id'] !== $sentence['user_id']){
        header('location: index.php');
        exit();
    }

    if(!empty($_POST)){
        if(isset($_POST['delete'])){
            $delete = $db->prepare('UPDATE message SET deleted=1 WHERE id=?');
            $delete->bindParam(1, $sentence['id'], PDO::PARAM_INT);
            $delete->execute();
            header('location: index.php');
            exit();
        }elseif(isset($_POST['back'])){
            header('location: index.php');
            exit();
        }
    }
    var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>削除</title>
</head>
<body>
    <div>
        <p><?php echo htmlspecialchars($sentence['id'], ENT_QUOTES), '.', htmlspecialchars($sentence['message'], ENT_QUOTES); ?></p>
    </div>
    <div>
        <p>削除しますか？</p>
    </div>
    <div>
        <form action="" method="post">
            <input type="submit" name="delete" value="削除する">
            <input type="submit" name="back" value="戻る">
        </form>
    </div>
</body>
</html>