<?php
namespace Yukar\Sql\Tests\Builder\Operators;

use Yukar\Sql\Builder\Operators\Alias;

/**
 * クラス Alias の単体テスト
 *
 * @author hiroki sugawara
 */
class AliasTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 正常系テスト
     */
    public function testGetOperatorFormat()
    {
        $this->assertSame('%s AS %s', $this->getAliasInstance()->getOperatorFormat());
    }

    /**
     * メソッド testGetOriginName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetOriginName()
    {
        return [
            [ 'origin', 'origin' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetOriginName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ origin_name の値
     */
    public function testGetOriginName($expected, $prop_value)
    {
        $object = $this->getAliasInstance();
        $this->getOriginNameProperty(new \ReflectionClass($object))->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getOriginName());
    }

    /**
     * メソッド testSetOriginName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOriginName()
    {
        return [
            [ 'origin', null, 'origin' ],
            [ 'based', 'origin', 'based' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetOriginName
     *
     * @param string $expected    期待値
     * @param mixed $prop_value   プロパティ origin_name の値
     * @param string $origin_name メソッド setOriginName の引数 origin_name に渡す値
     */
    public function testSetOriginName($expected, $prop_value, $origin_name)
    {
        $object = $this->getAliasInstance();
        $reflector = $this->getOriginNameProperty(new \ReflectionClass($object));
        $reflector->setValue($object, $prop_value);
        $object->setOriginName($origin_name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetOriginNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetOriginNameFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'origin', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetOriginNameFailure
     *
     * @param |Exception $expected 期待値
     * @param mixed $prop_value    プロパティ origin_name の値
     * @param string $origin_name  メソッド setOriginName の引数 origin_name に渡す値
     */
    public function testSetOriginNameFailure($expected, $prop_value, $origin_name)
    {
        $this->expectException($expected);

        $object = $this->getAliasInstance();
        $this->getOriginNameProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
        $object->setOriginName($origin_name);
    }

    /**
     * メソッド testGetAliasName のデータプロバイダー
     *
     * @return array
     */
    public function providerGetAliasName()
    {
        return [
            [ 'alias_name', 'alias_name' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetAliasName
     *
     * @param string $expected   期待値
     * @param string $prop_value プロパティ alias_name の値
     */
    public function testGetAliasName($expected, $prop_value)
    {
        $object = $this->getAliasInstance();
        $this->getAliasNameProperty(new \ReflectionClass($object))->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getAliasName());
    }

    /**
     * メソッド testSetAliasName のデータプロバイダー
     *
     * @return array
     */
    public function providerSetAliasName()
    {
        return [
            [ 'alias_name', null, 'alias_name' ],
            [ 'second_alias', 'first_alias', 'second_alias' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetAliasName
     *
     * @param string $expected   期待値
     * @param mixed $prop_value  プロパティ alias_name の値
     * @param string $alias_name メソッド setAliasName の引数 alias_name に渡す値
     */
    public function testSetAliasName($expected, $prop_value, $alias_name)
    {
        $object = $this->getAliasInstance();
        $reflector = $this->getAliasNameProperty(new \ReflectionClass($object));
        $reflector->setValue($object, $prop_value);
        $object->setAliasName($alias_name);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetAliasNameFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetAliasNameFailure()
    {
        return [
            [ '\InvalidArgumentException', null, '' ],
            [ '\InvalidArgumentException', 'alias_name', '' ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetAliasNameFailure
     *
     * @param \Exception $expected 期待値
     * @param mixed $prop_value    プロパティ alias_name の値
     * @param string $alias_name   メソッド setAliasName の引数 alias_name に渡す値
     */
    public function testSetAliasNameFailure($expected, $prop_value, $alias_name)
    {
        $this->expectException($expected);

        $object = $this->getAliasInstance();
        $this->getAliasNameProperty(new \ReflectionClass($object))->setValue($object, $prop_value);
        $object->setAliasName($alias_name);
    }

    /**
     * コンストラクタを通さずに作成した Alias クラスの新しいインスタンスを取得します。
     *
     * @return Alias コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getAliasInstance(): Alias
    {
        return (new \ReflectionClass('Yukar\Sql\Builder\Operators\Alias'))->newInstanceWithoutConstructor();
    }

    /**
     * プロパティ origin_name のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Alias のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ origin_name のリフレクションを持つインスタンス
     */
    private function getOriginNameProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('origin_name');
        $property->setAccessible(true);

        return $property;
    }

    /**
     * プロパティ alias_name のリフレクションを持つインスタンスを取得します。
     *
     * @param \ReflectionClass $reflector クラス Alias のオブジェクトのリフレクション
     *
     * @return \ReflectionProperty プロパティ alias_name のリフレクションを持つインスタンス
     */
    private function getAliasNameProperty(\ReflectionClass $reflector): \ReflectionProperty
    {
        $property = $reflector->getProperty('alias_name');
        $property->setAccessible(true);

        return $property;
    }

    /**
     * メソッド testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString()
    {
        return [
            [ 'origin_a AS alias_a', 'origin_a', 'alias_a' ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected    期待値
     * @param string $origin_name コンストラクタの引数 origin_name に渡す値
     * @param string $alias_name  コンストラクタの引数 alias_name に渡す値
     */
    public function testToString($expected, $origin_name, $alias_name)
    {
        $this->assertSame($expected, (string)new Alias($origin_name, $alias_name));
    }
}
