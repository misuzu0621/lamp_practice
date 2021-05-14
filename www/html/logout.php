<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

// セッション開始
session_start();
// セッション変数を全て削除
$_SESSION = array();
// $paramsにセッションに関する設定を代入
$params = session_get_cookie_params();
// クッキーに保存されているセッションIDを削除
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
// セッションIDを無効化
session_destroy();

// ログインページへ
redirect_to(LOGIN_URL);

