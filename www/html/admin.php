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

// ログインユーザデータ取得(連想配列)
$user = get_login_user($db);

// 管理者でないとき
if(is_admin($user) === false){
  // ログインページへ
  redirect_to(LOGIN_URL);
}

// 商品データ取得(二次元連想配列)
$items = get_all_items($db);
// viewファイル読み込み
include_once VIEW_PATH . '/admin_view.php';
