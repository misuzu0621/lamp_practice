<?php
// 設定ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';
// 商品データに関する関数ファイル読み込み
require_once MODEL_PATH . 'item.php';

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

// ログインユーザデータ取得(連想配列)
$user = get_login_user($db);

// 管理者でないとき
if(is_admin($user) === false){
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// $_POST['name']を取得
$name = get_post('name');
// $_POST['price']を取得
$price = get_post('price');
// $_POST['status']を取得
$status = get_post('status');
// $_POST['stock']を取得
$stock = get_post('stock');

// $_FILES['image']を取得
$image = get_file('image');

// 入力値チェックを行い、新規商品追加、画像ファイルの移動が成功したとき
if(regist_item($db, $name, $price, $stock, $status, $image)){
  // セッション変数にメッセージを追加
  set_message('商品を登録しました。');
  // そうでないとき
}else {
  // セッション変数にエラーメッセージを追加
  set_error('商品の登録に失敗しました。');
}


// 管理ページへ
redirect_to(ADMIN_URL);