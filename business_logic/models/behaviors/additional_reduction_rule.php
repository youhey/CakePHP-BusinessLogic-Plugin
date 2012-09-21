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

/**
 * 汎用的な変換ルール
 * 
 * @author IKEDA youhei <youhey.ikeda@gmail.com>
 */
class AdditionalReductionRuleBehavior extends ModelBehavior {

    /** カタカナ変換のオプション */
    const CONVERT_ZENKAKU_KATAKANA = 'KVS';

    /** マルチバイト対応の文字列前後の空白 */
    const MB_TRIM_REGEXP = '(^[[:space:]]+|[[:space:]]+$)';

    /** マルチバイト対応の空白文字列 */
    const MB_SPACE_REGEXP = '[[:space:]]';

    /**
     * 捨て仮名（小書き文字、小さいカタカナ）
     * 
     * @var array
     */
    private $katakanaLowerCase = array(
            'ァ' => 'ア',
            'ィ' => 'イ',
            'ゥ' => 'ウ',
            'ェ' => 'エ',
            'ォ' => 'オ',
            'ヵ' => 'カ',
            /* unsupported */ // 'ㇰ' => 'ク',
            'ヶ' => 'ケ',
            /* unsupported */ // 'ㇱ' => 'シ',
            /* unsupported */ // 'ㇲ' => 'ス',
            'ッ' => 'ツ',
            /* unsupported */ // 'ㇳ' => 'ト',
            /* unsupported */ // 'ㇴ' => 'ヌ',
            /* unsupported */ // 'ㇵ' => 'ハ',
            /* unsupported */ // 'ㇶ' => 'ヒ',
            /* unsupported */ // 'ㇷ' => 'フ',
            /* unsupported */ // 'ㇷ゚' => 'プ',
            /* unsupported */ // 'ㇸ' => 'ヘ',
            /* unsupported */ // 'ㇹ' => 'ホ',
            /* unsupported */ // 'ㇺ' => 'ム',
            'ャ' => 'ヤ',
            'ュ' => 'ユ',
            'ョ' => 'ヨ',
            /* unsupported */ // 'ㇻ' => 'ラ',
            /* unsupported */ // 'ㇼ' => 'リ',
            /* unsupported */ // 'ㇽ' => 'ル',
            /* unsupported */ // 'ㇾ' => 'レ',
            /* unsupported */ // 'ㇿ' => 'ロ',
            'ヮ' => 'ワ',
        );

    /**
     * 文字列の前後から全ての空白（全角スペース対応）を削除
     * 
     * <p>半角スペース、全角スペース、一部の制御文字を削除する。</p>
     * PCRE の文字クラス「space」に含まれる文字
     * <ul>
     * <li>HT</li>
     * <li>LF</li>
     * <li>VT</li>
     * <li>FF</li>
     * <li>CR</li>
     * <li>SP</li>
     * </ul>
     * 
     * @param Model $model モデル
     * @param string $input 入力文字列
     * @return string 前後の空白を削除した文字列
     */
    public function mbTrimSpaces(Model $model, $input) {
        $appEncoding = Configure::read('App.encoding');
        mb_regex_encoding($appEncoding); 

        $trimed = mb_ereg_replace(self::MB_TRIM_REGEXP, '', $input);

        return $trimed;
    }

    /**
     * 文字列から全ての空白（全角スペース対応）を削除
     * 
     * <p>半角スペース、全角スペース、一部の制御文字を削除する。</p>
     * PCRE の文字クラス「space」に含まれる文字
     * <ul>
     * <li>HT</li>
     * <li>LF</li>
     * <li>VT</li>
     * <li>FF</li>
     * <li>CR</li>
     * <li>SP</li>
     * </ul>
     * 
     * @param Model $model モデル
     * @param string $input 入力文字列
     * @return string 全ての空白を削除した文字列
     */
    public function mbStripSpaces(Model $model, $input) {
        $appEncoding = Configure::read('App.encoding');
        mb_regex_encoding($appEncoding); 

        $striped = mb_ereg_replace(self::MB_SPACE_REGEXP, '', $input);

        return $striped;
    }

    /**
     * 半角カタカナを全角カタカナに変換
     * 
     * @param Model $model モデル
     * @param string $hankaku 半角カタカナ
     * @return string 全角カタカナ
     */
    public function toZenkakuKatakana(Model $model, $hankaku) {
        $appEncoding = Configure::read('App.encoding');
        $replaced    = str_replace('-', 'ｰ', $hankaku);
        $zenkaku     = mb_convert_kana($replaced, self::CONVERT_ZENKAKU_KATAKANA, $appEncoding);

        return $zenkaku;
    }

    /**
     * 捨て仮名を大書きのカタカナに変換
     * 
     * @param Model $model モデル
     * @param string $katakana 捨て仮名を変換するカタカナ文字列
     * @return string 捨て仮名を大書きのカタカナに変換した文字列
     */
    public function ignoreKatakanaLowerCase(Model $model, $katakana) {
        $appEncoding = Configure::read('App.encoding');
        mb_regex_encoding($appEncoding); 

        $replaced = $katakana;
        foreach ($this->katakanaLowerCase as $lowercase => $uppercase) {
            $replaced = mb_ereg_replace($lowercase, $uppercase, $replaced); 
        }

        return $replaced;
    }
}
