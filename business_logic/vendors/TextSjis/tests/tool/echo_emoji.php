<?php

// テスト用の絵文字を出力

echo_docomo();
echo_softbank();
echo_au();

function echo_docomo() {
    /* docomo */ {
        $hare  = "\xF8\x9F";
        $kumo  = "\xF8\xA0";
        $ame   = "\xF8\xA1";
        $yuki  = "\xF8\xA2";
        $imode = "\xF9\x75";
    }
    echo "今日は{$kumo}から{$hare}、明日は{$ame}、昨日は{$yuki}でした。".PHP_EOL;
    echo "docomoは{$imode}です。".PHP_EOL;
}

function echo_softbank() {
    /* SoftBank */{
        $hare  = "\xF9\x8B";
        $kumo  = "\xF9\x8A";
        $ame   = "\xF9\x8C";
        $yuki  = "\xF9\x89";
        $imode = "\xFB\xD8";
    }
    echo "今日は{$kumo}から{$hare}、明日は{$ame}、昨日は{$yuki}でした。".PHP_EOL;
    echo "SoftBankは{$imode}でした。".PHP_EOL;
}

function echo_au() {
    /* au */ {
        $hare  = "\xF6\x60";
        $kumo  = "\xF6\x65";
        $ame   = "\xF6\x64";
        $yuki  = "\xF6\x5D";
        $ezweb = "\xF7\x94";
    }
    echo "今日は{$kumo}から{$hare}、明日は{$ame}、昨日は{$yuki}でした。".PHP_EOL;
    echo "auは{$ezweb}です。".PHP_EOL;
}
