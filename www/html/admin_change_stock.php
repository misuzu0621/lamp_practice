<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品データに関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';

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

// 管理者でないとき
if(is_admin($user) === false){
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// $_POST['item_id']を取得
$item_id = get_post('item_id');
// $_POST['stock']を取得
$stock = get_post('stock');

// 在庫数の更新が成功したとき
if(update_item_stock($db, $item_id, $stock)){
  // セッション変数にメッセージを追加
  set_message('在庫数を変更しました。');
// そうでないとき
} else {
  // セッション変数にエラーメッセージを追加
  set_error('在庫数の変更に失敗しました。');
}

// 管理ページへ
redirect_to(ADMIN_URL);