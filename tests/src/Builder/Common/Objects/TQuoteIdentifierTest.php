<?php
namespace Yukar\Sql\Tests\Builder\Objects;

use Yukar\Sql\Builder\Objects\DelimitedIdentifier;
use Yukar\Sql\Builder\Objects\TQuoteIdentifier;

/**
 * トレイト TQuoteIdentifier の単体テスト
 *
 * @author hiroki sugawara
 */
class TQuoteIdentifierTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_DELIMITED_IDENTIFIER = 'delimited_identifier';
    const METHOD_NAME_IS_PREPARED_QUOTE = 'isPreparedQuote';
    const METHOD_NAME_GET_QUOTED_STRING = 'getQuotedString';
    const METHOD_NAME_GET_QUOTED_LIST = 'getQuotedList';
    const METHOD_NAME_GET_MULTI_QUOTED_LIST = 'getMultiQuotedList';

    /**
     * 単体テスト対象となるクラスのテストが全て終わった時に最後に実行します。
     */
    public static function tearDownAfterClass()
    {
        $object = new \ReflectionClass(DelimitedIdentifier::class);
        $property = $object->getProperty('quote_type');
        $property->setAccessible(true);
        $property->setValue($object, null);
    }

    /**
     * 単体テスト対象となるトレイトのモックオブジェクトを取得します。
     *
     * @return \PHPUnit_Framework_MockObject_MockObject 単体テスト対象となるトレイトのモックオブジェクト
     */
    private function getMockInstance(): \PHPUnit_Framework_MockObject_MockObject
    {
        return $this->getMockForTrait(TQuoteIdentifier::class);
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param object $object        単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    private function getProperty($object, string $property_name): \ReflectionProperty
    {
        $property = (new \ReflectionClass($object))->getParentClass()->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のメソッドのリフレクションインスタンスを取得します。
     *
     * @param object $object      単体テスト対象となるクラスのインスタンス
     * @param string $method_name リフレクションを取得するメソッドの名前
     *
     * @return \ReflectionMethod 指定した名前のメソッドのリフレクションを持つインスタンス
     */
    private function getMethod($object, string $method_name): \ReflectionMethod
    {
        $method = (new \ReflectionClass($object))->getParentClass()->getMethod($method_name);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * メソッド testGetDelimitedIdentifier のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDelimitedIdentifier()
    {
        return [
            [ DelimitedIdentifier::NONE_QUOTES_TYPE, DelimitedIdentifier::NONE_QUOTES_TYPE ],
            [ DelimitedIdentifier::ANSI_QUOTES_TYPE, DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ DelimitedIdentifier::MYSQL_QUOTES_TYPE, DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetDelimitedIdentifier
     *
     * @param int $expected   期待値
     * @param int $prop_value メソッド init の引数 quote_type に渡す値
     */
    public function testGetDelimitedIdentifier($expected, $prop_value)
    {
        DelimitedIdentifier::init($prop_value);

        /** @var TQuoteIdentifier $object */
        $object = $this->getMockInstance();
        $this->getProperty($object, self::PROP_NAME_DELIMITED_IDENTIFIER)
            ->setValue($object, DelimitedIdentifier::get());

        self::assertSame(DelimitedIdentifier::get(), $object->getDelimitedIdentifier());
        self::assertSame($expected, $object->getDelimitedIdentifier()->getQuoteType());
    }

    /**
     * メソッド testSetDelimitedIdentifier のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDelimitedIdentifier()
    {
        return [
            [ DelimitedIdentifier::NONE_QUOTES_TYPE, null, DelimitedIdentifier::NONE_QUOTES_TYPE ],
            [ DelimitedIdentifier::ANSI_QUOTES_TYPE, -1, DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ DelimitedIdentifier::MYSQL_QUOTES_TYPE, 0, DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, 1, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
            [ DelimitedIdentifier::NONE_QUOTES_TYPE, 2, DelimitedIdentifier::NONE_QUOTES_TYPE ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetDelimitedIdentifier
     *
     * @param int $expected   期待値
     * @param int $prop_value メソッド init の引数 quote_type に渡す値（初期化に渡す）
     * @param int $set_value  メソッド init の引数 quote_type に渡す値（セッターメソッド直前）
     */
    public function testSetDelimitedIdentifier($expected, $prop_value, $set_value)
    {
        DelimitedIdentifier::init($prop_value ?? DelimitedIdentifier::NONE_QUOTES_TYPE);

        /** @var TQuoteIdentifier $object */
        $object = $this->getMockInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DELIMITED_IDENTIFIER);
        $reflector->setValue($object, DelimitedIdentifier::get());

        DelimitedIdentifier::init($set_value);
        $object->setDelimitedIdentifier(DelimitedIdentifier::get());

        self::assertSame(DelimitedIdentifier::get(), $reflector->getValue($object));
        self::assertSame($expected, $reflector->getValue($object)->getQuoteType());
    }

    /**
     * メソッド testIsPreparedQuote のデータプロバイダー
     *
     * @return array
     */
    public function providerIsPreparedQuote()
    {
        return [
            [ false, null ],
            [ true, DelimitedIdentifier::NONE_QUOTES_TYPE ],
            [ true, DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ true, DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ true, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerIsPreparedQuote
     *
     * @param bool $expected        期待値
     * @param int|null $prop_value  メソッド init の引数 quote_type に渡す値（初期化に渡す）
     */
    public function testIsPreparedQuote($expected, $prop_value)
    {
        isset($prop_value) && DelimitedIdentifier::init($prop_value);

        /** @var TQuoteIdentifier $object */
        $object = $this->getMockInstance();
        $prop_reflector = $this->getProperty($object, self::PROP_NAME_DELIMITED_IDENTIFIER);
        $method_reflector = $this->getMethod($object, self::METHOD_NAME_IS_PREPARED_QUOTE);

        isset($prop_value) && $prop_reflector->setValue($object, DelimitedIdentifier::get());

        self::assertSame($expected, $method_reflector->invoke($object));
    }

    /**
     * メソッド testGetQuotedString のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuotedString()
    {
        return [
            [ 'a', null, 'a' ],
            [ 'b', DelimitedIdentifier::NONE_QUOTES_TYPE, 'b' ],
            [ '"c"', DelimitedIdentifier::ANSI_QUOTES_TYPE, 'c' ],
            [ '`d`', DelimitedIdentifier::MYSQL_QUOTES_TYPE, 'd' ],
            [ '[e]', DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, 'e' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuotedString
     *
     * @param string $expected      期待値
     * @param int|null $prop_value  メソッド init の引数 quote_type に渡す値（初期化に渡す）
     * @param string $text          メソッド getQuotedString の引数 text に渡す値
     */
    public function testGetQuotedString($expected, $prop_value, $text)
    {
        isset($prop_value) && DelimitedIdentifier::init($prop_value);

        /** @var TQuoteIdentifier $object */
        $object = $this->getMockInstance();
        $prop_reflector = $this->getProperty($object, self::PROP_NAME_DELIMITED_IDENTIFIER);
        $method_reflector = $this->getMethod($object, self::METHOD_NAME_GET_QUOTED_STRING);

        isset($prop_value) && $prop_reflector->setValue($object, DelimitedIdentifier::get());

        self::assertSame($expected, $method_reflector->invoke($object, $text));
    }

    /**
     * メソッド testGetQuotedList のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuotedList()
    {
        $base_list = [ 'a', 'b', 'c' ];

        return [
            [ [ 'a', 'b', 'c' ], null, $base_list ],
            [ [ 'a', 'b', 'c' ], DelimitedIdentifier::NONE_QUOTES_TYPE, $base_list ],
            [ [ '"a"', '"b"', '"c"' ], DelimitedIdentifier::ANSI_QUOTES_TYPE, $base_list ],
            [ [ '`a`', '`b`', '`c`' ], DelimitedIdentifier::MYSQL_QUOTES_TYPE, $base_list ],
            [ [ '[a]', '[b]', '[c]' ], DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, $base_list ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuotedList
     *
     * @param string $expected      期待値
     * @param int|null $prop_value  メソッド init の引数 quote_type に渡す値（初期化に渡す）
     * @param array $list           メソッド getQuotedList の引数 list に渡す値
     */
    public function testGetQuotedList($expected, $prop_value, $list)
    {
        isset($prop_value) && DelimitedIdentifier::init($prop_value);

        /** @var TQuoteIdentifier $object */
        $object = $this->getMockInstance();
        $prop_reflector = $this->getProperty($object, self::PROP_NAME_DELIMITED_IDENTIFIER);
        $method_reflector = $this->getMethod($object, self::METHOD_NAME_GET_QUOTED_LIST);

        isset($prop_value) && $prop_reflector->setValue($object, DelimitedIdentifier::get());

        self::assertSame($expected, $method_reflector->invoke($object, $list));
    }

    /**
     * メソッド testGetMultiQuotedList のデータプロバイダー
     *
     * @return array
     */
    public function providerGetMultiQuotedList()
    {
        $base_list = [ 't.a', 't.b', 't.c' ];

        return [
            [ [ 't.a', 't.b', 't.c' ], null, $base_list, '.' ],
            [ [ 't.a', 't.b', 't.c' ], DelimitedIdentifier::NONE_QUOTES_TYPE, $base_list, '.' ],
            [ [ '"t"."a"', '"t"."b"', '"t"."c"' ], DelimitedIdentifier::ANSI_QUOTES_TYPE, $base_list, '.' ],
            [ [ '`t`.`a`', '`t`.`b`', '`t`.`c`' ], DelimitedIdentifier::MYSQL_QUOTES_TYPE, $base_list, '.' ],
            [ [ '[t].[a]', '[t].[b]', '[t].[c]' ], DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, $base_list, '.' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetMultiQuotedList
     *
     * @param string $expected      期待値
     * @param int|null $prop_value  メソッド init の引数 quote_type に渡す値（初期化に渡す）
     * @param array $list           メソッド getQuotedList の引数 list に渡す値
     * @param string $delimiter     メソッド getQuotedList の引数 line_delimiter に渡す値
     */
    public function testGetMultiQuotedList($expected, $prop_value, $list, $delimiter)
    {
        isset($prop_value) && DelimitedIdentifier::init($prop_value);

        /** @var TQuoteIdentifier $object */
        $object = $this->getMockInstance();
        $prop_reflector = $this->getProperty($object, self::PROP_NAME_DELIMITED_IDENTIFIER);
        $method_reflector = $this->getMethod($object, self::METHOD_NAME_GET_MULTI_QUOTED_LIST);

        isset($prop_value) && $prop_reflector->setValue($object, DelimitedIdentifier::get());

        self::assertSame($expected, $method_reflector->invoke($object, $list, $delimiter));
    }
}
