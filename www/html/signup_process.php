<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// ユーザデータに関する関数ファイル読み込み
require_once MODEL_PATH . 'user.php';

// セッション開始
session_start();

// ログイン済のとき
if(is_logined() === true){
  // 商品一覧ページへ
  redirect_to(HOME_URL);
}

// $_POST['name']を取得
$name = get_post('name');
// $_POST['password']を取得
$password = get_post('password');
// $_POST['password_confirmation']を取得
$password_confirmation = get_post('password_confirmation');

// DB接続
$db = get_db_connect();

try{
  // ユーザデータ登録の結果を取得
  $result = regist_user($db, $name, $password, $password_confirmation);
  // 登録出来ないとき
  if( $result=== false){
    // セッション変数にエラーメッセージを追加
    set_error('ユーザー登録に失敗しました。');
    // サインアップページへ
    redirect_to(SIGNUP_URL);
  }
// 例外発生時
}catch(PDOException $e){
  // セッション変数にエラーメッセージを追加
  set_error('ユーザー登録に失敗しました。');
  // サインアップページへ
  redirect_to(SIGNUP_URL);
}

// セッション変数にメッセージを追加
set_message('ユーザー登録が完了しました。');
// セッション変数にユーザIDを登録
login_as($db, $name, $password);
// 商品一覧ページへ
redirect_to(HOME_URL);