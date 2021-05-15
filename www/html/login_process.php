<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';

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

// ログイン済のとき
if(is_logined() === true){
  // 商品一覧ページへ
  redirect_to(HOME_URL);
}

// $_POST['name']を取得
$name = get_post('name');
// $_POST['password']を取得
$password = get_post('password');

// DB接続
$db = get_db_connect();


// ログインユーザデータ取得、セッション変数にユーザIDを登録
$user = login_as($db, $name, $password);
// 取得出来なかったとき、またはパスワードが正しくないとき
if( $user === false){
  // セッション変数にエラーメッセージを追加
  set_error('ログインに失敗しました。');
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// セッション変数にメッセージを追加
set_message('ログインしました。');
// 管理者のとき
if ($user['type'] === USER_TYPE_ADMIN){
  // 管理ページへ
  redirect_to(ADMIN_URL);
}
// 商品一覧ページへ
redirect_to(HOME_URL);