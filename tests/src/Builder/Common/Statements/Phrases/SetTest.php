<?php
namespace Yukar\Sql\Tests\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\SetValuesHash;
use Yukar\Sql\Builder\Common\Statements\Phrases\Set;

/**
 * クラス Set の単体テスト
 *
 * @author hiroki sugawara
 */
class SetTest extends \PHPUnit_Framework_TestCase
{
    const PROP_NAME_DICTIONARY = 'dictionary';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Set コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Set
    {
        /** @var Set $instance */
        $instance = (new \ReflectionClass(Set::class))->newInstanceWithoutConstructor();

        return $instance;
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
        $property = (new \ReflectionClass($object))->getProperty($property_name);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * 正常系テスト
     */
    public function testGetPhraseString()
    {
        self::assertSame('SET %s', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetDictionary のデータプロバイダー
     *
     * @return array
     */
    public function providerGetDictionary()
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
    public function testGetDictionary($expected, $prop_value)
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DICTIONARY)->setValue($object, $prop_value);

        self::assertSame($expected, $object->getDictionary());
    }

    /**
     * メソッド testSetDictionary のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDictionary()
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
    public function testSetDictionary($expected, $prop_value, $dictionary)
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_DICTIONARY);
        $reflector->setValue($object, $prop_value);
        $object->setDictionary($dictionary);

        self::assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetDictionaryFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetDictionaryFailure()
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
    public function testSetDictionaryFailure($expected, $prop_value, $dictionary)
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_DICTIONARY)->setValue($object, $prop_value);
        $object->setDictionary($dictionary);
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
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
    public function testToString($expected, $dictionary)
    {
        self::assertSame($expected, (string)new Set($dictionary));
    }
}
