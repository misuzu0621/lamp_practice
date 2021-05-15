<?php
// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'functions.php';
// DBに関する関数ファイル読み込み
require_once MODEL_PATH . 'db.php';

/**
 * ユーザデータを取得(連想配列)
 * @param  obj   $db      DBハンドル
 * @param  str   $user_id ユーザID
 * @return array ユーザデータ(連想配列)
 * @return bool  false
 */
function get_user($db, $user_id){
  // SQL文
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      user_id = ?
    LIMIT 1
  ";
  // パラメータを取得
  $params = array($user_id);
  // SQL文を実行してレコードを取得し返す、例外発生時falseを返す
  return fetch_query($db, $sql, $params);
}

/**
 * ユーザデータを取得(連想配列)
 * @param  obj   $db   DBハンドル
 * @param  str   $name ユーザ名
 * @return array ユーザデータ(連想配列)
 * @return bool  false
 */
function get_user_by_name($db, $name){
  // SQL文
  $sql = "
    SELECT
      user_id, 
      name,
      password,
      type
    FROM
      users
    WHERE
      name = ?
    LIMIT 1
  ";
  // パラメータを取得
  $params = array($name);
  // SQL文を実行してレコードを取得し返す、例外発生時falseを返す
  return fetch_query($db, $sql, $params);
}

/**
 * ログインユーザデータ取得、セッション変数にユーザIDを登録
 * @param  obj   $db       DBハンドル
 * @param  str   $name     ユーザ名
 * @param  str   $password パスワード
 * @return array $user     ログインユーザデータ(連想配列)
 * @return bool  false
 */
function login_as($db, $name, $password){
  // ログインユーザデータ取得
  $user = get_user_by_name($db, $name);
  // 取得出来なかったとき、または、パスワードが正しくないとき
  if($user === false || $user['password'] !== $password){
    // falseを返す
    return false;
  }
  // セッション変数にユーザIDを登録
  set_session('user_id', $user['user_id']);
  // $userを返す
  return $user;
}

/**
 * ログインユーザデータを取得(連想配列)
 * @param  obj   $db
 * @return array ログインユーザデータ(連想配列)
 * @return bool  false
 */
function get_login_user($db){
  // $_SESSION['user_id']を取得
  $login_user_id = get_session('user_id');

  // ログインユーザデータを取得して返す、例外発生時falseを返す
  return get_user($db, $login_user_id);
}

/**
 * ユーザデータ登録
 * @param  obj   $db                    DBハンドル
 * @param  str   $name                  ユーザ名
 * @param  str   $password              パスワード
 * @param  str   $password_confirmation 確認用パスワード
 * @return bool
 */
function regist_user($db, $name, $password, $password_confirmation) {
  // $nameと$passwordの入力値チェックが成功でないとき
  if( is_valid_user($name, $password, $password_confirmation) === false){
    // falseを返す
    return false;
  }
  
  // ユーザデータ登録が成功のときtrue、そうでないときfalseを返す
  return insert_user($db, $name, $password);
}

/**
 * $userが監理者かどうか確認
 * @param  array $user
 * @return bool
 */
function is_admin($user){
  // $user['type']が監理者のときtrueを返す、そうでないときfalseを返す
  return $user['type'] === USER_TYPE_ADMIN;
}

/**
 * ユーザ名とパスワードの入力値チェック
 * @param  str   $name                  ユーザ名
 * @param  str   $password              パスワード
 * @param  str   $password_confirmation 確認用パスワード
 * @return bool
 */
function is_valid_user($name, $password, $password_confirmation){
  // 短絡評価を避けるため一旦代入。
  // $nameの入力値チェックの結果を取得
  $is_valid_user_name = is_valid_user_name($name);
  // $passwordの入力値チェックの結果を取得
  $is_valid_password = is_valid_password($password, $password_confirmation);
  // $is_valid_user_nameと$is_valid_passwordがともにtrueのときtrue、そうでないときfalseを返す
  return $is_valid_user_name && $is_valid_password ;
}

/**
 * ユーザ名の入力値チェック
 * @param  str   $name ユーザ名
 * @return bool  $is_valid
 */
function is_valid_user_name($name) {
  // $is_validにtrueを代入
  $is_valid = true;
  // $nameの文字数がユーザ名の最小文字数以上かつ最大文字数以下でないとき
  if(is_valid_length($name, USER_NAME_LENGTH_MIN, USER_NAME_LENGTH_MAX) === false){
    // セッション変数にエラーメッセージを追加
    set_error('ユーザー名は'. USER_NAME_LENGTH_MIN . '文字以上、' . USER_NAME_LENGTH_MAX . '文字以内にしてください。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $nameが英数字の正規表現にマッチしなかったとき
  if(is_alphanumeric($name) === false){
    // セッション変数にエラーメッセージを追加
    set_error('ユーザー名は半角英数字で入力してください。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $is_validを返す
  return $is_valid;
}

/**
 * パスワードの入力値チェック
 * @param  str   $password              パスワード
 * @param  str   $password_confirmation 確認用パスワード
 * @return bool  $is_valid
 */
function is_valid_password($password, $password_confirmation){
  // $is_validにtrueを代入
  $is_valid = true;
  // $passwordの文字数がパスワードの最小文字数以上かつ最大文字数以下でないとき
  if(is_valid_length($password, USER_PASSWORD_LENGTH_MIN, USER_PASSWORD_LENGTH_MAX) === false){
    // セッション変数にエラーメッセージを追加
    set_error('パスワードは'. USER_PASSWORD_LENGTH_MIN . '文字以上、' . USER_PASSWORD_LENGTH_MAX . '文字以内にしてください。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $passwordが英数字の正規表現にマッチしなかったとき
  if(is_alphanumeric($password) === false){
    // セッション変数にエラーメッセージを追加
    set_error('パスワードは半角英数字で入力してください。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $passwordと$password_confirmationが等しくないとき
  if($password !== $password_confirmation){
    // セッション変数にエラーメッセージを追加
    set_error('パスワードがパスワード(確認用)と一致しません。');
    // $is_validにfalseを代入
    $is_valid = false;
  }
  // $is_validを返す
  return $is_valid;
}

/**
 * ユーザデータ登録
 * @param  obj   $db       DBハンドル
 * @param  str   $name     ユーザ名
 * @param  str   $password パスワード
 * @return bool
 */
function insert_user($db, $name, $password){
  // SQL文
  $sql = "
    INSERT INTO
      users(name, password)
    VALUES (?, ?);
  ";
  // パラメータを取得
  $params = array($name, $password);
  // SQL文の実行結果を返す
  return execute_query($db, $sql, $params);
}

