<?php

// �e�X�g�p�̊G�������o��

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
    echo "������{$kumo}����{$hare}�A������{$ame}�A�����{$yuki}�ł����B".PHP_EOL;
    echo "docomo��{$imode}�ł��B".PHP_EOL;
}

function echo_softbank() {
    /* SoftBank */{
        $hare  = "\xF9\x8B";
        $kumo  = "\xF9\x8A";
        $ame   = "\xF9\x8C";
        $yuki  = "\xF9\x89";
        $imode = "\xFB\xD8";
    }
    echo "������{$kumo}����{$hare}�A������{$ame}�A�����{$yuki}�ł����B".PHP_EOL;
    echo "SoftBank��{$imode}�ł����B".PHP_EOL;
}

function echo_au() {
    /* au */ {
        $hare  = "\xF6\x60";
        $kumo  = "\xF6\x65";
        $ame   = "\xF6\x64";
        $yuki  = "\xF6\x5D";
        $ezweb = "\xF7\x94";
    }
    echo "������{$kumo}����{$hare}�A������{$ame}�A�����{$yuki}�ł����B".PHP_EOL;
    echo "au��{$ezweb}�ł��B".PHP_EOL;
}
