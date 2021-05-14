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

// ログイン済でないとき
if(is_logined() === false){
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();
// ログインユーザデータを取得(連想配列)
$user = get_login_user($db);

// $_POST['cart_id']を取得
$cart_id = get_post('cart_id');
// $_POST['amount']を取得
$amount = get_post('amount');

// 購入予定数量更新が成功したとき
if(update_cart_amount($db, $cart_id, $amount)){
  // セッション変数にメッセージを追加
  set_message('購入数を更新しました。');
// そうでないとき
} else {
  // セッション変数にエラーメッセージを追加
  set_error('購入数の更新に失敗しました。');
}

// カートページへ
redirect_to(CART_URL);