<?php
namespace Yukar\Sql\Tests\Builder\Common\Objects;

use Yukar\Sql\Builder\Common\Objects\DelimitedIdentifier;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス DelimitedIdentifier の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Objects
 * @author hiroki sugawara
 */
class DelimitedIdentifierTest extends CustomizedTestCase
{
    private const STATIC_PROP_NAME_INSTANCE = 'instance';
    private const PROP_NAME_QUOTE_TYPE = 'quote_type';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return DelimitedIdentifier::class;
    }

    /**
     * 単体テスト対象となるクラスのテストが全て終わった時に最後に実行します。
     *
     * @throws \ReflectionException
     */
    public static function tearDownAfterClass()
    {
        $object = new \ReflectionClass(DelimitedIdentifier::class);
        $property = $object->getProperty(self::STATIC_PROP_NAME_INSTANCE);
        $property->setAccessible(true);
        $property->setValue($object, null);
    }

    /**
     * 単体テスト対象となるクラスのリフレクションオブジェクトを取得します。
     *
     * @param DelimitedIdentifier $target リフレクションの対象となるクラスのオブジェクト
     *
     * @return \ReflectionClass 単体テスト対象となるクラスのリフレクションオブジェクト
     */
    private function getReflection(DelimitedIdentifier $target = null): ?\ReflectionClass
    {
        try {
            $reflection = $this->getReflectionClass(isset($target) ? $target : DelimitedIdentifier::class);
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
            $reflection = null;
        }

        return $reflection;
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
    public function testInit(): void
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init();

        $this->assertInstanceOf(DelimitedIdentifier::class, $r_instance->getValue($reflection));
    }

    /**
     * メソッド testIsAlreadyInit のデータプロバイダー
     *
     * @return array
     */
    public function providerIsAlreadyInit(): array
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
    public function testIsAlreadyInit($expected, $init_flag): void
    {
        $this->resetProperties();
        ($init_flag === true) && DelimitedIdentifier::init();

        $this->assertSame($expected, DelimitedIdentifier::isAlreadyInit());
    }

    /**
     * 正常系テスト
     */
    public function testGet(): void
    {
        $this->resetProperties();
        DelimitedIdentifier::init();

        $this->assertInstanceOf(DelimitedIdentifier::class, DelimitedIdentifier::get());
    }

    /**
     * 異常系テスト
     */
    public function testGetFailure(): void
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
    public function providerGetQuoteType(): array
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
    public function testGetQuoteType($expected, $prop_value): void
    {
        $this->resetProperties();
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_QUOTE_TYPE)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getQuoteType());
    }

    /**
     * メソッド testSetQuoteType のデータプロバイダー
     *
     * @return array
     */
    public function providerSetQuoteType(): array
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
    public function testSetQuoteType($expected, $prop_value, $quote_type): void
    {
        $this->resetProperties();
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_QUOTE_TYPE);
        $reflector->setValue($object, $prop_value);
        $object->setQuoteType($quote_type);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testGetQuotedString と testQuoted のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuotedString(): array
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
    public function testGetQuotedString($expected, $quote_type, $text): void
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init($quote_type);
        /** @var DelimitedIdentifier $object */
        $object = $r_instance->getValue($reflection);

        $this->assertSame($expected, $object->getQuotedString($text));
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
    public function testQuoted($expected, $quote_type, $text): void
    {
        $this->resetProperties();
        DelimitedIdentifier::init($quote_type);

        $this->assertSame($expected, DelimitedIdentifier::quoted($text));
    }

    /**
     * メソッド testGetQuotedList と testQuotedRange のデータプロバイダー
     *
     * @return array
     */
    public function providerGetQuotedList(): array
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
    public function testGetQuotedList($expected, $quote_type, $texts): void
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init($quote_type);
        /** @var DelimitedIdentifier $object */
        $object = $r_instance->getValue($reflection);

        $this->assertSame($expected, $object->getQuotedList($texts));
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
    public function testQuotedRange($expected, $quote_type, $texts): void
    {
        $this->resetProperties();
        DelimitedIdentifier::init($quote_type);

        $this->assertSame($expected, DelimitedIdentifier::quotedRange($texts));
    }

    /**
     * メソッド testGetMultiQuotedList のデータプロバイダー
     *
     * @return array
     */
    public function providerGetMultiQuotedList(): array
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
    public function testGetMultiQuotedList($expected, $quote_type, $texts, $delimiter): void
    {
        /** @var \ReflectionProperty $r_instance */
        list($reflection, $r_instance) = $this->resetProperties();
        DelimitedIdentifier::init($quote_type);
        /** @var DelimitedIdentifier $object */
        $object = $r_instance->getValue($reflection);

        $this->assertSame($expected, $object->getMultiQuotedList($texts, $delimiter));
    }
}
