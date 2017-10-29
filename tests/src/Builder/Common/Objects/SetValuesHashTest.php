<?php
namespace Yukar\Sql\Tests\Builder\Common\Objects;

use Yukar\Sql\Builder\Common\Objects\DelimitedIdentifier;
use Yukar\Sql\Builder\Common\Objects\SetValuesHash;

/**
 * クラス SetValuesHash の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Objects
 * @author hiroki sugawara
 */
class SetValuesHashTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 単体テスト対象となるクラスのテストが全て終わった時に最後に実行します。
     */
    public static function tearDownAfterClass(): void
    {
        $object = new \ReflectionClass(DelimitedIdentifier::class);
        $property = $object->getProperty('quote_type');
        $property->setAccessible(true);
        $property->setValue($object, null);
    }

    /**
     * testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $single = [ 'col1' => 'val1' ];
        $double = [ 'col1' => 'val1', 'col2' => '20' ];

        return [
            [ 'col1 = \'val1\'', $single, null ],
            [ '"col1" = \'val1\'', $single, DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ '`col1` = \'val1\'', $single, DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ '[col1] = \'val1\'', $single, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
            [ 'col1 = \'val1\', col2 = 20', $double, null ],
            [ '"col1" = \'val1\', "col2" = 20', $double, DelimitedIdentifier::ANSI_QUOTES_TYPE ],
            [ '`col1` = \'val1\', `col2` = 20', $double, DelimitedIdentifier::MYSQL_QUOTES_TYPE ],
            [ '[col1] = \'val1\', [col2] = 20', $double, DelimitedIdentifier::SQL_SERVER_QUOTES_TYPE ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected   期待値
     * @param array  $input      コンストラクタの引数 input に渡す値
     * @param int    $quote_type コンストラクタの引数 identifier に渡す値
     */
    public function testToString($expected, $input, $quote_type): void
    {
        DelimitedIdentifier::init($quote_type ?? DelimitedIdentifier::NONE_QUOTES_TYPE);

        $this->assertSame($expected, (string)new SetValuesHash($input, DelimitedIdentifier::get()));
    }
}
