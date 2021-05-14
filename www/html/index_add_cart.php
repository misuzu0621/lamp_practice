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


// $_POST['item_id']を取得
$item_id = get_post('item_id');

// カートデータ登録、または購入予定数量更新が成功したとき
if(add_cart($db,$user['user_id'], $item_id)){
  // セッション変数にメッセージを追加
  set_message('カートに商品を追加しました。');
// そうでないとき
} else {
  // セッション変数にエラーメッセージを追加
  set_error('カートの更新に失敗しました。');
}

// 商品一覧ページへ
redirect_to(HOME_URL);