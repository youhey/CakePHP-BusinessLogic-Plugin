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

class TestAdditionalReductionRuleModel extends CakeTestModel
{
    public
        $useTable = false, 
        $actsAs   = array('BusinessLogic.AdditionalReductionRule');
}

/**
 * 拡張した変換モジュールのテストケース
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class AdditionalReductionRuleBehaviorTestCase extends CakeTestCase
{

    private 
        $env = array();

    public function startTest() {
        $this->env = $_SERVER;
        $this->Model = new TestAdditionalReductionRuleModel;
    }
    public function endTest() {
        $_SERVER = $this->env;
        $this->Model = null;
        ClassRegistry::flush();
    }

    public function test：文字列の前方から半角スペースを削除() {
        $前に半角スペースあり = '     ego cogito, ergo sum';

        $result   = $this->Model->mbTrimSpaces($前に半角スペースあり);
        $expected = 'ego cogito, ergo sum';
        $this->assertIdentical($expected, $result);
    }

    public function test：文字列の後方から半角スペースを削除() {
        $後ろに半角スペースあり = 'ego cogito, ergo sum     ';

        $result   = $this->Model->mbTrimSpaces($後ろに半角スペースあり);
        $expected = 'ego cogito, ergo sum';
        $this->assertIdentical($expected, $result);
    }

    public function test：文字列の前後から半角スペースを削除() {
        $前後に半角スペースあり = '     ego cogito, ergo sum     ';

        $result   = $this->Model->mbTrimSpaces($前後に半角スペースあり);
        $expected = 'ego cogito, ergo sum';
        $this->assertIdentical($expected, $result);
    }

    public function test：文字列の前後から制御文字を削除() {
        // PCRE の文字クラス「space」は、「HT」「LF」「VT」「FF」「CR」「SP」です。
        $controlCharacters = array(
                'HT' => chr(9),
                'LF' => chr(10),
                'VT' => chr(11),
                'FF' => chr(12),
                'CR' => chr(13),
                'SP' => chr(32),
            );
        $オリジナル文字列 = '吾輩は猫である';
        foreach ($controlCharacters as $説明 => $制御文字) {
            $制御文字あり = "{$制御文字}{$制御文字}{$オリジナル文字列}{$制御文字}{$制御文字}";

            $result   = $this->Model->mbTrimSpaces($制御文字あり);
            $expected = '吾輩は猫である';
            $this->assertIdentical($expected, $result, "制御文字を削除（{$説明}）");
        }
    }

    public function test：文字列の前方から全角スペースを削除() {
        $前に全角スペースあり = '　　　　　吾輩は猫である';

        $result   = $this->Model->mbTrimSpaces($前に全角スペースあり);
        $expected = '吾輩は猫である';
        $this->assertIdentical($expected, $result);
    }

    public function test：文字列の後方から全角スペースを削除() {
        $後ろに全角スペースあり = '吾輩は猫である　　　　　';

        $result   = $this->Model->mbTrimSpaces($後ろに全角スペースあり);
        $expected = '吾輩は猫である';
        $this->assertIdentical($expected, $result);
    }

    public function test：文字列の前後から全角スペースを削除() {
        $前後に全角スペースあり = '　　　　　吾輩は猫である　　　　　';

        $result   = $this->Model->mbTrimSpaces($前後に全角スペースあり);
        $expected = '吾輩は猫である';
        $this->assertIdentical($expected, $result);
    }

    public function test：文字列の前後に混在したスペースを削除() {
        $前後に全角スペースあり = "\t\r\n 　吾輩は猫である\t\r\n 　";

        $result   = $this->Model->mbTrimSpaces($前後に全角スペースあり);
        $expected = '吾輩は猫である';
        $this->assertIdentical($expected, $result);
    }
    public function test：文章中のスペースは削除しないで、文字列の前後のスペースだけ削除() {
        $前後に全角スペースあり = "\t\t　吾　輩 は\r\n猫\t\tで　あ る     \r\n\r\n";

        $result   = $this->Model->mbTrimSpaces($前後に全角スペースあり);
        $expected = "吾　輩 は\r\n猫\t\tで　あ る";
        $this->assertIdentical($expected, $result);
    }

    public function test：文字列の全角スペースを削除() {
        $全角スペースあり = 'ニュー速で　やるお';

        $result   = $this->Model->mbStripSpaces($全角スペースあり);
        $expected = 'ニュー速でやるお';
        $this->assertIdentical($expected, $result);
    }

    public function test：多様なスペースが混在した縦書きから、文字列のスペースを削除() {
        $混在スペースあり = '縦読み職人からのアドバイス    
　　　　　　　　　　　　　　 詩はじめて書きました。                                     
　　　　　　　　　　　　　　 はじめまして X です。元彼は体育会系なんです。              
　　　　　　　　　　　　　　 縦の人間関係に疲れてしまって・・・。                       
　　　　　　　　　　　　　　 にどと恋なんかって・・・嘘。すぐに新しい恋を見つけるっす   
　　　　　　　　　　　　　　 読んでくれて嬉しいです。　Zさんに少しは近づけたかなぁ      
　　　　　　　　　　　　　　 めったに詩は書かへんけど、書いてすっきりしました。         
';

        $result   = $this->Model->mbStripSpaces($混在スペースあり);
        $expected = '縦読み職人からのアドバイス詩はじめて書きました。はじめましてXです。元彼は体育会系なんです。縦の人間関係に疲れてしまって・・・。にどと恋なんかって・・・嘘。すぐに新しい恋を見つけるっす読んでくれて嬉しいです。Zさんに少しは近づけたかなぁめったに詩は書かへんけど、書いてすっきりしました。';
        $this->assertIdentical($expected, $result);
    }

    public function test：半角カタカナを全角カタカナに変換() {
        $半角カタカナ = 'ｲｼﾞｭｳｲﾝ ﾋｶﾙ';

        $result   = $this->Model->toZenkakuKatakana($半角カタカナ);
        $expected = 'イジュウイン　ヒカル';
        $this->assertIdentical($expected, $result);
    }

    public function test：半角カタカナの濁音を全角カタカナに変換() {
        $半角の濁音 = array(
                'ｶﾞ'  => 'ガ',
                'ｷﾞ'  => 'ギ',
                'ｸﾞ'  => 'グ',
                'ｹﾞ'  => 'ゲ',
                'ｺﾞ'  => 'ゴ',
                'ｻﾞ'  => 'ザ',
                'ｼﾞ'  => 'ジ',
                'ｽﾞ'  => 'ズ',
                'ｾﾞ'  => 'ゼ',
                'ｿﾞ'  => 'ゾ',
                'ﾀﾞ'  => 'ダ',
                'ﾁﾞ'  => 'ヂ',
                'ﾂﾞ'  => 'ヅ',
                'ﾃﾞ'  => 'デ',
                'ﾄﾞ'  => 'ド',
                'ﾊﾞ'  => 'バ',
                'ﾋﾞ'  => 'ビ',
                'ﾌﾞ'  => 'ブ',
                'ﾍﾞ'  => 'ベ',
                'ﾎﾞ'  => 'ボ',
                'ｷﾞｬ' => 'ギャ',
                'ｷﾞｭ' => 'ギュ',
                'ｷﾞｮ' => 'ギョ',
                'ｼﾞｬ' => 'ジャ',
                'ｼﾞｭ' => 'ジュ',
                'ｼﾞｮ' => 'ジョ',
                'ﾁﾞｬ' => 'ヂャ',
                'ﾁﾞｭ' => 'ヂュ',
                'ﾁﾞｮ' => 'ヂョ',
                'ﾃﾞｬ' => 'デャ',
                'ﾃﾞｭ' => 'デュ',
                'ﾃﾞｮ' => 'デョ',
                'ﾋﾞｬ' => 'ビャ',
                'ﾋﾞｭ' => 'ビュ',
                'ﾋﾞｮ' => 'ビョ',
                'ｷﾞｪ' => 'ギェ',
                'ｸﾞｧ' => 'グァ',
                'ｸﾞｨ' => 'グィ',
                'ｸﾞｪ' => 'グェ',
                'ｸﾞｫ' => 'グォ',
                'ｼﾞｪ' => 'ジェ',
                'ｽﾞｨ' => 'ズィ',
                'ﾄﾞｧ' => 'ドァ',
                'ﾄﾞｨ' => 'ドィ',
                'ﾄﾞｩ' => 'ドゥ',
                'ﾄﾞｪ' => 'ドェ',
                'ﾄﾞｫ' => 'ドォ',
                'ﾃﾞｨ' => 'ディ',
                'ﾃﾞｩ' => 'デゥ',
                'ｸﾞヮ' => 'グヮ',
            );
        foreach ($半角の濁音 as $半角カタカナ => $全角カタカナ) {
            $result   = $this->Model->toZenkakuKatakana($半角カタカナ);
            $expected = $全角カタカナ;
            $this->assertIdentical($expected, $result, "濁音「{$全角カタカナ}」を全角に変換");
        }
    }

    public function test：半角カタカナの半濁音を全角カタカナに変換() {
        $半角の半濁音 = array(
                'ﾊﾟ'  => 'パ',
                'ﾋﾟ'  => 'ピ',
                'ﾌﾟ'  => 'プ',
                'ﾍﾟ'  => 'ペ',
                'ﾎﾟ'  => 'ポ',
                'ﾋﾟｬ' => 'ピャ',
                'ﾋﾟｭ' => 'ピュ',
                'ﾋﾟｮ' => 'ピョ',
            );
        foreach ($半角の半濁音 as $半角カタカナ => $全角カタカナ) {
            $result   = $this->Model->toZenkakuKatakana($半角カタカナ);
            $expected = $全角カタカナ;
            $this->assertIdentical($expected, $result, "半濁音「{$全角カタカナ}」を全角に変換");
        }
    }

    public function test：全てのカタカナを半角から全角に変換() {
        $半角カタカナ = 'ｧｱｨｲｩｳｪｴｫｵｶｶﾞｷｷﾞｸｸﾞｹｹﾞｺｺﾞｻｻﾞｼｼﾞｽｽﾞｾｾﾞｿｿﾞﾀﾀﾞﾁﾁﾞｯﾂﾂﾞﾃﾃﾞﾄﾄﾞﾅﾆﾇﾈﾉﾊﾊﾞﾊﾟﾋﾋﾞﾋﾟﾌﾌﾞﾌﾟﾍﾍﾞﾍﾟﾎﾎﾞﾎﾟﾏﾐﾑﾒﾓｬﾔｭﾕｮﾖﾗﾘﾙﾚﾛﾜｦﾝｳﾞｰ';

        $result   = $this->Model->toZenkakuKatakana($半角カタカナ);
        $expected = 'ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂッツヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモャヤュユョヨラリルレロワヲンヴー';
        $this->assertIdentical($expected, $result);
    }

    public function test：カタカナの捨て仮名を大字に変換() {
        $捨て仮名ありカタカナ = array(
                'イジュウイン　ヒカル' => 'イジユウイン　ヒカル',
                'ミッシェル・ガン・エレファント' => 'ミツシエル・ガン・エレフアント',
                'キャリーパミュパミュカワイィィ' => 'キヤリーパミユパミユカワイイイ'
            );
        foreach ($捨て仮名ありカタカナ as $捨て仮名あり => $捨て仮名なし) {
            $result   = $this->Model->ignoreKatakanaLowerCase($捨て仮名あり);
            $expected = $捨て仮名なし;
            $this->assertIdentical($expected, $result);
        }
    }

    public function test：カタカナの捨て仮名を全て変換() {
        $小書きカタカナ = 'ァィゥェォヵヶッャュョヮ';

        $result   = $this->Model->ignoreKatakanaLowerCase($小書きカタカナ);
        $expected = 'アイウエオカケツヤユヨワ';
        $this->assertIdentical($expected, $result);
    }
}
