<?php
    try{
        $db = new PDO('mysql:dbname=reco;host=db;charset=utf8;unix_socket=/tmp/mysql.sock','root','root');
    }catch(PDOException $e) {
        echo 'DB接続エラー: ' . $e->getMessage();
    }