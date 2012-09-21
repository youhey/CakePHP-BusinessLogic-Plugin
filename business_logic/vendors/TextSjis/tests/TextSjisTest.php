<?php
/**
 * シフトJIS文字列 - シフトJIS文字列の操作機能を提供
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

require_once
    dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'TextSjis.php';

/**
 * テストケース - シフトJISの機種依存文字を判定するモジュール
 * 
 * @author IKEDA Youhei <ikeda@midc.jp>
 */
class TextSjisTest extends PHPUnit_Framework_TestCase {

    private $dir = '';
    private $sjisGaijiRanges = array(
                array(0x8540,0x857E),array(0x8580,0x859E), //   9区
                array(0x859F,0x85FC),                      //  10区
                array(0x8640,0x867E),array(0x8680,0x869E), //  11区
                array(0x869F,0x86FC),                      //  12区
                array(0x8740,0x877E),array(0x8780,0x879E), //  13区-NEC特殊記号-
                array(0x879F,0x87FC),                      //  14区
                array(0x8840,0x887E),array(0x8880,0x889E), //  15区
                array(0xEB40,0xEB7E),array(0xEB80,0xEB9E), //  85区
                array(0xEB9F,0xEBFC),                      //  86区
                array(0xEC40,0xEC7E),array(0xEC80,0xEC9E), //  87区
                array(0xEC9F,0xECFC),                      //  88区
                array(0xED40,0xED7E),array(0xED80,0xED9E), //  89区-NEC拡張外字
                array(0xED9F,0xEDFC),                      //  90区-NEC拡張外字
                array(0xEE40,0xEE7E),array(0xEE80,0xEE9E), //  91区-NEC拡張外字
                array(0xEE9F,0xEEFC),                      //  92区-NEC拡張外字
                array(0xEF40,0xEF7E),array(0xEF80,0xEF9E), //  93区-NEC拡張外字
                array(0xEF9F,0xEFFC),                      //  94区-NEC拡張外字
                array(0xF040,0xF07E),array(0xF080,0xF09E), //  95区-ユーザ領域
                array(0xF09F,0xF0FC),                      //  96区-ユーザ領域
                array(0xF140,0xF17E),array(0xF180,0xF19E), //  97区-ユーザ領域
                array(0xF19F,0xF1FC),                      //  98区-ユーザ領域
                array(0xF240,0xF27E),array(0xF280,0xF29E), //  99区-ユーザ領域
                array(0xF29F,0xF2FC),                      // 100区-ユーザ領域
                array(0xF340,0xF37E),array(0xF380,0xF39E), // 101区-ユーザ領域
                array(0xF39F,0xF3FC),                      // 102区-ユーザ領域
                array(0xF440,0xF47E),array(0xF480,0xF49E), // 103区-ユーザ領域
                array(0xF49F,0xF4FC),                      // 104区-ユーザ領域
                array(0xF540,0xF57E),array(0xF580,0xF59E), // 105区-ユーザ領域
                array(0xF59F,0xF5FC),                      // 106区-ユーザ領域
                array(0xF640,0xF67E),array(0xF680,0xF69E), // 107区-ユーザ領域
                array(0xF69F,0xF6FC),                      // 108区-ユーザ領域
                array(0xF740,0xF77E),array(0xF780,0xF79E), // 109区-ユーザ領域
                array(0xF79F,0xF7FC),                      // 110区-ユーザ領域
                array(0xF840,0xF87E),array(0xF880,0xF89E), // 111区-ユーザ領域
                array(0xF89F,0xF8FC),                      // 112区-ユーザ領域
                array(0xF940,0xF97E),array(0xF980,0xF99E), // 113区-ユーザ領域
                array(0xF99F,0xF9FC),                      // 114区-ユーザ領域
                array(0xFA40,0xFA7E),array(0xFA80,0xFA9E), // 115区-IBM拡張漢字
                array(0xFA9F,0xFAFC),                      // 116区-IBM拡張漢字
                array(0xFB40,0xFB7E),array(0xFB80,0xFB9E), // 117区-IBM拡張漢字
                array(0xFB9F,0xFBFC),                      // 118区-IBM拡張漢字
                array(0xFC40,0xFC7E),array(0xFC80,0xFC9E), // 119区-IBM拡張漢字
                array(0xFC9F,0xFCFC),                      // 120区
            );
    private $docomoEmojiRange = array(
                array(0xF89F, 0xF8FC), // 基本絵文字（1）
                array(0xF940, 0xF949), // 基本絵文字（2）
                array(0xF950, 0xF952), // 基本絵文字（3）
                array(0xF955, 0xF957), // 基本絵文字（4）
                array(0xF95B, 0xF95E), // 基本絵文字（5）
                array(0xF972, 0xF97E), // 基本絵文字（6）
                array(0xF980, 0xF9B0), // 基本絵文字（7）
                array(0xF9B1, 0xF9FC), // 拡張絵文字
        );

    protected function setUp() {
        $this->dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
    }

    public function test：外字を含んだパターン1の文字列をチェック() {
        $text   = file_get_contents("{$this->dir}has_gaiji_1.txt");
        $result = TextSjis::hasSjisGaiji($text);
        $this->assertTrue($result, 'Validate for gaiji-text, gaiji pattern 1');
    }

    public function test：外字を含んだパターン2の文字列をチェック() {
        $text   = file_get_contents("{$this->dir}has_gaiji_1.txt");
        $result = TextSjis::hasSjisGaiji($text);
        $this->assertTrue($result, 'Validate for gaiji-text, gaiji pattern 2');
    }

    public function test：外字を含まないパターン1の文字列をチェック() {
        $text   = file_get_contents("{$this->dir}no_gaiji_1.txt");
        $result = TextSjis::hasSjisGaiji($text);
        $this->assertFalse($result, 'Validate for simple-text, text pattern 1');
    }

    public function test：外字を含まないパターン2の文字列をチェック() {
        $text   = file_get_contents("{$this->dir}no_gaiji_2.txt");
        $result = TextSjis::hasSjisGaiji($text);
        $this->assertFalse($result, 'Validate for simple-text, text pattern 2');
    }

    public function test：docomoの絵文字を含んだテキストのチェック() {
        $text   = file_get_contents("{$this->dir}docomo_emoji.txt");
        $result = TextSjis::hasSjisGaiji($text);
        $this->assertTrue($result, 'Validate for docomo emoji-text');
    }

    public function test：auの絵文字を含んだテキストのチェック() {
        $text   = file_get_contents("{$this->dir}au_emoji.txt");
        $result = TextSjis::hasSjisGaiji($text);
        $this->assertTrue($result, 'Validate for au text-emoji');
    }

    /** @dataProvider getSjisGaijiProvider */
    public function test：シフトJISの外字を総当りでチェック($char) {
        $result = TextSjis::hasSjisGaiji($char);
        $this->assertTrue($result, "Validate for sjis gaiji: {$char}");
    }

    /** @dataProvider getDocomoEmojiProvider */
    public function test：docomo絵文字を総当りでチェック($emoji, $mixed) {
        $result = TextSjis::hasSjisGaiji($emoji);
        $this->assertTrue($result, "Validate for docomo emoji: {$emoji}");
    }

    /** @dataProvider getAuEmojiProvider */
    public function test：au絵文字を総当りでチェック($emoji, $mixed) {
        $result = TextSjis::hasSjisGaiji($emoji);
        $this->assertTrue($result, "Validate for au emoji: {$emoji}");
    }

    /** @dataProvider getCalculationDocomoEmojiProvider */
    public function test：算出可能なdocomo絵文字を総当たりでチェック($emoji) {
        $result = TextSjis::hasSjisGaiji($emoji);
        $this->assertTrue($result, 'Validate for docomo emoji: {$emoji}');
    }

    public function test：SoftBankのWebコードを含んだテキストをチェック() {
        $text   = file_get_contents("{$this->dir}softbank_webcode.txt");
        $result = TextSjis::hasWebCode($text);
        $this->assertTrue($result, 'Validate for softbank web-code');
    }

    public function test：SoftBankのWebコードを含まないテキストをチェック() {
        $text   = file_get_contents("{$this->dir}has_gaiji_1.txt");
        $result = TextSjis::hasWebCode($text);
        $this->assertFalse($result, 'Validate for softbank web-code');

        $text   = file_get_contents("{$this->dir}no_gaiji_1.txt");
        $result = TextSjis::hasWebCode($text);
        $this->assertFalse($result, 'Validate for softbank web-code');
    }

    /** @dataProvider getSoftBankWebCodeProvider */
    public function test：SoftBankのWebコードを総当りでチェック($webcode) {
        $result = TextSjis::hasWebCode($webcode);
        $this->assertTrue($result, "Validate for softbank web-code: {$webcode}");
    }

    /**
     * 機種依存文字のプロバイダ
     * 機種依存文字はJIS標準外の区点番号から算出
     */
    public function getSjisGaijiProvider()
    {
        $provider = array();
        foreach ($this->sjisGaijiRanges as $range) {
            for ($i = $range[0]; $i <= $range[1]; ++$i) {
                $hex  = dechex($i);
                $char = eval('return "' . 
                             '\x'.substr($hex, 0, 2) . 
                             '\x'.substr($hex, 2, 2) . 
                             '";');
                $provider[] = array($char);
            }
        }

        return $provider;
    }

    /** docomo絵文字プロバイダ */
    public function getDocomoEmojiProvider()
    {
        $dir    = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
        $text   = file_get_contents("{$dir}docomo_all_emoji.txt");
        $header = mb_convert_encoding('docomoの絵文字「', 'SJIS-win', 'UTF-8');
        $footer = mb_convert_encoding('」です。', 'SJIS-win', 'UTF-8');
        $emoji  = array();
        foreach (explode("\r\n", $text) as $char) {
            $emoji[] = array($char, $header.$char.$footer);
        }

        return $emoji;
    }

    /** au絵文字プロバイダ */
    public function getAuEmojiProvider()
    {
        $dir    = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
        $text   = file_get_contents("{$dir}au_all_emoji.txt");
        $header = mb_convert_encoding('auの絵文字「', 'SJIS-win', 'UTF-8');
        $footer = mb_convert_encoding('」です。', 'SJIS-win', 'UTF-8');
        $emoji  = array();
        foreach (explode("\r\n", $text) as $char) {
            $emoji[] = array($char, $header.$char.$footer);
        }

        return $emoji;
    }

    /** SoftBankのWebコードプロバイダ */
    public function getSoftBankWebCodeProvider()
    {
        $dir     = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
        $text    = file_get_contents("{$dir}softbank_all_webcode.txt");
        $header  = mb_convert_encoding('SoftBank Webコード「', 'SJIS-win', 'UTF-8');
        $footer  = mb_convert_encoding('」。', 'SJIS-win', 'UTF-8');
        $webcode = array();
        foreach (explode("\r\n", $text) as $char) {
            $webcode[] = array($header.$char.$footer);
        }

        return $webcode;
    }

    /**
     * docomo絵文字のプロバイダその2
     * docomoEmojiRange公式の絵文字範囲から算出
     */
    public function getCalculationDocomoEmojiProvider()
    {
        $provider = array();
        foreach ($this->docomoEmojiRange as $range) {
            for ($i = $range[0]; $i <= $range[1]; ++$i) {
                $hex  = dechex($i);
                $char = eval('return "' . 
                             '\x'.substr($hex, 0, 2) . 
                             '\x'.substr($hex, 2, 2) . 
                             '";');
                $provider[] = array($char);
            }
        }

        return $provider;
    }
}
