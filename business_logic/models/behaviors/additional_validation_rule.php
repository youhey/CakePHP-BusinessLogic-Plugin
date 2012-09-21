<?php
/**
 * ビジネスロジックの汎用的なライブラリ
 * 
 * PHP versions >= 5.2
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * 
 * @since   BusinessLogic 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/** TextSjis class. */
App::import('Vendor', 'BusinessLogic.TextSjis', array('file' => 'TextSjis.php'));

/** Security class. */
App::import('Core', 'Security');

/**
 * バリデーションルールの拡張
 * 
 * @author IKEDA youhei <youhey.ikeda@gmail.com>
 */
class AdditionalValidationRuleBehavior extends ModelBehavior {

    /** メールアドレスのルールを正規表現で定義 */
    const 
        EMAIL_LOOSERULE = '/^(?:[*+!.&#$|\'\\%\/0-9a-z^_`{}=?~-]+@(?:[0-9a-z-]+\.)+(?:[a-z]{2,4}|museum|travel))$/im';

    /** 電話番号のルールを正規表現で定義 */
    const 
        TELEPHONE_JP = '/^(?:0(?:\d{1}-\d{4}|\d{2}-\d{3}|\d{3}-\d{2}|\d{4}-\d{1}|\d{5})-\d{4}|0\d{9})$/m', 
        CELLPHONE_JP = '/^(?:0[789]0-(?:\d{3}-\d{5}|\d{4}-\d{4})|0[789]0\d{8})$/m', 
        IP_PHONE_JP  = '/^(?:050-(?:\d{3}-\d{5}|\d{4}-\d{4})|050\d{8})$/m';

    /** 郵便番号のルールを正規表現で定義 */
    const POSTCODE_JP = '/^(?:\d{3}-\d{4}|\d{7})$/m';

    /** シフトJISの文字コード */
    const SJIS_ENCODING = 'SJIS-win';

    /** 年数で許容する上限 */
    const MAXIMUM_YEARS = 120;

    /**
     * ひらがなかをチェックする正規表現
     * 
     * - 0x e3 81 81 = ぁ
     * - 0x e3 81 82 = あ
     * - 0x e3 81 83 = ぃ
     * - 0x e3 81 84 = い
     * - 0x e3 81 85 = ぅ
     * - 0x e3 81 86 = う
     * - 0x e3 81 87 = ぇ
     * - 0x e3 81 88 = え
     * - 0x e3 81 89 = ぉ
     * - 0x e3 81 8a = お
     * - 0x e3 81 8b = か
     * - 0x e3 81 8c = が
     * - 0x e3 81 8d = き
     * - 0x e3 81 8e = ぎ
     * - 0x e3 81 8f = く
     * - 0x e3 81 90 = ぐ
     * - 0x e3 81 91 = け
     * - 0x e3 81 92 = げ
     * - 0x e3 81 93 = こ
     * - 0x e3 81 94 = ご
     * - 0x e3 81 95 = さ
     * - 0x e3 81 96 = ざ
     * - 0x e3 81 97 = し
     * - 0x e3 81 98 = じ
     * - 0x e3 81 99 = す
     * - 0x e3 81 9a = ず
     * - 0x e3 81 9b = せ
     * - 0x e3 81 9c = ぜ
     * - 0x e3 81 9d = そ
     * - 0x e3 81 9e = ぞ
     * - 0x e3 81 9f = た
     * - 0x e3 81 a0 = だ
     * - 0x e3 81 a1 = ち
     * - 0x e3 81 a2 = ぢ
     * - 0x e3 81 a3 = っ
     * - 0x e3 81 a4 = つ
     * - 0x e3 81 a5 = づ
     * - 0x e3 81 a6 = て
     * - 0x e3 81 a7 = で
     * - 0x e3 81 a8 = と
     * - 0x e3 81 a9 = ど
     * - 0x e3 81 aa = な
     * - 0x e3 81 ab = に
     * - 0x e3 81 ac = ぬ
     * - 0x e3 81 ad = ね
     * - 0x e3 81 ae = の
     * - 0x e3 81 af = は
     * - 0x e3 81 b0 = ば
     * - 0x e3 81 b1 = ぱ
     * - 0x e3 81 b2 = ひ
     * - 0x e3 81 b3 = び
     * - 0x e3 81 b4 = ぴ
     * - 0x e3 81 b5 = ふ
     * - 0x e3 81 b6 = ぶ
     * - 0x e3 81 b7 = ぷ
     * - 0x e3 81 b8 = へ
     * - 0x e3 81 b9 = べ
     * - 0x e3 81 ba = ぺ
     * - 0x e3 81 bb = ほ
     * - 0x e3 81 bc = ぼ
     * - 0x e3 81 bd = ぽ
     * - 0x e3 81 be = ま
     * - 0x e3 81 bf = み
     * - 0x e3 82 80 = む
     * - 0x e3 82 81 = め
     * - 0x e3 82 82 = も
     * - 0x e3 82 83 = ゃ
     * - 0x e3 82 84 = や
     * - 0x e3 82 85 = ゅ
     * - 0x e3 82 86 = ゆ
     * - 0x e3 82 87 = ょ
     * - 0x e3 82 88 = よ
     * - 0x e3 82 89 = ら
     * - 0x e3 82 8a = り
     * - 0x e3 82 8b = る
     * - 0x e3 82 8c = れ
     * - 0x e3 82 8d = ろ
     * - 0x e3 82 8e = ゎ
     * - 0x e3 82 8f = わ
     * - 0x e3 82 90 = ゐ
     * - 0x e3 82 91 = ゑ
     * - 0x e3 82 92 = を
     * - 0x e3 82 93 = ん
     * - 0x e3 82 94 = （『う』に濁点） is not hiragana
     * - 0x e3 82 95 = （小文字の『か』） is not hiragana
     * - 0x e3 82 96 = （小文字の『け』） is not hiragana
     * - 0x e3 82 9b = ゛（濁点） is not hiragana
     * - 0x e3 82 9c = ゜（半濁点）is not hiragana
     * - 0x e3 82 9d = ゝ（平仮名繰返し記号） is not hiragana
     * - 0x e3 82 9e = ゞ（平仮名繰返し記号濁点） is not hiragana
     * - 0x e3 83 bc = ー（長音）
     * - 0x e3 83 bd = ヽ is not hiragana
     * - 0x e3 83 be = ヾ is not hiragana
     * - 0x e3 80 80 = 全角スペース
     */
    const HIRAGANA = '/^(?:\xe3(?:\x81[\x81-\xbf]|\x82[\x80-\x93]|\x83\xbc|\x80\x80))*$/m';

    /**
     * カタカナをチェックする正規表現
     * 
     * - 0x e3 82 a1 = ァ
     * - 0x e3 82 a2 = ア
     * - 0x e3 82 a3 = ィ
     * - 0x e3 82 a4 = イ
     * - 0x e3 82 a5 = ゥ
     * - 0x e3 82 a6 = ウ
     * - 0x e3 82 a7 = ェ
     * - 0x e3 82 a8 = エ
     * - 0x e3 82 a9 = ォ
     * - 0x e3 82 aa = オ
     * - 0x e3 82 ab = カ
     * - 0x e3 82 ac = ガ
     * - 0x e3 82 ad = キ
     * - 0x e3 82 ae = ギ
     * - 0x e3 82 af = ク
     * - 0x e3 82 b0 = グ
     * - 0x e3 82 b1 = ケ
     * - 0x e3 82 b2 = ゲ
     * - 0x e3 82 b3 = コ
     * - 0x e3 82 b4 = ゴ
     * - 0x e3 82 b5 = サ
     * - 0x e3 82 b6 = ザ
     * - 0x e3 82 b7 = シ
     * - 0x e3 82 b8 = ジ
     * - 0x e3 82 b9 = ス
     * - 0x e3 82 ba = ズ
     * - 0x e3 82 bb = セ
     * - 0x e3 82 bc = ゼ
     * - 0x e3 82 bd = ソ
     * - 0x e3 82 be = ゾ
     * - 0x e3 82 bf = タ
     * - 0x e3 82 c0 = ダ
     * - 0x e3 82 c1 = チ
     * - 0x e3 82 c2 = ヂ
     * - 0x e3 82 c3 = ッ
     * - 0x e3 82 c4 = ツ
     * - 0x e3 82 c5 = ヅ
     * - 0x e3 82 c6 = テ
     * - 0x e3 82 c7 = デ
     * - 0x e3 82 c8 = ト
     * - 0x e3 82 c9 = ド
     * - 0x e3 82 ca = ナ
     * - 0x e3 82 cb = ニ
     * - 0x e3 82 cc = ヌ
     * - 0x e3 82 cd = ネ
     * - 0x e3 82 ce = ノ
     * - 0x e3 82 cf = ハ
     * - 0x e3 82 d0 = バ
     * - 0x e3 82 d1 = パ
     * - 0x e3 82 d2 = ヒ
     * - 0x e3 82 d3 = ビ
     * - 0x e3 82 d4 = ピ
     * - 0x e3 82 d5 = フ
     * - 0x e3 82 d6 = ブ
     * - 0x e3 82 d7 = プ
     * - 0x e3 82 d8 = ヘ
     * - 0x e3 82 d9 = ベ
     * - 0x e3 82 da = ペ
     * - 0x e3 82 db = ホ
     * - 0x e3 82 dc = ボ
     * - 0x e3 82 dd = ポ
     * - 0x e3 82 de = マ
     * - 0x e3 82 df = ミ
     * - 0x e3 82 e0 = ム
     * - 0x e3 82 e1 = メ
     * - 0x e3 82 e2 = モ
     * - 0x e3 82 e3 = ャ
     * - 0x e3 82 e4 = ヤ
     * - 0x e3 82 e5 = ュ
     * - 0x e3 82 e6 = ユ
     * - 0x e3 82 e7 = ョ
     * - 0x e3 82 e8 = ヨ
     * - 0x e3 82 e9 = ラ
     * - 0x e3 82 ea = リ
     * - 0x e3 82 eb = ル
     * - 0x e3 82 ec = レ
     * - 0x e3 82 ed = ロ
     * - 0x e3 82 ee = ヮ
     * - 0x e3 82 ef = ワ
     * - 0x e3 82 f0 = ヰ
     * - 0x e3 82 f1 = ヱ
     * - 0x e3 82 f2 = ヲ
     * - 0x e3 82 f3 = ン
     * - 0x e3 82 f4 = ヴ
     * - 0x e3 82 f5 = （小文字の『カ』） is not katakana
     * - 0x e3 82 f6 = （小文字の『ケ』） is not katakana
     * - 0x e3 82 f7 = （『ワ』に濁点） is not katakana
     * - 0x e3 82 f8 = （『ヰ』に濁点） is not katakana
     * - 0x e3 82 f9 = （『ヱ』に濁点） is not katakana
     * - 0x e3 82 fa = （『ヲ』に濁点） is not katakana
     * - 0x e3 82 9b = ゛（濁点） is not katakana
     * - 0x e3 82 9c = ゜（半濁点）is not katakana
     * - 0x e3 82 9d = ゝ（平仮名繰返し記号） is not katakana
     * - 0x e3 82 9e = ゞ（平仮名繰返し記号濁点） is not katakana
     * - 0x e3 83 bc = ー（長音）
     * - 0x e3 83 bd = ヽ is not katakana
     * - 0x e3 83 be = ヾ is not katakana
     * - 0x e3 80 80 = 全角スペース
     */
    const KATAKANA = '/^(?:\xe3(?:\x82[\xa1-\xbf]|\x83[\x80-\xb4]|\x83\xbc|\x80\x80))*$/m';

    /**
     * 文字列がメールアドレスかをチェック
     *
     * <p>CakePHP 純正の正規表現では一部のアドレスに対して厳正すぎる。<br />
     * モバイルやgmailなど、アットマーク前のdotなどは許容したい。<br />
     * 単純なメールアドレスの正規表現を用意して代用します。</p>
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean メールアドレスとして妥当であればTRUE
     */
    public function email(Model $model, $check) {
        $lineText = $this->lf2space($this->extract($check));
        $isEmail  = (bool)preg_match(self::EMAIL_LOOSERULE, $lineText);

        return $isEmail;
    }

    /**
     * 電話番号をチェック
     * 
     * <p>電話番号は、ハイフンのありなし、どちらも正しい番号とします。<br />
     * 主に家庭電話・携帯電話・IP電話などに対応しています。</p>
     * <p>フリーダイヤルなどには対応していません。</p>
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean 正しい電話番号であればTRUE
     */
    public function telephone(Model $model, $check) {
        $lineText    = $this->lf2space($this->extract($check));
        $isTelephone = (bool)preg_match(self::TELEPHONE_JP, $lineText)
                       || (bool)preg_match(self::CELLPHONE_JP, $lineText)
                       || (bool)preg_match(self::IP_PHONE_JP, $lineText);

        return $isTelephone;
    }

    /**
     * 郵便番号をチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean 正しい郵便番号であればTRUE
     */
    public function postcode(Model $model, $check) {
        $lineText   = $this->lf2space($this->extract($check));
        $isPostcode = (bool)preg_match(self::POSTCODE_JP, $lineText);

        return $isPostcode;
    }

    /**
     * ひらがなをチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean ひらがなのみであればTRUE
     */
    public function hiragana(Model $model, $check) {
        $lineText   = $this->lf2space($this->extract($check));
        $isHiragana = (bool)preg_match(self::HIRAGANA, $lineText);

        return $isHiragana;
    }

    /**
     * カタカナをチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean カタカナのみであればTRUE
     */
    public function katakana(Model $model, $check) {
        $lineText   = $this->lf2space($this->extract($check));
        $isKatakana = (bool)preg_match(self::KATAKANA, $lineText);

        return $isKatakana;
    }

    /**
     * UTF-8文字列にシフトJISの機種依存が含まれているかをチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean 機種依存文字が存在しなければTRUE
     */
    public function notSjisGaiji(Model $model, $check) {
        $input     = $this->extract($check);
        $encoding  = Configure::read('App.encoding');
        $sjistext  = mb_convert_encoding($input, self::SJIS_ENCODING, $encoding);
        $isWithout = !TextSjis::hasSjisGaiji($sjistext) 
                     && !TextSjis::hasWebCode($sjistext);

        return $isWithout;
    }

    /**
     * 2つの入力が一致するかをチェック
     * 
     * @param  AppModel $model  モデル
     * @param  array    $check  検証する値の連想配列
     * @param  string   $suffix 比較する2つ目の項目名につけたSUFFIX
     * @return boolean  2つの入力が一致すればTRUE
     */
    public function compareConfirmation(Model $model, $check, $suffix) {
        $fieldName    = key($check);
        $checkValue   = null;
        $confirmation = null;
        $targetName   = $fieldName.$suffix;

        if ($fieldName !== null) {
            if (isset($model->data[$model->alias][$fieldName])) {
                $checkValue = $model->data[$model->alias][$fieldName];
            }
            if (isset($model->data[$model->alias][$targetName])) {
                $confirmation = $model->data[$model->alias][$targetName];
            }
        }
        $isMatched = ($checkValue !== null)
                     && ($confirmation !== null)
                     && ($checkValue === $confirmation);

        return $isMatched;
    }

    /**
     * ハッシュ化されたパスワードの入力がEMPTYかをチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean パスワードの入力があればTRUE
     */
    public function notEmptyPassword(Model $model, $check) {
        $input      = $this->extract($check);
        $empty      = Security::hash('', null, true);
        $isNotEmpty = ($input !== $empty);

        return $isNotEmpty;
    }

    /**
     * マスタを主キーの値で検索して、データが存在するかをチェック
     * 
     * @param  Model  $model モデル
     * @param  array  $check 検証する値の連想配列
     * @param  string $alias モデルの名前
     * @return boolean success
     */
    public function existsRelation(Model $model, $check, $alias) {
        $input  = $this->extract($check);
        $object = ClassRegistry::init($alias);
        if ($object instanceof Model) {
            $conditions = array("{$object->alias}.{$object->primaryKey}" => $input);
            $query      = array('conditions' => $conditions, 
                                'recursive' => -1, 
                                'callbacks' => false);
            $numrows    = $object->find('count', $query);
        } else {
            $message = "Error validated at authorization in {$alias}: ExistsRelation";
            $this->log($message, LOG_ERROR);
        }
        $object = null;

        $exists = ($numrows > 0);

        return $exists;
    }

    /**
     * 日付の（グレゴリオ暦の）妥当性をチエック
     * 
     * <p>strtotime メソッドが存在しない日付を補正してしまうバグあり <br/>
     * 「2013-02-29」は「2013-03-01」、「2012-04-31」は「2012-05-01」</p>
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean 妥当であれば TRUE
     */
    public function isDate(Model $model, $check) {
        $date      = $this->extract($check);
        $timestamp = strtotime($date);

        $valid = null;
        if ($timestamp !== false) {
            $year  = date('Y', $timestamp);
            $month = date('n', $timestamp);
            $day   = date('j', $timestamp);
            $valid = checkdate($month, $day, $year);
        }
        $verification = ($valid === true);

        return $verification;
    }

    /**
     * 日付の「年数」が有効範囲かをチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @param  mixed $oldestYear 年数の有効範囲
     * @return boolean 年数が有効範囲内であれば TRUE
     */
    public function oldestYear(Model $model, $check, $oldestYear = null) {
        $date      = $this->extract($check);
        $timestamp = strtotime($date);

        if ($oldestYear === null) {
            $oldestYear = self::MAXIMUM_YEARS;
        }

        $valid = null;
        if ($timestamp !== false) {
            $year    = (int)date('Y', $timestamp);
            $current = (int)date('Y', $_SERVER['REQUEST_TIME']);
            $valid   = (($current - $oldestYear) <= $year);
        }
        $verification = ($valid === true);

        return $verification;
    }

    /**
     * 日付が現在よりも過去かをチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean 現在より過去であれば TRUE
     */
    public function lessThanToday(Model $model, $check) {
        $date      = $this->extract($check);
        $timestamp = strtotime($date);

        $valid = null;
        if ($timestamp !== false) {
            $checkDate   = (int)date('Ymd', $timestamp);
            $currentDate = (int)date('Ymd', $_SERVER['REQUEST_TIME']);
            $valid       = ($checkDate <= $currentDate);
        }
        $verification = ($valid === true);

        return $verification;
    }

    /**
     * 有効期限の日付が有効か（過ぎていないか）をチェック
     * 
     * @param  Model $model モデル
     * @param  array $check 検証する値の連想配列
     * @return boolean 有効であれば TRUE
     */
    public function greaterThanExpiration(Model $model, $check) {
        $date      = $this->extract($check);
        $timestamp = strtotime($date);

        $future = null;
        if ($timestamp !== false) {
            $checkDate   = (int)date('Ym01', $timestamp);
            $currentDate = (int)date('Ym01', $_SERVER['REQUEST_TIME']);
            $future      = ($checkDate >= $currentDate);
        }
        $verification = ($future === true);

        return $verification;
    }

    /**
     * バリデーションの実行対象を取り出す
     * 
     * @param  array $input バリデーションの対象
     * @return string バリデーションの実行対象
     */
    private function extract($input) {
        $return = '';
        if (is_array($input)) {
            $buf = array_shift($input);
            if (is_string($buf)) {
                $return = $buf;
            }
        }

        return $return;
    }

    /**
     * 改行文字を半角スペースに置換
     * 
     * <p>改行を含んだテキストを正しく検証できるよう、半角スペースに置換</p>
     * 
     * @param  string $value 改行を含む文字列
     * @return string 改行を半角スペースに置換した文字列
     */
    private function lf2space($value) {
        $replaced = str_replace(array("\r\n", "\n\r", "\n", "\r"), ' ', $value);

        return $replaced;
    }
}
