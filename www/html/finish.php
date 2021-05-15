<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品データに関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';
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

// カートデータ取得(二次元連想配列)
$carts = get_user_carts($db, $user['user_id']);

// カートデータ削除が成功でないとき
if(purchase_carts($db, $carts) === false){
  // セッション変数にエラーメッセージを追加
  set_error('商品が購入できませんでした。');
  // カートページへ
  redirect_to(CART_URL);
} 

// カートの合計金額を取得
$total_price = sum_carts($carts);

// viewファイル読み込み
include_once '../view/finish_view.php';