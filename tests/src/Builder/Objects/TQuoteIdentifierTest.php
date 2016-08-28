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
}
