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

// $_POST['token']を取得
$token = get_post('token');

// トークンが正しくないとき
if (is_valid_csrf_token($token) === false) {
  // ログインページへ
  redirect_to(LOGIN_URL);
}
// トークンの破棄
delete_csrf_token($token);

// ログイン済でないとき
if(is_logined() === false){
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// ログインユーザデータを取得(連想配列)
$user = get_login_user($db);

// $_POST['order_id']を取得
$order_id = get_post('order_id');

// 購入履歴データを取得(連想配列)
$order = get_order($db, $order_id);

// 購入明細データを取得(二次元連想配列)
$order_details = get_order_details($db, $order_id);

// viewファイル読み込み
include_once VIEW_PATH . 'order_details_view.php';
