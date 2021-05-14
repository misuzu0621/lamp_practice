<?php

/**
 * $varの情報を表示
 * @param  str   $var
 */
function dd($var){
  // $varの情報を表示
  var_dump($var);
  // プログラムを終了
  exit();
}

/**
 * $urlのブラウザを表示
 * @param  str   $url
 */
function redirect_to($url){
  // $urlのブラウザを表示する
  header('Location: ' . $url);
  // 現在のプログラムを終了
  exit;
}

/**
 * GET値取得
 * @param  str   $name        配列キー
 * @return str   $_GET[$name] GET値
 * @return str   ''
 */
function get_get($name){
  // $_GET[$name]がセットされていてかつnullでないとき
  if(isset($_GET[$name]) === true){
    // $_GET[$name]を返す
    return $_GET[$name];
  };
  // ''を返す
  return '';
}

/**
 * POST値取得
 * @param  str   $name         配列キー
 * @return str   $_POST[$name] POST値
 * @return str   ''
 */
function get_post($name){
  // $_POST[$name]がセットされていてかつnullでないとき
  if(isset($_POST[$name]) === true){
    // $_POST[$name]を返す
    return $_POST[$name];
  };
  // ''を返す
  return '';
}

/**
 * ファイル値取得
 * @param  str   $name          配列キー
 * @return array $_FILES[$name] ファイル値
 * @return array array()
 */
function get_file($name){
  // $_FILES[$name]がセットされていてかつnullでないとき
  if(isset($_FILES[$name]) === true){
    // $_FILES[$name]を返す
    return $_FILES[$name];
  };
  // array()を返す
  return array();
}

/**
 * セッション値取得
 * @param  str   $name            配列キー
 * @return str   $_SESSION[$name] セッション値
 * @return str   ''
 */
function get_session($name){
  // $_SESSION[$name]がセットされていてかつnullでないとき
  if(isset($_SESSION[$name]) === true){
    // $_SESSION[$name]を返す
    return $_SESSION[$name];
  };
  // ''を返す
  return '';
}

/**
 * セッション値登録
 * @param  str   $name  配列キー
 * @param  str   $value
 */
function set_session($name, $value){
  // $_SESSION[$name]に$valueを代入
  $_SESSION[$name] = $value;
}

/**
 * セッション変数にエラーメッセージを追加
 * @param  str   $error エラーメッセージ
 */
function set_error($error){
  // $_SESSION['__errors'][]に$errorを代入
  $_SESSION['__errors'][] = $error;
}

/**
 * エラーメッセージ取得
 * @return array $errors エラーメッセージ(配列)
 * @return array array()
 */
function get_errors(){
  // $_SESSION['__errors']を取得
  $errors = get_session('__errors');
  // $errorsが''のとき
  if($errors === ''){
    // array()を返す
    return array();
  }
  // $_SESSION['__errors']にarray()を代入
  set_session('__errors',  array());
  // $errorsを返す
  return $errors;
}

/**
 * セッション変数にエラーメッセージがあるか確認
 * @return bool
 */
function has_error(){
  // $_SESSION['__errors']がセットされていてかつnullでない、かつ数が0でないときtrue、そうでないときfalseを返す
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

/**
 * セッション変数にメッセージを追加
 * @param  str   $message メッセージ
 */
function set_message($message){
  // $_SESSION['__messages'][]に$messageを代入
  $_SESSION['__messages'][] = $message;
}

/**
 * メッセージ取得
 * @return array $messages メッセージ(配列)
 * @return array array()
 */
function get_messages(){
  // $_SESSION['__messages']を取得
  $messages = get_session('__messages');
  // $messagesが''のとき
  if($messages === ''){
    // array()を返す
    return array();
  }
  // $_SESSION['__messages']にarray()を代入
  set_session('__messages',  array());
  // $messagesを返す
  return $messages;
}

/**
 * ログイン済か確認
 * @return bool
 */
function is_logined(){
  // $_SESSION['user_id']がセットされていてかつnullでないときtrue、そうでないときfalseを返す
  return get_session('user_id') !== '';
}

/**
 * 画像ファイル名を作成
 * @param  array $file 画像ファイル
 * @return str   画像ファイル名
 * @return str   ''
 */
function get_upload_filename($file){
  // 画像ファイルチェックが正しくないとき
  if(is_valid_upload_image($file) === false){
    // ''を返す
    return '';
  }
  // 画像ファイルの形式を取得
  $mimetype = exif_imagetype($file['tmp_name']);
  // 画像ファイルの拡張子を取得
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  // 画像ファイル名(20文字のランダムな文字列.拡張子)を返す
  return get_random_string() . '.' . $ext;
}

/**
 * $length文字のランダムな文字列を作成
 * @param  int   $length = 20
 * @return str   substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length)
 */
function get_random_string($length = 20){
  // $length文字のランダムな文字列を作成
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

/**
 * 画像ファイルの移動
 * @param  array $image    画像ファイル
 * @param  str   $filename 画像ファイル名
 * return  bool
 */
function save_image($image, $filename){
  // $image['tmp_name']をIMAGE_DIR . $filenameに移動が成功したときtrue、失敗したときfalseを返す
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

/**
 * 画像ファイルの削除
 * @param  str   $filename 画像ファイル名
 * @return bool
 */
function delete_image($filename){
  // IMAGE_DIR.$filenameが存在するとき
  if(file_exists(IMAGE_DIR . $filename) === true){
    // IMAGE_DIR.$filenameを削除
    unlink(IMAGE_DIR . $filename);
    // trueを返す
    return true;
  }
  // falseを返す
  return false;
  
}



/**
 * 文字数チェック
 * @param  str   $string
 * @param  int   $minimum_length               最小文字数
 * @param  int   $maximum_length = PHP_INT_MAX 最大文字数
 * @return bool
 */
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  // $lengthに$stringの文字数を代入
  $length = mb_strlen($string);
  // $lengthが最小文字数以上かつ最大文字数以下のときtrue、そうでないときfalseを返す
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

/**
 * 英数字の正規表現にマッチしているか確認
 * @param  str   $string
 * @return bool
 */
function is_alphanumeric($string){
  // $stringが英数字の正規表現にマッチしたときtrue、そうでないときfalseを返す
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

/**
 * 正の整数の正規表現にマッチしているか確認
 * @param  str   $string
 * @return bool
 */
function is_positive_integer($string){
  // $stringが正の整数の正規表現にマッチしたときture、そうでないときfalseを返す
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

/**
 * パターンチェック
 * @param  str   $string
 * @param  str   $format
 * @return bool
 */
function is_valid_format($string, $format){
  // $stringが$formatのパターンにマッチしたときtrue、そうでないときfalseを返す
  return preg_match($format, $string) === 1;
}


/**
 * 画像ファイルチェック
 * @param  array $image 画像ファイル
 * @return bool
 */
function is_valid_upload_image($image){
  // $image['tmp_name']がPOST通信でアップロードされていないとき
  if(is_uploaded_file($image['tmp_name']) === false){
    // セッション変数にエラーメッセージを追加
    set_error('ファイル形式が不正です。');
    // falseを返す
    return false;
  }
  // 画像ファイルの形式を取得
  $mimetype = exif_imagetype($image['tmp_name']);
  // 画像ファイルの形式が正しくないとき
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    // セッション変数にエラーメッセージを追加
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    // falseを返す
    return false;
  }
  // trueを返す
  return true;
}

/**
 * 特殊文字をHTMLエンティティに変換
 * @param  str   $str 変換前文字列
 * @return str   変換後文字列
 */
function h($str) {
  // 特殊文字をHTMLエンティティに変換して返す
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}