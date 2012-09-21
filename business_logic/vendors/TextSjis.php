<?php
/**
 * シフトJIS文字列 - シフトJIS文字列の操作機能を提供
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * シフトJISの機種依存文字を判定
 *
 * @author IKEDA youhei <youhey.ikeda@gmail.com>
 */
class TextSjis {

    /**
     * 適用範囲外の符号（機種依存文字）範囲テーブル
     * 
     * @var  array
     * @link http://ja.wikipedia.org/wiki/JIS_X_0208
     */
    private static $gaijiRanges = array(
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

    /**
     * 文字列にシフトJISの機種依存文字が含まれるかをチェック
     * 
     * <p>適用範囲外の符号（機種依存文字）が含まれていればTRUEを返す<br />
     * 適用範囲はJIS基本漢字のみとする</p>
     * 
     * @param  string  $attribute チェックする文字列
     * @return boolean 機種依存文字が含まれるか
     */
    public static function hasSjisGaiji($attribute) {
        $gaiji = array();
        foreach (self::$gaijiRanges as $range) {
            $begin   = pack("H*", dechex($range[0]));
            $end     = pack("H*", dechex($range[1]));
            $gaiji[] = "[{$begin}-{$end}]";
        }
        // [Note mb_ereg_match matches a string from the beginning only]
        // mb_ereg_match('a', 'some apples'); // returns false
        // mb_ereg_match('a', 'a kiwi');      // returns true
        // source. http://jp2.php.net/manual/ja/function.mb-ereg-match.php
        $pattern = '.*(?:'.implode('|', $gaiji).')';

        $regex_encoding = mb_regex_encoding();
        mb_regex_encoding('SJIS');
        $buf = mb_ereg_match($pattern, $attribute);
        mb_regex_encoding($regex_encoding);

        return 
            ($buf === true);
    }

    /**
     * 文字列にSoftBankのWebコードが含まれるかをチェック
     * 
     * <p>文字列にSoftBsnkのWebコードが含まれていればTRUEを返す</p>
     * 
     * @param  string  $attribute チェックする文字列
     * @return boolean SoftBankのWebコードが含まれるか
     * @link   http://creation.mb.softbank.jp/web/web_pic_about.html
     * @link   http://labs.unoh.net/2007/01/softbank_1.html
     */
    public static function hasWebCode($attribute) {
        $webcode = '\x1B\x24(\x47[\x21-\x7A]+|\x45[\x21-\x7A]+|\x46[\x21-\x7A]+|' 
                 . '\x4F[\x21-\x6D]+|\x50[\x21-\x6C]+|\x51[\x21-\x5E]+)\x0F?';

        return 
            (bool)preg_match("/{$webcode}/", $attribute);
    }
}
