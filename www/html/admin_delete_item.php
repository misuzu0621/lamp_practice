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
  // LOGIN_URLへ
  redirect_to(LOGIN_URL);
}

// DB接続
$db = get_db_connect();

// ログインユーザデータ取得(連想配列)
$user = get_login_user($db);

// 管理者でないとき
if(is_admin($user) === false){
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// $_POST['item_id']を取得
$item_id = get_post('item_id');


// 商品・画像ファイルの削除が成功したとき
if(destroy_item($db, $item_id) === true){
  // セッション変数にメッセージを追加
  set_message('商品を削除しました。');
// そうでないとき
} else {
  // セッション変数にエラーメッセージを追加
  set_error('商品削除に失敗しました。');
}



// 管理者ページへ
redirect_to(ADMIN_URL);