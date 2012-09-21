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

App::import('Core', 'Security');

class TestAdditionalValidationRuleModel extends CakeTestModel {
    public
        $useTable = false, 
        $actsAs   = array('BusinessLogic.AdditionalValidationRule');
}

class TestCompareConfirmationRuleModel extends CakeTestModel {
    public
        $useTable = false, 
        $actsAs   = array('BusinessLogic.AdditionalValidationRule');

    public 
        $alias = 'Foo', 
        $validate = array('name' => array('rule' => array('compareConfirmation', '_confirm')));
}

/**
 * 拡張したバリデーションのテストケース
 * 
 * @author IKEDA Youhei <youhey.ikeda@gmail.com>
 */
class AdditionalValidationRuleBehaviorTestCase extends CakeTestCase {

    private 
        $env = array();

    public function startTest() {
        $this->env = $_SERVER;
        $this->Model = new TestAdditionalValidationRuleModel;
    }
    public function endTest() {
        $_SERVER = $this->env;
        $this->Model = null;
        ClassRegistry::flush();
    }

    public function test：バリデーション「email」単純なOKパターンのテスト() {
        $this->assertTrue($this->Model->email(array('info@example.com')), 'ユーザ名英字はOK');
        $this->assertTrue($this->Model->email(array('42@example.com')), 'ユーザ名数字はOK');
        $this->assertTrue($this->Model->email(array('foo42@example.com')), 'ユーザ名英数字（末尾数字）はOK');
        $this->assertTrue($this->Model->email(array('42foo@example.com')), 'ユーザ名英数字（先頭数字）はOK');
    }

    public function test：バリデーション「email」ローカル部に記号を含むOKパターンのテスト() {
        $this->assertTrue($this->Model->email(array('foo.bar@example.com')), 'ユーザ名にドットがあってもOK');
        $this->assertTrue($this->Model->email(array('foo-bar@example.com')), 'ユーザ名にハイフンがあってもOK');
        $this->assertTrue($this->Model->email(array('foo_bar@example.com')), 'ユーザ名にアンダーバーがあってもOK');
        $this->assertTrue($this->Model->email(array('foo+bar@example.com')), 'ユーザ名にプラスがあってもOK');
        $this->assertTrue($this->Model->email(array('foo&bar@example.com')), 'ユーザ名にアンパサンドがあってもOK');
        $this->assertTrue($this->Model->email(array("foo'bar@example.com")), 'ユーザ名にシングルクォテーションがあってもOK');
        $this->assertTrue($this->Model->email(array('foo_bar@example.com')), 'ユーザ名にアンダーバーがあってもOK');
        $this->assertTrue($this->Model->email(array('foo!bar@example.com')), 'ユーザ名にビックリマークがあってもOK');
        $this->assertTrue($this->Model->email(array('foo#bar@example.com')), 'ユーザ名にシャープがあってもOK');
        $this->assertTrue($this->Model->email(array('foo$bar@example.com')), 'ユーザ名にダラーがあってもOK');
        $this->assertTrue($this->Model->email(array('foo%bar@example.com')), 'ユーザ名にパーセントがあってもOK');
        $this->assertTrue($this->Model->email(array('foo*bar@example.com')), 'ユーザ名にアスタリスクがあってもOK');
        $this->assertTrue($this->Model->email(array('foo/bar@example.com')), 'ユーザ名にスラッシュがあってもOK');
        $this->assertTrue($this->Model->email(array('foo=bar@example.com')), 'ユーザ名にイコールがあってもOK');
        $this->assertTrue($this->Model->email(array('foo?bar@example.com')), 'ユーザ名にハテナがあってもOK');
        $this->assertTrue($this->Model->email(array('foo^bar@example.com')), 'ユーザ名に^があってもOK');
        $this->assertTrue($this->Model->email(array('foo`bar@example.com')), 'ユーザ名にバックスラッシュがあってもOK');
        $this->assertTrue($this->Model->email(array('foo{bar@example.com')), 'ユーザ名に{があってもOK');
        $this->assertTrue($this->Model->email(array('foo}bar@example.com')), 'ユーザ名に}があってもOK');
        $this->assertTrue($this->Model->email(array('foo|bar@example.com')), 'ユーザ名にパイプがあってもOK');
        $this->assertTrue($this->Model->email(array('foo~bar@example.com')), 'ユーザ名にニョロがあってもOK');
    }

    public function test：バリデーション「email」ドメイン部のOKパターンのテスト() {
        $this->assertTrue($this->Model->email(array('foo@mail.example.com')), 'サブドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@e-example.com')),    'ハイフンを含むドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@12345.com')),        '数字だけのドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@x.com')),            '一文字だけのドメインでもOK');
    }

    public function test：バリデーション「email」ドメインのテスト__ICANN_TLDs() {
        $this->assertTrue($this->Model->email(array('foo@example.aero')), '「aero」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.asia')), '「asia」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.biz')), '「biz」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.cat')), '「cat」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.com')), '「com」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.coop')), '「coop」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.edu')), '「edu」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.gov')), '「gov」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.info')), '「info」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.int')), '「int」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.jobs')), '「jobs」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.mil')), '「mil」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.mobi')), '「mobi」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.museum')), '「museum」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.name')), '「name」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.net')), '「net」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.org')), '「org」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.pro')), '「pro」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.tel')), '「tel」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.travel')), '「travel」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.hr')), '「hr」ドメインでもOK');
    }

    public function test：バリデーション「email」ドメインのテスト__JPドメイン() {
        $this->assertTrue($this->Model->email(array('foo@example.jp')), '「jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.co.jp')), '「co.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.or.jp')), '「or.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.ne.jp')), '「ne.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.ac.jp')), '「ac.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.ed.jp')), '「ed.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.go.jp')), '「go.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.gr.jp')), '「gr.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.lg.jp')), '「lg.jp」ドメインでもOK');
        $this->assertTrue($this->Model->email(array('foo@example.ed.jp')), '「ed.jp」ドメインでもOK');
    }

    public function test：バリデーション「email」RFCの規定で有効なメルアドのテスト() {
        $this->assertTrue($this->Model->email(array('email-_-@example.com')), 'ありがちで、仕様上も有効なメルアドその1');
        $this->assertTrue($this->Model->email(array('email^.^@example.com')), 'ありがちで、仕様上も有効なメルアドその2');
        $this->assertTrue($this->Model->email(array("email'_'@example.com")), 'ありがちで、仕様上も有効なメルアドその3');
        $this->assertTrue($this->Model->email(array("!#$%&'*+-/=?^_`.{|}~@example.com")), '記号だらけだけど有効なメルアド');
    }

    public function test：バリデーション「email」RFCの規定では無効だけど、有効にしたメルアドのテスト() {
        // RFCの規定では無効だけど、モバイルやGmailを考慮して許容
        $this->assertTrue($this->Model->email(array('.email@example.com')), '先頭がドットだから仕様上はNG、でも許容している');
        $this->assertTrue($this->Model->email(array('email.@example.com')), '末尾がドットだから仕様上はNG、でも許容している');
        $this->assertTrue($this->Model->email(array('test...email@example.com')), 'ドットが連続しているから仕様上はNG、でも許容している');
        // $this->assertTrue($this->Model->email(array('email:-}@example.com')));
    }

    public function test：バリデーション「email」ローカル部が複雑なメルアドのテスト() {
        // strange, but technically valid email addresses
        $this->assertTrue($this->Model->email(array('S=postmaster/OU=rz/P=uni-frankfurt/A=d400/C=de@gateway.d400.de')), '複雑だけど有効なメルアドその1');
        $this->assertTrue($this->Model->email(array('customer/department=shipping@example.com')), '複雑だけど有効なメルアドその2');
        $this->assertTrue($this->Model->email(array('$A12345@example.com')), '複雑だけど有効なメルアドその3');
        $this->assertTrue($this->Model->email(array('!def!xyz%abc@example.com')), '複雑だけど有効なメルアドその4');
        $this->assertTrue($this->Model->email(array('_somename@example.com')), '複雑だけど有効なメルアドその5');
    }

    public function test：バリデーション「email」不正なメルアドのテスト() {
        $this->assertFalse($this->Model->email(array('email@localhost')), 'ドメインが不正（トップレベルしかない）');
        $this->assertFalse($this->Model->email(array('email@192.168.0.1')), 'ドメインが不正（IPアドレスではだめ）');
        $this->assertFalse($this->Model->email(array('email@example.c')), 'ドメインが不正（TLDは2文字以上）');
        $this->assertFalse($this->Model->email(array('email@example.com.a')), 'ドメインが不正（TLDは2文字以上）');
        $this->assertFalse($this->Model->email(array('email@example.12')), 'ドメインが不正（TLDは数字不可）');
        $this->assertFalse($this->Model->email(array('email@example.toolong')), 'ドメインが不正（トップレベルが不正）');
        $this->assertFalse($this->Model->email(array('email@example.com.')), 'ドメインが不正（最後がドット）');
        $this->assertFalse($this->Model->email(array('email@example..com')), 'ドメインが不正（ドットが連続）');
        $this->assertFalse($this->Model->email(array('email@example.com;')), 'ドメインが不正（最後がセミコロン）');
        $this->assertFalse($this->Model->email(array('email@@example.com')), 'アットマークが連続している');
        $this->assertFalse($this->Model->email(array('email@efg@example.com')), 'アットマークが複数ある');
        $this->assertFalse($this->Model->email(array('email@sub,example.com')), 'ドメインが不正（カンマ）');
        $this->assertFalse($this->Model->email(array("email@sub'example.com")), 'ドメインが不正（シングルクォテーション）');
        $this->assertFalse($this->Model->email(array('email@sub/example.com')), 'ドメインが不正（スラッシュ）');
        $this->assertFalse($this->Model->email(array('email@yahoo!.com')), 'ドメインが不正（ビックリマーク）');
        $this->assertFalse($this->Model->email(array('email@example_underscored.com')), 'ドメインが不正（アンダーバー）');
        $this->assertFalse($this->Model->email(array('email@test.ra.ru....com')), 'ドメインが不正（ドットが連続）');
        $this->assertFalse($this->Model->email(array('foo(bar@example.com')), 'ローカル部に括弧');
        $this->assertFalse($this->Model->email(array('foo)bar@example.com')), 'ローカル部に括弧');
        $this->assertFalse($this->Model->email(array('foo<bar@example.com')), 'ローカル部に括弧');
        $this->assertFalse($this->Model->email(array('foo>bar@example.com')), 'ローカル部に括弧');
        $this->assertFalse($this->Model->email(array('foo[bar@example.com')), 'ローカル部に括弧');
        $this->assertFalse($this->Model->email(array('foo]bar@example.com')), 'ローカル部に括弧');
        $this->assertFalse($this->Model->email(array('foo:bar@example.com')), 'ローカル部にコロン');
        $this->assertFalse($this->Model->email(array('foo;bar@example.com')), 'ローカル部にセミコロン');
        $this->assertFalse($this->Model->email(array('foo bar@example.com')), 'ローカル部にスペース');
        $this->assertFalse($this->Model->email(array('foo,bar@example.com')), 'ローカル部にカンマ');
        $this->assertFalse($this->Model->email(array('Nyrée.surname@example.com')), 'ローカル部にマルチバイト文字');
    }


    public function test：バリデーション「telephone」ハイフンありの自宅電話番号をテスト() {
        $this->assertTrue($this->Model->telephone(array('00-0000-0000')), '「0」市外局番1桁、市内局番4桁、加入者番号');
        $this->assertTrue($this->Model->telephone(array('000-000-0000')), '「0」市外局番2桁、市内局番3桁、加入者番号');
        $this->assertTrue($this->Model->telephone(array('0000-00-0000')), '「0」市外局番3桁、市内局番2桁、加入者番号');
        $this->assertTrue($this->Model->telephone(array('00000-0-0000')), '「0」市外局番4桁、市内局番1桁、加入者番号');

        for ($i = 0; $i < 10000; ++$i) {
            $pref = $i;
            if ($i > 999) {
                $city = '0';
            } elseif ($i > 99) {
                $city = '00';
            } elseif ($i > 9) {
                $city = '000';
            } else {
                $city = '0000';
            }

            $number = "0{$pref}-{$city}-0000";
            $this->assertTrue($this->Model->telephone(array($number)), "自宅電話番号「{$number}」");
        }
        for ($i = 0; $i < 10000; ++$i) {
            if ($i > 999) {
                $pref = '0';
            } elseif ($i > 99) {
                $pref = '00';
            } elseif ($i > 9) {
                $pref = '000';
            } else {
                $pref = '0000';
            }
            $city = $i;

            $number = "0{$pref}-{$city}-0000";
            $this->assertTrue($this->Model->telephone(array($number)), "自宅電話番号「{$number}」");
        }

        $this->assertTrue($this->Model->telephone(array('01234-5-6789')), '電話番号は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('0123-45-6789')), '電話番号は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('012-345-6789')), '電話番号は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('01-2345-6789')), '電話番号は桁数のみチェック、番号体系は無視');
    }

    public function test：バリデーション「telephone」ハイフンなしの自宅電話番号をテスト() {
        $this->assertTrue($this->Model->telephone(array('0000000000')), '「0」市外局番、市内局番、加入者番号');

        for ($i = 0; $i < 10000; ++$i) {
            $pref = $i;
            if ($i > 999) {
                $city = '0';
            } elseif ($i > 99) {
                $city = '00';
            } elseif ($i > 9) {
                $city = '000';
            } else {
                $city = '0000';
            }

            $number = "0{$pref}{$city}0000";
            $this->assertTrue($this->Model->telephone(array($number)), "自宅電話番号「{$number}」");
        }
        for ($i = 0; $i < 10000; ++$i) {
            if ($i > 999) {
                $pref = '0';
            } elseif ($i > 99) {
                $pref = '00';
            } elseif ($i > 9) {
                $pref = '000';
            } else {
                $pref = '0000';
            }
            $city = $i;

            $number = "0{$pref}{$city}0000";
            $this->assertTrue($this->Model->telephone(array($number)), "自宅電話番号「{$number}」");
        }

        $this->assertTrue($this->Model->telephone(array('0123456789')), '電話番号は桁数のみチェック、番号体系は無視');
    }

    public function test：バリデーション「telephone」ハイフンありの携帯電話番号をテスト() {
        $this->assertTrue($this->Model->telephone(array('090-000-00000')), '下8桁はハイフン区切りの「3」「5」形式が基本');
        $this->assertTrue($this->Model->telephone(array('090-0000-0000')), '下8桁はハイフン区切りの「4」「4」形式も許容');

        $this->assertTrue($this->Model->telephone(array('080-000-00000')), '下8桁はハイフン区切りの「3」「5」形式が基本');
        $this->assertTrue($this->Model->telephone(array('080-0000-0000')), '下8桁はハイフン区切りの「4」「4」形式も許容');

        // http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html
        for ($i = 10; $i < 100; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $number = sprintf('090-%s%s-00000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（090）3-5形式「{$number}」");
                $number = sprintf('090-%s%s0-0000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（090）4-4形式「{$number}」");
            }
        }
        // http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html
        for ($i = 10; $i < 73; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $number = sprintf('080-%s%s-00000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（080）3-5形式「{$number}」");
                $number = sprintf('080-%s%s0-0000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（080）4-4形式「{$number}」");
            }
        }

        $this->assertTrue($this->Model->telephone(array('090-012-34567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('090-123-45678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('090-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('090-0123-4567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('090-1234-5678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('090-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');

        $this->assertTrue($this->Model->telephone(array('080-012-34567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('080-123-45678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('080-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('080-0123-4567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('080-1234-5678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('080-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');

        for ($i = 1; $i < 10; ++$i) {
            $number = sprintf('090-%s-%s', str_repeat($i, 3), str_repeat($i, 5));
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（090）3-5形式「{$number}」");
            $number = sprintf('090-%s-%s', str_repeat($i, 4), str_repeat($i, 4));
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（090）4-4形式「{$number}」");

            $number = sprintf('080-%s-%s', str_repeat($i, 3), str_repeat($i, 5));
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（080）3-5形式「{$number}」");
            $number = sprintf('080-%s-%s', str_repeat($i, 4), str_repeat($i, 4));
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（080）4-4形式「{$number}」");
        }
    }

    public function test：バリデーション「telephone」ハイフンなしの携帯電話番号をテスト() {
        $this->assertTrue($this->Model->telephone(array('09000000000')), '携帯電話番号（090）区切りなしでもOK');
        $this->assertTrue($this->Model->telephone(array('08000000000')), '携帯電話番号（080）区切りなしでもOK');

        // http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html
        for ($i = 10; $i < 100; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $number = sprintf('090%s%s00000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（090）「{$number}」");
            }
        }
        // http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html
        for ($i = 10; $i < 73; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $number = sprintf('080%s%s00000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（080）「{$number}」");
            }
        }

        $this->assertTrue($this->Model->telephone(array('09001234567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('09012345678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('09023456789')), '下8桁は桁数のみチェック、番号体系は無視');

        $this->assertTrue($this->Model->telephone(array('08001234567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('08012345678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('08023456789')), '下8桁は桁数のみチェック、番号体系は無視');

        for ($i = 1; $i < 10; ++$i) {
            $number = '090'.str_repeat($i, 8);
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（090）「{$number}」");

            $number = '080'.str_repeat($i, 8);
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（080）「{$number}」");
        }
    }

    public function test：バリデーション「telephone」ハイフンありのPHS電話番号をテスト() {
        $this->assertTrue($this->Model->telephone(array('070-000-00000')), '下8桁はハイフン区切りの「3」「5」形式が基本');
        $this->assertTrue($this->Model->telephone(array('070-0000-0000')), '下8桁はハイフン区切りの「4」「4」形式も許容');

        // http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html
        for ($i = 50; $i < 70; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $number = sprintf('070-%s%s-00000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "PHSの電話番号、3-5形式「{$number}」");
                $number = sprintf('070-%s%s0-0000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "PHSの電話番号、4-4形式「{$number}」");
            }
        }

        $this->assertTrue($this->Model->telephone(array('070-012-34567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('070-123-45678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('070-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('070-0123-4567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('070-1234-5678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('070-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');

        for ($i = 1; $i < 10; ++$i) {
            $number = sprintf('070-%s-%s', str_repeat($i, 3), str_repeat($i, 5));
            $this->assertTrue($this->Model->telephone(array($number)), "PHSの電話番号、3-5形式「{$number}」");
            $number = sprintf('070-%s-%s', str_repeat($i, 4), str_repeat($i, 4));
            $this->assertTrue($this->Model->telephone(array($number)), "PHSの電話番号、4-4形式「{$number}」");
        }
    }

    public function test：バリデーション「telephone」ハイフンなしのPHS電話番号をテスト() {
        $this->assertTrue($this->Model->telephone(array('07000000000')), 'PHSの電話番号は区切りなしでもOK');

        // http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html
        for ($i = 50; $i < 70; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $number = sprintf('070%s%s00000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "PHSの電話番号3-5形式「{$number}」");
            }
        }

        $this->assertTrue($this->Model->telephone(array('07001234567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('07012345678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('07023456789')), '下8桁は桁数のみチェック、番号体系は無視');

        for ($i = 1; $i < 10; ++$i) {
            $number = sprintf('070%s%s', str_repeat($i, 3), str_repeat($i, 5));
            $this->assertTrue($this->Model->telephone(array($number)), "PHSの電話番号「{$number}」");
        }
    }

    public function test：バリデーション「telephone」ハイフンありのIP電話番号をテスト() {
        $this->assertTrue($this->Model->telephone(array('050-0000-0000')), '下8桁はハイフン区切りの「4」「4」形式が許容');
        $this->assertTrue($this->Model->telephone(array('050-000-00000')), '下8桁はハイフン区切りの「3」「5」形式も基本');

        // http://www.soumu.go.jp/main_sosiki/joho_tsusin/top/tel_number/number_shitei.html
        for ($i = 100; $i < 1000; ++$i) {
            for ($j = 0; $j < 10; ++$j) {
                $number = sprintf('050-%s%s-0000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（050）4-4形式「{$number}」");

                $number = sprintf('050-%s-%s0000', $i, $j);
                $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（050）3-5形式「{$number}」");
            }
        }

        $this->assertTrue($this->Model->telephone(array('050-0123-4567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('050-1234-5678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('050-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('050-012-34567')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('050-123-45678')), '下8桁は桁数のみチェック、番号体系は無視');
        $this->assertTrue($this->Model->telephone(array('050-234-56789')), '下8桁は桁数のみチェック、番号体系は無視');

        for ($i = 1; $i < 10; ++$i) {
            $number = sprintf('050-%s-%s', str_repeat($i, 4), str_repeat($i, 4));
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（050）4-4形式「{$number}」");
            $number = sprintf('050-%s-%s', str_repeat($i, 3), str_repeat($i, 5));
            $this->assertTrue($this->Model->telephone(array($number)), "携帯電話番号（050）3-5形式「{$number}」");
        }
    }

    public function test：バリデーション「telephone」失敗するパターンのテスト() {
        $this->assertFalse($this->Model->telephone(array('hoge')), '電話番号の体系と無関係な文字列');
        $this->assertFalse($this->Model->telephone(array('foo-bar-hoge')), '桁数とハイフン意外はの体系と無関係な文字列');

        $this->assertFalse($this->Model->telephone(array('12-3456-7890')), '国内プレフィックス「0」から始まる');
        $this->assertFalse($this->Model->telephone(array('123-456-7890')), '国内プレフィックス「0」から始まる');
        $this->assertFalse($this->Model->telephone(array('1234-56-7890')), '国内プレフィックス「0」から始まる');
        $this->assertFalse($this->Model->telephone(array('12345-6-7890')), '国内プレフィックス「0」から始まる');

        $this->assertFalse($this->Model->telephone(array('+81-00000-0000')), '国際電話には対応しない');
        $this->assertFalse($this->Model->telephone(array('+81-0-0000-0000')), '国際電話には対応しない');
        $this->assertFalse($this->Model->telephone(array('+81-00-000-0000')), '国際電話には対応しない');
        $this->assertFalse($this->Model->telephone(array('+81-000-00-0000')), '国際電話には対応しない');
        $this->assertFalse($this->Model->telephone(array('+81-0000-0-0000')), '国際電話には対応しない');
        $this->assertFalse($this->Model->telephone(array('+81000000000')), '国際電話には対応しない');
    }

    public function test：バリデーション「telephone」固定電話番号の桁数をテスト() {
        $this->assertFalse($this->Model->telephone(array('0')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('00')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('0000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('00000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('000000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('0000000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('00000000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('000000000')), '固定電話は必ず10桁');

        $this->assertFalse($this->Model->telephone(array('00000000000')), '固定電話は必ず10桁');

        $this->assertFalse($this->Model->telephone(array('0-0')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('0-0-0')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('0-0-00')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('0-0-000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('0-0-0000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('00-0-0000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('00-00-0000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('00-000-0000')), '固定電話は必ず10桁');
        $this->assertFalse($this->Model->telephone(array('000-0000-0000')), '固定電話は必ず10桁');
    }

    public function test：バリデーション「telephone」携帯電話番号の桁数をテスト() {
        $this->assertFalse($this->Model->telephone(array('090')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('0900')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('09000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('090000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('0900000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('09000000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('090000000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('090000000000')), '携帯電話（090）は必ず11桁');

        // この番号は、固定電話の10桁で許容される
        // $this->assertFalse($this->Model->telephone(array('0900000000')), '携帯電話（090）は必ず11桁');

        $this->assertFalse($this->Model->telephone(array('080')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('0800')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('08000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('080000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('0800000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('08000000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('080000000')), '携帯電話（090）は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('080000000000')), '携帯電話（090）は必ず11桁');

        // この番号は、固定電話の10桁で許容される
        // $this->assertFalse($this->Model->telephone(array('0800000000')), '携帯電話（090）は必ず11桁');
    }

    public function test：バリデーション「telephone」PHS電話番号の桁数をテスト() {
        $this->assertFalse($this->Model->telephone(array('070')), '携帯電話は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('0700')), '携帯電話は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('07000')), '携帯電話は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('070000')), '携帯電話は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('0700000')), '携帯電話は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('07000000')), '携帯電話は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('070000000')), '携帯電話は必ず11桁');
        $this->assertFalse($this->Model->telephone(array('070000000000')), '携帯電話は必ず11桁');

        // この番号は、固定電話の10桁で許容される
        // $this->assertFalse($this->Model->telephone(array('0700000000')), '携帯電話は必ず11桁');
    }

    public function test：バリデーション「telephone」非対応の電話番号をテスト() {
        $this->assertFalse($this->Model->telephone(array('020-000-00000')), 'ポケベルの番号は電話番号ではない');
        $this->assertFalse($this->Model->telephone(array('020-0000-0000')), 'ポケベルの番号は電話番号ではない');
        $this->assertFalse($this->Model->telephone(array('02000000000')), 'ポケベルの番号は電話番号ではない');

        $this->assertFalse($this->Model->telephone(array('060-0000-0000')), 'FMCの電話番号には非対応');
        $this->assertFalse($this->Model->telephone(array('060-000-00000')), 'FMCの電話番号には非対応');
        $this->assertFalse($this->Model->telephone(array('06000000000')), 'FMCの電話番号には非対応');

        $this->assertFalse($this->Model->telephone(array('030-000-00000')), '自動車電話、沿岸船舶電話の番号はもうないはず');
        $this->assertFalse($this->Model->telephone(array('030-0000-0000')), '自動車電話、沿岸船舶電話の番号はもうないはず');
        $this->assertFalse($this->Model->telephone(array('03000000000')), '自動車電話、沿岸船舶電話の番号はもうないはず');
        $this->assertFalse($this->Model->telephone(array('040-000-00000')), '自動車電話、沿岸船舶電話の番号はもうないはず');
        $this->assertFalse($this->Model->telephone(array('040-0000-0000')), '自動車電話、沿岸船舶電話の番号はもうないはず');
        $this->assertFalse($this->Model->telephone(array('04000000000')), '自動車電話、沿岸船舶電話の番号はもうないはず');

        $this->assertFalse($this->Model->telephone(array('010-000-00000')), '「010」は国際接続番号なので無視');
        $this->assertFalse($this->Model->telephone(array('010-000-00000')), '「010」は国際接続番号なので無視');
        $this->assertFalse($this->Model->telephone(array('01000000000')), '「010」は国際接続番号なので無視');
    }

    public function test：バリデーション「postcode」単純なOKパターンのテスト() {
        $this->assertTrue($this->Model->postcode(array('984-8545')), 'ハイフンあり');
        $this->assertTrue($this->Model->postcode(array('9848545')), 'ハイフンなし');
        $this->assertTrue($this->Model->postcode(array('0000000')), '全てゼロ');
        $this->assertTrue($this->Model->postcode(array('9999999')), '全て九');
    }

    public function test：バリデーション「postcode」単純なNGパターンのテスト() {
        $this->assertFalse($this->Model->postcode(array('123-45678')), '下に1文字多い');
        $this->assertFalse($this->Model->postcode(array('1234-5678')), '上に1文字多い');
        $this->assertFalse($this->Model->postcode(array('12345678')), '1文字多い');
        $this->assertFalse($this->Model->postcode(array('123-456')), '下で1文字少ない');
        $this->assertFalse($this->Model->postcode(array('12-3456')), '上で1文字少ない');
        $this->assertFalse($this->Model->postcode(array('123456')), '1文字少ない');
        $this->assertFalse($this->Model->postcode(array('foo-hoge')), '数字以外');
        $this->assertFalse($this->Model->postcode(array('foohoge')), '数字以外');
        $this->assertFalse($this->Model->postcode(array('o12-34567')), '数字以外');
        $this->assertFalse($this->Model->postcode(array('')), 'EMPTY');
        $this->assertFalse($this->Model->postcode(array(null)), 'NULL');
    }

    public function test：バリデーション「postcode」で成功する値を全てテスト() {
        // for ($i = 0; $i < 9999999; ++$i) {
        //     $postcode = sprintf('%07d', $i);
        //     $this->assertTrue($this->Model->postcode(array($postcode)), $postcode);
        // 
        //     $postcode = substr($postcode, 0, 3).'-'.substr($postcode, 3, 4);
        //     $this->assertTrue($this->Model->postcode(array($postcode)), $postcode);
        // }
    }

    public function test：バリデーション「hiragana」ひらがなをテスト() {
        $this->assertTrue($this->Model->hiragana(array('わがはいはねこである')), 'ひらがなのみ');
        $this->assertFalse($this->Model->hiragana(array('吾輩は猫である')), '漢字あり');
    }

    public function test：バリデーション「hiragana」許容する全てのひらがなをテスト() {
        $ひらがな = 'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをんー　';
        $this->assertTrue($this->Model->hiragana(array($ひらがな)));
        for ($i = 0, $n = mb_strlen($ひらがな); $i < $n; $i++) {
            $文字 = mb_substr($ひらがな, $i, 1);
            $this->assertTrue($this->Model->hiragana(array($文字)), "「{$文字}」は、ひらがななのでOK");
        }
    }

    public function test：バリデーション「hiragana」問題になりそうな、ひらがなではない文字をテスト() {
        $this->assertFalse($this->Model->hiragana(array('ヴ')), '「ヴ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヵ')), '「ヵ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヶ')), '「ヶ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヷ')), '「ヷ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヸ')), '「ヸ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヹ')), '「ヹ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヺ')), '「ヺ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('゛')), '「゛」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('゜')), '「゜」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ゝ')), '「ゝ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ゞ')), '「ゞ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヽ')), '「ヽ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('ヾ')), '「ヾ」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('。')), '「。」は、ひらがなではない');
        $this->assertFalse($this->Model->hiragana(array('、')), '「、」は、ひらがなではない');

        $this->assertFalse($this->Model->hiragana(array(' ')), '半角スペースはノー');
        $this->assertFalse($this->Model->hiragana(array("\t")), 'タブはノー');
        $this->assertFalse($this->Model->hiragana(array("\r")), 'CRはノー');
        $this->assertFalse($this->Model->hiragana(array("\n")), 'LFはノー');
        $this->assertFalse($this->Model->hiragana(array("\r\n")), 'CRLFはノー');
    }

    public function test：バリデーション「hiragana」カタカナをテスト() {
        $this->assertFalse($this->Model->hiragana(array('ア')), '「ア」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('イ')), '「イ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ウ')), '「ウ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('エ')), '「エ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('オ')), '「オ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('カ')), '「カ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('キ')), '「キ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ク')), '「ク」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ケ')), '「ケ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('コ')), '「コ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('サ')), '「サ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('シ')), '「シ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ス')), '「ス」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('セ')), '「セ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ソ')), '「ソ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('タ')), '「タ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('チ')), '「チ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ツ')), '「ツ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('テ')), '「テ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ト')), '「ト」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ナ')), '「ナ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ニ')), '「ニ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヌ')), '「ヌ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ネ')), '「ネ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ノ')), '「ノ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ハ')), '「ハ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヒ')), '「ヒ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('フ')), '「フ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヘ')), '「ヘ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ホ')), '「ホ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('マ')), '「マ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ミ')), '「ミ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ム')), '「ム」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('メ')), '「メ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('モ')), '「モ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヤ')), '「ヤ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ユ')), '「ユ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヨ')), '「ヨ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ラ')), '「ラ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('リ')), '「リ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ル')), '「ル」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('レ')), '「レ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ロ')), '「ロ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ワ')), '「ワ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヰ')), '「ヰ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヱ')), '「ヱ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ヲ')), '「ヲ」は、カタカナなのでNO');
        $this->assertFalse($this->Model->hiragana(array('ン')), '「ン」は、カタカナなのでNO');
    }

    public function test：バリデーション「hiragana」ASCIIをテスト() {
        for ($i = 0; $i <= 255; ++$i) {
            $c = chr($i);
            $this->assertFalse($this->Model->hiragana(array($c)), "ASCII（{$i}）はひらがなではない");
        }
    }

    public function test：バリデーション「hiragana」記号をテスト() {
        $this->assertFalse($this->Model->hiragana(array('！')), '！は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('”')), '”は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＃')), '＃は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＄')), '＄は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('％')), '％は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＆')), '＆は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('’')), '’は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('（')), '（は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('）')), '）は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＝')), '＝は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('～')), '～は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('｜')), '｜は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('｀')), '｀は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('｛')), '｛は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＋')), '＋は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＊')), '＊は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('｝')), '｝は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＜')), '＜は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＞')), '＞は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('？')), '？は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('＿')), '＿は、記号なのでNO');
        $this->assertFalse($this->Model->hiragana(array('１')), '１は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('２')), '２は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('３')), '３は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('４')), '４は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('５')), '５は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('６')), '６は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('７')), '７は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('８')), '８は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('９')), '９は、全角数字なのでNO');
        $this->assertFalse($this->Model->hiragana(array('０')), '０は、全角数字なのでNO');
    }

    public function test：バリデーション「katakana」カタカナをテスト() {
        $this->assertTrue($this->Model->katakana(array('ワガハイハネコデアル')), 'カタカナのみ');
        $this->assertFalse($this->Model->katakana(array('吾輩は猫である')), '漢字あり');
    }

    public function test：バリデーション「katakana」許容する全てのカタカナをテスト() {
        $カタカナ = 'ァアィイゥウェエォオカガキギクグケゲコゴサザシジスズセゼソゾタダチヂッツヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモャヤュユョヨラリルレロヮワヰヱヲンー　';
        $this->assertTrue($this->Model->katakana(array($カタカナ)));
        for ($i = 0, $n = mb_strlen($カタカナ); $i < $n; $i++) {
            $文字 = mb_substr($カタカナ, $i, 1);
            $this->assertTrue($this->Model->katakana(array($文字)), "「{$文字}」は、カタカナなのでOK");
        }
    }

    public function test：バリデーション「katakana」問題になりそうな、カタカナではない文字をテスト() {
        $this->assertFalse($this->Model->katakana(array('ヵ')), '「ヵ」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('ヶ')), '「ヶ」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('ヷ')), '「ヷ」は、ひらがなではない');
        $this->assertFalse($this->Model->katakana(array('ヸ')), '「ヸ」は、ひらがなではない');
        $this->assertFalse($this->Model->katakana(array('ヹ')), '「ヹ」は、ひらがなではない');
        $this->assertFalse($this->Model->katakana(array('ヺ')), '「ヺ」は、ひらがなではない');
        $this->assertFalse($this->Model->katakana(array('゛')), '「゛」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('゜')), '「゜」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('ゝ')), '「ゝ」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('ゞ')), '「ゞ」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('ヽ')), '「ヽ」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('ヾ')), '「ヾ」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('。')), '「。」は、カタカナではない');
        $this->assertFalse($this->Model->katakana(array('、')), '「、」は、カタカナではない');

        $this->assertFalse($this->Model->katakana(array(' ')), '半角スペースはノー');
        $this->assertFalse($this->Model->katakana(array("\t")), 'タブはノー');
        $this->assertFalse($this->Model->katakana(array("\r")), 'CRはノー');
        $this->assertFalse($this->Model->katakana(array("\n")), 'LFはノー');
        $this->assertFalse($this->Model->katakana(array("\r\n")), 'CRLFはノー');
    }

    public function test：バリデーション「katakana」ひらがなをテスト() {
        $ひらがな = 'ぁあぃいぅうぇえぉおかがきぎくぐけげこごさざしじすずせぜそぞただちぢっつづてでとどなにぬねのはばぱひびぴふぶぷへべぺほぼぽまみむめもゃやゅゆょよらりるれろゎわゐゑをん';
        $this->assertFalse($this->Model->katakana(array($ひらがな)));
        for ($i = 0, $n = mb_strlen($ひらがな); $i < $n; $i++) {
            $文字 = mb_substr($ひらがな, $i, 1);
            $this->assertFalse($this->Model->katakana(array($文字)), "「{$文字}」は、ひらがななのでNG");
        }
    }

    public function test：バリデーション「katakana」ASCIIをテスト() {
        for ($i = 0; $i <= 255; ++$i) {
            $c = chr($i);
            $this->assertFalse($this->Model->katakana(array($c)), "ASCII（{$i}）はカタカナではない");
        }
    }

    public function test：バリデーション「katakana」記号をテスト() {
        $this->assertFalse($this->Model->katakana(array('！')), '！は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('”')), '”は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＃')), '＃は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＄')), '＄は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('％')), '％は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＆')), '＆は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('’')), '’は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('（')), '（は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('）')), '）は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＝')), '＝は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('～')), '～は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('｜')), '｜は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('｀')), '｀は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('｛')), '｛は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＋')), '＋は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＊')), '＊は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('｝')), '｝は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＜')), '＜は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＞')), '＞は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('？')), '？は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('＿')), '＿は、記号なのでNO');
        $this->assertFalse($this->Model->katakana(array('１')), '１は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('２')), '２は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('３')), '３は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('４')), '４は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('５')), '５は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('６')), '６は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('７')), '７は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('８')), '８は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('９')), '９は、全角数字なのでNO');
        $this->assertFalse($this->Model->katakana(array('０')), '０は、全角数字なのでNO');
    }

    public function test：バリデーション「notSjisGaiji」代表的な外字でテスト() {
        $this->assertFalse($this->Model->notSjisGaiji(array('髙村薫')));
        $this->assertFalse($this->Model->notSjisGaiji(array('內田百閒')));
        $this->assertFalse($this->Model->notSjisGaiji(array('手塚治虫')));
        $this->assertFalse($this->Model->notSjisGaiji(array('德永英明')));
        $this->assertFalse($this->Model->notSjisGaiji(array('宮﨑あおい')));
        $this->assertFalse($this->Model->notSjisGaiji(array('草彅剛')));
        $this->assertFalse($this->Model->notSjisGaiji(array('里見弴')));
        $this->assertFalse($this->Model->notSjisGaiji(array('李承燁')));
        $this->assertFalse($this->Model->notSjisGaiji(array('鄭珉台')));
        $this->assertFalse($this->Model->notSjisGaiji(array('鄧小平')));
    }

    public function test：バリデーション「notSjisGaiji」NEC特殊記号をテスト() {
        $this->assertFalse($this->Model->notSjisGaiji(array('①')));
        $this->assertFalse($this->Model->notSjisGaiji(array('②')));
        $this->assertFalse($this->Model->notSjisGaiji(array('③')));
        $this->assertFalse($this->Model->notSjisGaiji(array('④')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑤')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑥')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑦')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑧')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑨')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑩')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑪')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑫')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑬')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑭')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑮')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑯')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑰')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑱')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑲')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⑳')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅰ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅱ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅲ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅳ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅴ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅵ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅶ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅷ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅸ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('Ⅹ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍉')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌔')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌢')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍍')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌘')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌧')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌃')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌶')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍑')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍗')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌍')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌦')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌣')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌫')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍊')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㌻')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㎜')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㎝')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㎞')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㎎')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㎏')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㏄')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㎡')));
        $this->assertFalse($this->Model->notSjisGaiji(array('〝')));
        $this->assertFalse($this->Model->notSjisGaiji(array('〟')));
        $this->assertFalse($this->Model->notSjisGaiji(array('№')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㏍')));
        $this->assertFalse($this->Model->notSjisGaiji(array('℡')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㊤')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㊥')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㊦')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㊧')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㊨')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㈱')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㈲')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㈹')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍾')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍽')));
        $this->assertFalse($this->Model->notSjisGaiji(array('㍼')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('≒')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('≡')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('∫')));
        $this->assertFalse($this->Model->notSjisGaiji(array('∮')));
        $this->assertFalse($this->Model->notSjisGaiji(array('∑')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('√')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('⊥')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('∠')));
        $this->assertFalse($this->Model->notSjisGaiji(array('∟')));
        $this->assertFalse($this->Model->notSjisGaiji(array('⊿')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('∵')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('∩')));
        // $this->assertFalse($this->Model->notSjisGaiji(array('∪')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅰ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅱ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅲ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅳ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅴ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅵ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅶ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅷ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅸ')));
        $this->assertFalse($this->Model->notSjisGaiji(array('ⅹ')));
    }

    public function test：バリデーション「compareConfirmation」入力が一致しないテスト() {
        $model = new TestCompareConfirmationRuleModel;

        $model->data = array('Foo' => array('name' => 'foo', 'name_confirm' => 'bar'));
        $this->assertFalse($model->validates(), 'nameとconfirmが不一致');

        $model->data = array('Foo' => array('num' => '42', 'name_check' => '42'));
        $this->assertFalse($model->validates(), 'suffixが間違っている');

        $model->validate = array('name' => array('num' => array('compareConfirmation', '_check')));
        $model->data = array('Foo' => array('num' => '42', 'name_confirm' => '42'));
        $this->assertFalse($model->validates(), 'suffixが間違っている');
    }

    public function test：バリデーション「compareConfirmation」比較のテスト() {
        $model = new TestCompareConfirmationRuleModel;

        $model->data = array('Foo' => array('name' => '', 'name_confirm' => '0'));
        $this->assertFalse($model->validates(), 'EMPTYと0は一致しない');

        $model->data = array('Foo' => array('name' => 1, 'name_confirm' => true));
        $this->assertFalse($model->validates(), '1とTRUEは一致しない');
    }

    public function test：バリデーション「notEmptyPassword」パスワード未入力をテスト() {
        $ユーザ入力 = 'opensesame';
        $パスワード = Security::hash($ユーザ入力, null, true);
        $this->assertTrue($this->Model->notEmptyPassword(array($パスワード)), '入力あり');

        $ユーザ入力 = '';
        $パスワード = Security::hash($ユーザ入力, null, true);
        $this->assertFalse($this->Model->notEmptyPassword(array($パスワード)), '入力なし');
    }

    public function test：日付のグレゴリオ暦妥当性をバリデーション() {
        $閏年の29日 = array('birthday' => '2012-02-29');
        $this->assertTrue($this->Model->isDate($閏年の29日));

        $十三月一日 = array('birthday' => '2012-13-01');
        $this->assertFalse($this->Model->isDate($十三月一日));

        $一月三二日 = array('birthday' => '2012-01-32');
        $this->assertFalse($this->Model->isDate($十三月一日));
    }

    public function test：「isDate」メソッドの既知のバグ() {
        // 平年の29日を、内部で翌日（2013-03-01）に計算するバグあり
        $平年の29日 = array('birthday' => '2013-02-29');
        // $this->assertFalse($this->Model->isDate($平年の29日));
        $this->assertTrue($this->Model->isDate($平年の29日));

        // 存在しない31日を、内部で翌月1日に計算するバグあり
        $四月三十一日 = array('birthday' => '2012-04-31');
        // $this->assertFalse($this->Model->isDate($四月三十一日));
        $this->assertTrue($this->Model->isDate($四月三十一日));
    }

    public function test：年数の有効範囲（最長年数、デフォルト120年）をバリデーション() {
        $_SERVER['REQUEST_TIME'] = strtotime('2012-08-30 00:00:00');

        $有効な範囲 = array(
                '昭和55年'       => '1980-09-17',
                '明治24年元日'   => '1893-01-01',
                '明治24年大晦日' => '1893-12-31',
                '明治25年元日'   => '1892-01-01',
                '明治25年大晦日' => '1892-12-31',
            );
        foreach ($有効な範囲 as $年度 => $日付) {
            $ユーザ入力 = array('birthday' => $日付);
            $this->assertTrue($this->Model->oldestYear($ユーザ入力), "{$年度}は有効範囲");
        }

        $有効な範囲 = array(
                '昭和55年'       => '1980-09-17',
                '明治26年元日'   => '1893-01-01',
                '明治26年大晦日' => '1893-12-31',
                '明治25年元日'   => '1892-01-01',
                '明治25年大晦日' => '1892-12-31',
            );
        foreach ($有効な範囲 as $年度 => $日付) {
            $ユーザ入力 = array('birthday' => $日付);
            $this->assertTrue($this->Model->oldestYear($ユーザ入力), "{$年度}は有効範囲");
        }

        $無効な範囲 = array(
                '明治24年元日'   => '1891-01-01',
                '明治24年大晦日' => '1891-12-31',
                '明治23年元日'   => '1890-01-01',
                '明治23年大晦日' => '1890-12-31',
            );
        foreach ($無効な範囲 as $年度 => $日付) {
            $ユーザ入力 = array('birthday' => $日付);
            $this->assertFalse($this->Model->oldestYear($ユーザ入力), "{$年度}は有効な範囲外");
        }
    }

    public function test：年数の有効範囲（最長年数、引数で）をバリデーション() {
        $_SERVER['REQUEST_TIME'] = strtotime('2012-08-30 00:00:00');

        $昭和55年 = array('birthday' => '1980-09-17');
        $this->assertTrue($this->Model->oldestYear($昭和55年, 33));

        $昭和55年 = array('birthday' => '1980-09-17');
        $this->assertTrue($this->Model->oldestYear($昭和55年, 32));

        $昭和55年 = array('birthday' => '1980-09-17');
        $this->assertFalse($this->Model->oldestYear($昭和55年, 31));

        $昭和55年 = array('birthday' => '1980-09-17');
        $this->assertFalse($this->Model->oldestYear($昭和55年, 30));

        $平成4年 = array('birthday' => '1992-09-17');
        $this->assertTrue($this->Model->oldestYear($平成4年, 21));

        $平成4年 = array('birthday' => '1992-09-17');
        $this->assertTrue($this->Model->oldestYear($平成4年, 20));

        $平成4年 = array('birthday' => '1992-09-17');
        $this->assertFalse($this->Model->oldestYear($平成4年, 19));
    }

    public function test：日付が現在日時よりも過去かをバリデーション() {
        $_SERVER['REQUEST_TIME'] = strtotime('2012-08-30 00:00:00');

        $当日 = array('birthday' => '2012-08-30');
        $this->assertTrue($this->Model->lessThanToday($当日));

        $昨日 = array('birthday' => '2012-08-29');
        $this->assertTrue($this->Model->lessThanToday($昨日));

        $明日 = array('birthday' => '2012-08-31');
        $this->assertFalse($this->Model->lessThanToday($明日));

        $_SERVER['REQUEST_TIME'] = strtotime('2012-04-02 00:00:00');

        $当日 = array('birthday' => '2012-04-02');
        $this->assertTrue($this->Model->lessThanToday($当日));

        $昨日 = array('birthday' => '2012-04-01');
        $this->assertTrue($this->Model->lessThanToday($昨日));

        $明日 = array('birthday' => '2012-04-03');
        $this->assertFalse($this->Model->lessThanToday($明日));
    }

    public function test：有効期限が現在日時を過ぎていないかバリデーション() {
        $_SERVER['REQUEST_TIME'] = strtotime('2012-08-30 00:00:00');

        $今月 = array('expiration' => '2012-08-01');
        $this->assertTrue($this->Model->greaterThanExpiration($今月));

        $来月 = array('expiration' => '2012-09-01');
        $this->assertTrue($this->Model->greaterThanExpiration($来月));

        $先月 = array('expiration' => '2012-07-01');
        $this->assertFalse($this->Model->greaterThanExpiration($先月));

        $_SERVER['REQUEST_TIME'] = strtotime('1999-12-01 00:00:00');

        $今月 = array('expiration' => '1999-12-01');
        $this->assertTrue($this->Model->greaterThanExpiration($今月));

        $来月 = array('expiration' => '2000-01-01');
        $this->assertTrue($this->Model->greaterThanExpiration($来月));

        $先月 = array('expiration' => '1999-11-01');
        $this->assertFalse($this->Model->greaterThanExpiration($先月));
    }
}
