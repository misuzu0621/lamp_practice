<?php
// 定数ファイル読み込み
require_once '../conf/const.php';
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';

// セッション開始
session_start();

// ログイン済のとき
if(is_logined() === true){
  // 商品一覧ページへ
  redirect_to(HOME_URL);
}

// viewファイル読み込み
include_once VIEW_PATH . 'login_view.php';