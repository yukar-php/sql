<?php
namespace Yukar\Sql\Tests\Builder\Common\Objects;

use Yukar\Sql\Builder\Common\Objects\DelimitedIdentifier;

/**
 * クラス DelimitedIdentifier の単体テスト
 *
 * @author hiroki sugawara
 */
class DelimitedIdentifierTest extends \PHPUnit_Framework_TestCase
{
    const STATIC_PROP_NAME_INSTANCE = 'instance';
    const PROP_NAME_QUOTE_TYPE = 'quote_type';

    /**
     * 単体テスト対象となるクラスのテストが全て終わった時に最後に実行します。
     */
    public static function tearDownAfterClass()
    {
        $object = new \ReflectionClass(DelimitedIdentifier::class);
        $property = $object->getProperty(self::STATIC_PROP_NAME_INSTANCE);
        $property->setAccessible(true);

        $property->setValue($object, null);
    }

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return DelimitedIdentifier コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): DelimitedIdentifier
    {
        /** @var DelimitedIdentifier $instance */
        $instance = (new \ReflectionClass(DelimitedIdentifier::class))->newInstanceWithoutConstructor();

        return $instance;
    }

    /**
     * 単体テスト対象となるクラスのリフレクションオブジェクトを取得します。
     *
     * @param DelimitedIdentifier $target リフレクションの対象となるクラスのオブジェクト
     *
     * @return \ReflectionClass 単体テスト対象となるクラスのリフレクションオブジェクト
     */
    private function getReflection(DelimitedIdentifier $target = null): \ReflectionClass
    {
        return new \ReflectionClass(isset($target) ? $target : DelimitedIdentifier::class);
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param \ReflectionClass $object        単体テスト対象となるクラスのインスタンス
     * @param string           $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    private function getProperty(\ReflectionClass $object, string $property_name): \ReflectionProperty
    {
        $property = $object->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 対象クラスのプロパティの状態をリセットします。
     *
     * @return array
     */
    private function resetProperties(): array
    {
        $reflection = $this->getReflection();
        $r_instance = $this->getProperty($reflection, self::STATIC_PROP_NAME_INSTANCE);
        $r_instance->setValue(null);

        return [ $reflection, $r_instance ];
    }

    /**
     * 正常系テスト
     */
    public function testInit()
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init();

        self::assertInstanceOf(DelimitedIdentifier::class, $r_instance->getValue($reflection));
    }

    /**
     * メソッド testIsAlreadyInit のデータプロバイダー
     *
     * @return array
     */
    public function providerIsAlreadyInit()
    {
        return [
            [ false, false ],
            [ true, true ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerIsAlreadyInit
     *
     * @param bool $expected  期待値
     * @param bool $init_flag メソッド init を実行するかどうか
     */
    public function testIsAlreadyInit($expected, $init_flag)
    {
        $this->resetProperties();
        ($init_flag === true) && DelimitedIdentifier::init();

        self::assertSame($expected, DelimitedIdentifier::isAlreadyInit());
    }

    /**
     * 正常系テスト
     */
    public function testGet()
    {
        $this->resetProperties();
        DelimitedIdentifier::init();

        self::assertInstanceOf(DelimitedIdentifier::class, DelimitedIdentifier::get());
    }

    /**
     * 異常系テスト
     */
    public function testGetFailure()
    {
        $this->resetProperties();
        $this->expectException(\BadFunctionCallException::class);

        DelimitedIdentifier::get();
    }

    /**
     * メソッド testGetQuoteType のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuoteType()
    {
        return [
            [ -1, DelimitedIdentifier::NONE_QUOTES_TYPE ],
            [ 0, DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ 1, DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ 2, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuoteType
     *
     * @param int $expected   期待値
     * @param int $prop_value プロパティ quote_type の値
     */
    public function testGetQuoteType($expected, $prop_value)
    {
        $this->resetProperties();
        $object = $this->getNewInstance();
        $this->getProperty($this->getReflection($object), self::PROP_NAME_QUOTE_TYPE)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getQuoteType());
    }

    /**
     * メソッド testSetQuoteType のデータプロバイダー
     *
     * @return array
     */
    public function providerSetQuoteType()
    {
        return [
            [ -1, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, DelimitedIdentifier::NONE_QUOTES_TYPE ],
            [ 0, DelimitedIdentifier::NONE_QUOTES_TYPE, DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ 1, DelimitedIdentifier::ANSI_QUOTES_TYPE, DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ 2, DelimitedIdentifier::MYSQL_QUOTES_TYPE, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
            [ 0, DelimitedIdentifier::NONE_QUOTES_TYPE, -2 ],
            [ 0, DelimitedIdentifier::NONE_QUOTES_TYPE, 3 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetQuoteType
     *
     * @param int $expected   期待値
     * @param int $prop_value プロパティ quote_type の値
     * @param int $quote_type メソッド setQuoteType の引数 quote_type に渡す値
     */
    public function testSetQuoteType($expected, $prop_value, $quote_type)
    {
        $this->resetProperties();
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($this->getReflection($object), self::PROP_NAME_QUOTE_TYPE);
        $reflector->setValue($object, $prop_value);
        $object->setQuoteType($quote_type);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetQuotedString と testQuoted のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuotedString()
    {
        return [
            [ 'column', DelimitedIdentifier::NONE_QUOTES_TYPE, 'column' ],
            [ '"column"', DelimitedIdentifier::ANSI_QUOTES_TYPE, 'column' ],
            [ '`column`', DelimitedIdentifier::MYSQL_QUOTES_TYPE, 'column' ],
            [ '[column]', DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, 'column' ],
            [ '', DelimitedIdentifier::NONE_QUOTES_TYPE, '' ],
            [ '', DelimitedIdentifier::NONE_QUOTES_TYPE, '' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuotedString
     *
     * @param string $expected   期待値
     * @param int    $quote_type メソッド init の引数 quote_type に渡す値
     * @param string $text       メソッド getQuotedString の引数 text に渡す値
     */
    public function testGetQuotedString($expected, $quote_type, $text)
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init($quote_type);
        /** @var DelimitedIdentifier $object */
        $object = $r_instance->getValue($reflection);

        self::assertSame($expected, $object->getQuotedString($text));
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuotedString
     *
     * @param string $expected   期待値
     * @param int    $quote_type メソッド init の引数 quote_type に渡す値
     * @param string $text       メソッド quoted の引数 text に渡す値
     */
    public function testQuoted($expected, $quote_type, $text)
    {
        $this->resetProperties();
        DelimitedIdentifier::init($quote_type);

        self::assertSame($expected, DelimitedIdentifier::quoted($text));
    }

    /**
     * メソッド testGetQuotedList と testQuotedRange のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuotedList()
    {
        return [
            [ [ 'a', 'b', 'c' ], DelimitedIdentifier::NONE_QUOTES_TYPE, [ 'a', 'b', 'c' ] ],
            [ [ '"a"', '"b"', '"c"' ], DelimitedIdentifier::ANSI_QUOTES_TYPE, [ 'a', 'b', 'c' ] ],
            [ [ '`a`', '`b`', '`c`' ], DelimitedIdentifier::MYSQL_QUOTES_TYPE, [ 'a', 'b', 'c' ] ],
            [ [ '[a]', '[b]', '[c]' ], DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE, [ 'a', 'b', 'c' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuotedList
     *
     * @param array $expected   期待値
     * @param int   $quote_type メソッド init の引数 quote_type に渡す値
     * @param array $texts      メソッド getQuotedList の引数 list に渡す値
     */
    public function testGetQuotedList($expected, $quote_type, $texts)
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init($quote_type);
        /** @var DelimitedIdentifier $object */
        $object = $r_instance->getValue($reflection);

        self::assertSame($expected, $object->getQuotedList($texts));
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetQuotedList
     *
     * @param array $expected   期待値
     * @param int   $quote_type メソッド init の引数 quote_type に渡す値
     * @param array $texts      メソッド quotedRange の引数 list に渡す値
     */
    public function testQuotedRange($expected, $quote_type, $texts)
    {
        $this->resetProperties();
        DelimitedIdentifier::init($quote_type);

        self::assertSame($expected, DelimitedIdentifier::quotedRange($texts));
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
     * @param array $expected   期待値
     * @param int   $quote_type メソッド init の引数 quote_type に渡す値
     * @param array $texts      メソッド getMultiQuotedList の引数 list に渡す値
     * @param string $delimiter メソッド getMultiQuotedList の引数 line_delimiter に渡す値
     */
    public function testGetMultiQuotedList($expected, $quote_type, $texts, $delimiter)
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init($quote_type);
        /** @var DelimitedIdentifier $object */
        $object = $r_instance->getValue($reflection);

        self::assertSame($expected, $object->getMultiQuotedList($texts, $delimiter));
    }
}
