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
// $_POST['changes_to']を取得
$changes_to = get_post('changes_to');

// $changes_toがopenのとき
if($changes_to === 'open'){
  // ステータスを公開に更新
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  // セッション変数にメッセージを追加
  set_message('ステータスを変更しました。');
// $changes_toがcloseのとき
}else if($changes_to === 'close'){
  // ステータスを非公開に更新
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  // セッション変数にメッセージを追加
  set_message('ステータスを変更しました。');
// それ以外のとき
}else {
  // セッション変数にエラーメッセージを追加
  set_error('不正なリクエストです。');
}


// 管理ページへ
redirect_to(ADMIN_URL);