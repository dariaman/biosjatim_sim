<?php
 /* Connect to an ODBC database using driver invocation */

    $dsn = 'mysql:dbname=chat;host=localhost:3362;charset=utf8';
    $user = 'jatimsql';
    $password = 'ved+-18bios';

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
        $pdo->setAttribute(PDO::ATTR_TIMEOUT, 5);
    } catch (PDOException $e) {
        echo 'Connection failed:'.$e->getMessage();
    }
