<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\SetValuesHash;
use Yukar\Sql\Builder\Common\Statements\Phrases\Set;
use Yukar\Sql\Tests\CustomizedTestCase;

/**
 * クラス Set の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class SetTest extends CustomizedTestCase
{
    private const PROP_NAME_DICTIONARY = 'dictionary';

    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    protected function getTargetClassName(): string
    {
        return Set::class;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString(): void
    {
        $this->assertSame('SET %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetDictionary のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDictionary(): array
    {
        $dic_1 = new SetValuesHash([ 'col1' => 'val1', 'col2' => '20' ]);

        return [
            [ $dic_1, $dic_1 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetDictionary
     *
     * @param SetValuesHash $expected   期待値
     * @param SetValuesHash $prop_value プロパティ dictionary の値
     */
    public function testGetDictionary($expected, $prop_value): void
    {
        /** @var Set $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DICTIONARY)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getDictionary());
    }

    /**
     * メソッド testSetDictionary のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDictionary(): array
    {
        $dic_1 = new SetValuesHash([ 'col1' => 'val1', 'col2' => '20' ]);
        $dic_2 = new SetValuesHash([ 'col1' => 'val1' ]);

        return [
            [ $dic_1, null, $dic_1 ],
            [ $dic_2, $dic_1, $dic_2 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetDictionary
     *
     * @param SetValuesHash $expected   期待値
     * @param mixed         $prop_value プロパティ dictionary の値
     * @param SetValuesHash $dictionary メソッド setDictionary の引数 dictionary に渡す値
     */
    public function testSetDictionary($expected, $prop_value, $dictionary): void
    {
        /** @var Set $object */
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DICTIONARY);
        $reflector->setValue($object, $prop_value);
        $object->setDictionary($dictionary);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetDictionaryFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDictionaryFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, new SetValuesHash() ],
            [ \InvalidArgumentException::class, new SetValuesHash([ 'col' => 'val' ]), new SetValuesHash() ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetDictionaryFailure
     *
     * @param \Exception    $expected   期待値
     * @param mixed         $prop_value プロパティ dictionary の値
     * @param SetValuesHash $dictionary メソッド setDictionary の引数 dictionary に渡す値
     */
    public function testSetDictionaryFailure($expected, $prop_value, $dictionary): void
    {
        $this->expectException($expected);

        /** @var Set $object */
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DICTIONARY)->setValue($object, $prop_value);
        $object->setDictionary($dictionary);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        $dic_1 = new SetValuesHash([ 'col1' => 'val1', 'col2' => '20' ]);
        $dic_2 = new SetValuesHash([ 'col1' => 'val1' ]);

        return [
            [ "SET col1 = 'val1', col2 = 20", $dic_1 ],
            [ "SET col1 = 'val1'", $dic_2 ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string        $expected   期待値
     * @param SetValuesHash $dictionary コンストラクタの引数 dictionary に渡す値
     */
    public function testToString($expected, $dictionary): void
    {
        $this->assertSame($expected, (string)new Set($dictionary));
    }
}
