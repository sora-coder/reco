<?php
    session_start();
    require('dbconnect.php');

    if(isset($_SESSION['id']) && $_SESSION['time']+60*60 > time()){
        $_SESSION['time'] = time();

        $users = $db->prepare('SELECT * FROM users WHERE id=?');
        $users->execute(array($_SESSION['id']));
        $user = $users->fetch();
    }else{
        header('location: login.php');
        exit();
    }

    if(!empty($_POST)){
        if($_POST['message'] !== ''){
            if(!isset($_REQUEST['res'])){
                $_POST['reply_message_id'] = 0;
            }
            $message = $db->prepare('INSERT INTO message SET user_id=?, message=?, reply_message_id=?, created=NOW()');
            $message->execute(array(
            $user['id'],
            $_POST['message'],
            $_POST['reply_message_id']
            ));
            header('location: index.php');
            exit();
        }
    }

    $counts = $db->query('SELECT COUNT(*) AS cnt FROM message');
    $cnt = $counts->fetch();
    $maxpage = ceil($cnt['cnt'] / 5);
    $page = $_REQUEST['page'];
    if($_REQUEST['page'] === ''){
        $page = $maxpage;
    }
    $page = max($page, 1);
    $page = min($page, $maxpage);
    $start = ($page - 1) * 5;
    $posts = $db->prepare('SELECT * FROM (SELECT u.name, u.image, m.* FROM users u, message m WHERE u.id=m.user_id  ORDER BY m.created DESC LIMIT ?, 5) AS A ORDER BY created');
    $posts->bindParam(1, $start, PDO::PARAM_INT);
    $posts->execute();

    if(isset($_REQUEST['res'])){
        $response = $db->prepare('SELECT u.name, u.image, m.* FROM users u, message m WHERE u.id=m.user_id AND m.id=?');
        $response->execute(array($_REQUEST['res']));
        $table = $response->fetch();
        $reference_message = '>>' . $table['id'] . '.  ' . $table['message'] . '(' . $table['name'] . ')';
    }
    //     ini_set('display_errors', 1);
    // $db->setAttribute(pdo::ATTR_ERRMODE,pdo::ERRMODE_EXCEPTION);
    // $db->setAttribute(pdo::ATTR_EMULATE_PREPARES, false);
    // echo "<br>";
    // var_dump($table);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>top</title>
</head>
<body>
    <div id="wrap">
        <header>
            <h1>reco</h1>
        </header>
        <div id="content">
            <div>
                <?php foreach($posts as $post): ?>
                    <div class="message">
                        <span><?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?></span>
                        <?php if($post['image' !== '']): ?>
                        <img src="user_icon/<?php echo htmlspecialchars($post['image'], ENT_QUOTES), '"'; ?><?php else: ?><img src="icon/default_icon.png" <?php endif; ?> width="30" height="30" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>"><span class="name">[<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>]</span>
                        <p white-space="pre-wrap"><?php echo htmlspecialchars($post['message'], ENT_QUOTES); ?></p>
                        <p><span>[<a href="index.php?res=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">返信</a>]</span><span class="day"><?php htmlspecialchars($post['created'], ENT_QUOTES); ?>[<a href="delete.php?id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">削除</a>]</span></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="linear-navigation">
                <?php if($page < $maxpage): ?>
                <a href="index.php?page=<?php echo htmlspecialchars($page + 1, ENT_QUOTES); ?>">前へ</a>
                <?php endif; ?>
                <span>&ensp;|&ensp;</span>
                <?php if($page > 1): ?>
                <a href="index.php?page=<?php echo htmlspecialchars($page - 1, ENT_QUOTES); ?>">次へ</a>
                <?php endif; ?>
            </div>
            <div class="add_post">
                <form action="" method="post">
                    <dl>
                        <dt><?php echo htmlspecialchars($user['name'],ENT_QUOTES); ?>さんメッセージを入力してください</dt>
                        <dt><?php echo htmlspecialchars($reference_message, ENT_QUOTES); ?></dt>
                        <dd>
                            <textarea name="message" cols="50" rows="5"></textarea>
                            <input type="hidden" name="reply_message_id" value="<?php echo htmlspecialchars($_REQUEST['res'], ENT_QUOTES) ?>">
                        </dd>
                    </dl>
                    <div>
                        <input type="submit" value="投稿する">
                    </div>
                </form>
            </div>
            <div class="logout">
                <a href="logout.php">ログアウト</a>
            </div>
        </div>

    </div>

</body>
</html>