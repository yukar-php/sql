<?php
namespace Yukar\Sql\Tests\Builder\Specialized\MySQL\Statements\Phrases;

use Yukar\Sql\Builder\Specialized\MySQL\Statements\Phrases\Partition;

/**
 * クラス Partition の単体テスト
 *
 * @package Yukar\Sql\Tests\Builder\Specialized\MySQL\Statements\Phrases
 * @author  hiroki sugawara
 */
class PartitionTest extends \PHPUnit_Framework_TestCase
{
    private const PROP_NAME_PARTITIONS = 'partitions';

    /**
     * コンストラクタを通さずに作成した単体テスト対象となるクラスの新しいインスタンスを取得します。
     *
     * @return Partition コンストラクタを通さずに作成した新しいインスタンス
     */
    private function getNewInstance(): Partition
    {
        /** @var Partition $instance */
        $instance =  (new \ReflectionClass(Partition::class))->newInstanceWithoutConstructor();

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
    public function testGetPhraseString(): void
    {
        $this->assertSame('PARTITION (%s)', $this->getNewInstance()->getPhraseString());
    }

    /**
     * メソッド testGetPartitions のデータプロバイダー
     *
     * @return array
     */
    public function providerGetPartitions(): array
    {
        return [
            [ [ 'partition_a' ], [ 'partition_a' ] ],
            [ [ 'partition_a', 'partition_b' ], [ 'partition_a', 'partition_b' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerGetPartitions
     *
     * @param array $expected   期待値
     * @param array $prop_value プロパティ partitions の値
     */
    public function testGetPartitions($expected, $prop_value): void
    {
        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_PARTITIONS)->setValue($object, $prop_value);

        $this->assertSame($expected, $object->getPartitions());
    }

    /**
     * メソッド testSetPartitions のデータプロバイダー
     *
     * @return array
     */
    public function providerSetPartitions(): array
    {
        return [
            [ [ 'partition_a' ], null, [ 'partition_a' ] ],
            [ [ 'partition_b', 'partition_c' ], [ 'partition_a' ], [ 'partition_b', 'partition_c' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerSetPartitions
     *
     * @param array $expected   期待値
     * @param array $prop_value プロパティ partitions の値
     * @param array $partitions メソッド setPartitions の引数 partitions に渡す値
     */
    public function testSetPartitions($expected, $prop_value, $partitions): void
    {
        $object = $this->getNewInstance();
        $reflector = $this->getProperty($object, self::PROP_NAME_PARTITIONS);
        $reflector->setValue($object, $prop_value);
        $object->setPartitions(...$partitions);

        $this->assertSame($expected, $reflector->getValue($object));
    }

    /**
     * メソッド testSetPartitionsFailure のデータプロバイダー
     *
     * @return array
     */
    public function providerSetPartitionsFailure(): array
    {
        return [
            [ \InvalidArgumentException::class, null, [ 1 ] ],
            [ \InvalidArgumentException::class, null, [ 0 ] ],
            [ \InvalidArgumentException::class, null, [ 1.2 ] ],
            [ \InvalidArgumentException::class, null, [ 0.1 ] ],
            [ \InvalidArgumentException::class, null, [ true ] ],
            [ \InvalidArgumentException::class, null, [ false ] ],
            [ \InvalidArgumentException::class, null, [ [] ] ],
            [ \InvalidArgumentException::class, null, [ null ] ],
            [ \InvalidArgumentException::class, null, [ new \stdClass() ] ],
            [ \InvalidArgumentException::class, null, [ '1' ] ],
            [ \InvalidArgumentException::class, null, [ '0' ] ],
            [ \InvalidArgumentException::class, null, [ '' ] ],
            [ \InvalidArgumentException::class, null, [ 'a', null ] ],
        ];
    }

    /**
     * 異常系テスト
     *
     * @dataProvider providerSetPartitionsFailure
     *
     * @param \Exception $expected   期待値
     * @param mixed      $prop_value プロパティ partitions の値
     * @param array      $partitions メソッド setPartitions の引数 partitions に渡す値
     */
    public function testSetPartitionsFailure($expected, $prop_value, $partitions): void
    {
        $this->expectException($expected);

        $object = $this->getNewInstance();
        $this->getProperty($object, self::PROP_NAME_PARTITIONS)->setValue($object, $prop_value);
        $object->setPartitions($partitions);
    }

    /**
     * testToString のデータプロバイダー
     *
     * @return array
     */
    public function providerToString(): array
    {
        return [
            [ 'PARTITION (partition_a)', [ 'partition_a' ] ],
            [ 'PARTITION (partition_a, partition_b)', [ 'partition_a', 'partition_b' ] ],
        ];
    }

    /**
     * 正常系テスト
     *
     * @dataProvider providerToString
     *
     * @param string $expected   期待値
     * @param array  $partitions コンストラクタの引数 partitions に渡す値
     */
    public function testToString($expected, $partitions): void
    {
        $this->assertSame($expected, (string)new Partition(...$partitions));
    }
}
