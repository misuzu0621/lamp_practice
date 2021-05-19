<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// カートデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'cart.php';

// セッション開始
session_start();

// ログイン済でないとき
if(is_logined() === false){
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// ログインユーザデータを取得(連想配列)
$user = get_login_user($db);

// 購入履歴データ取得(二次元連想配列)
$orders = get_orders($db, $user);

// トークンの生成
$token = get_csrf_token();

// viewファイル読み込み
include_once VIEW_PATH . 'orders_view.php';
