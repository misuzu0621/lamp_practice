<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');


define('IMAGE_PATH', '/assets/images/');
define('STYLESHEET_PATH', '/assets/css/');
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );

define('DB_HOST', 'mysql');
define('DB_NAME', 'sample');   // MySQLのDB名
define('DB_USER', 'testuser'); // MySQLのユーザ名
define('DB_PASS', 'password'); // MySQLのパスワード
define('DB_CHARSET', 'utf8');  // DBの文字コード

define('SIGNUP_URL', '/signup.php'); // サインアップページ
define('LOGIN_URL', '/login.php');   // ログインページ
define('LOGOUT_URL', '/logout.php'); // ログアウトページ
define('HOME_URL', '/index.php');    // 商品一覧ページ
define('CART_URL', '/cart.php');     // カートページ
define('FINISH_URL', '/finish.php'); // 購入完了ページ
define('ADMIN_URL', '/admin.php');   // 管理ページ

define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');        // 英数字の正規表現
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/'); // 正の整数の正規表現


define('USER_NAME_LENGTH_MIN', 6);       // ユーザ名の最小文字数
define('USER_NAME_LENGTH_MAX', 100);     // ユーザ名の最大文字数
define('USER_PASSWORD_LENGTH_MIN', 6);   // パスワードの最小文字数
define('USER_PASSWORD_LENGTH_MAX', 100); // パスワードの最大文字数

define('USER_TYPE_ADMIN', 1);  // 管理者
define('USER_TYPE_NORMAL', 2); // 一般ユーザ

define('ITEM_NAME_LENGTH_MIN', 1);   // 商品名の最小文字数
define('ITEM_NAME_LENGTH_MAX', 100); // 商品名の最大文字数

define('ITEM_STATUS_OPEN', 1);  // 公開
define('ITEM_STATUS_CLOSE', 0); // 非公開

define('PERMITTED_ITEM_STATUSES', array( // 商品のステータス
  'open' => 1,                           // 公開
  'close' => 0,                          // 非公開
));

define('PERMITTED_IMAGE_TYPES', array( // 画像ファイルの形式
  IMAGETYPE_JPEG => 'jpg',             // JPEG
  IMAGETYPE_PNG => 'png',              // PNG
));