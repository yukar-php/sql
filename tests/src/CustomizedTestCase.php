<?php
namespace Yukar\Sql\Tests;

use PHPUnit\Framework\TestCase;

/**
 * PHPUnit の TestCase クラスをプロジェクト用にカスタマイズした抽象クラスです。
 *
 * @package Yukar\Sql\Tests
 * @author  hiroki sugawara
 */
abstract class CustomizedTestCase extends TestCase
{
    /**
     * テスト対象となるクラスの名前を取得します。
     *
     * @return string テスト対象となるクラスの名前
     */
    abstract protected function getTargetClassName(): string;

    /**
     * コンストラクタを通さずに作成した単体テスト対象となる通常のクラスの新しいインスタンスを取得します。
     *
     * @return object コンストラクタを通さずに作成した新しいインスタンス
     */
    protected function getNewInstance()
    {
        try {
            $instance = $this->getReflectionClass($this->getTargetClassName())->newInstanceWithoutConstructor();
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
            $instance = null;
        }

        return $instance;
    }

    /**
     * コンストラクタを通さずに作成した単体テスト対象となる抽象クラスの新しいインスタンスを取得します。
     *
     * @return object コンストラクタを通さずに作成した新しいインスタンス
     */
    protected function getNewAbstractInstance()
    {
        try {
            $instance = $this->getReflectionClass($this->getMockForAbstractClass($this->getTargetClassName()))
                ->newInstanceWithoutConstructor();
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
            $instance = null;
        }

        return $instance;
    }

    /**
     * 単体テスト対象となるトレイトの新しいインスタンスを取得します。
     *
     * @return \PHPUnit_Framework_MockObject_MockObject 単体テスト対象となるトレイトの新しいインスタンス
     */
    protected function getNewTraitInstance()
    {
        return $this->getMockForTrait($this->getTargetClassName());
    }

    /**
     * 単体テスト対象となるクラスの指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param object $object        単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    protected function getProperty($object, string $property_name): ?\ReflectionProperty
    {
        try {
            $reflection = ($object instanceof \ReflectionClass) ? $object : $this->getReflectionClass($object);
            $property = $reflection->getProperty($property_name);
            $property->setAccessible(true);
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
            $property = null;
        }

        return $property;
    }

    /**
     * 単体テスト対象となるクラスの親クラスにある指定した名前のプロパティのリクレクションインスタンスを取得します。
     *
     * @param object $object        単体テスト対象となるクラスのインスタンス
     * @param string $property_name リフレクションを取得するプロパティの名前
     *
     * @return \ReflectionProperty 指定した名前のプロパティのリフレクションを持つインスタンス
     */
    protected function getParentProperty($object, string $property_name): ?\ReflectionProperty
    {
        try {
            $property = $this->getReflectionClass($object)->getParentClass()->getProperty($property_name);
            $property->setAccessible(true);
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
            $property = null;
        }

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
    protected function getParentMethod($object, string $method_name): \ReflectionMethod
    {
        try {
            $method = $this->getReflectionClass($object)->getParentClass()->getMethod($method_name);
            $method->setAccessible(true);
        } catch (\ReflectionException $e) {
            $this->fail($e->getMessage());
            $method = null;
        }

        return $method;
    }

    /**
     * 引数に指定したクラスの名前またはインスタンスからリフレクションインスタンスを取得します。
     *
     * @param mixed $target リフレクションクラスを取得するクラスの名前またはインスタンス
     *
     * @return \ReflectionClass 引数に指定したクラスの名前またはインスタンスのリフレクションインスタンス
     *
     * @throws \ReflectionException 引数 $target に指定した値からリフレクションインスタンスを取得できない場合
     */
    protected function getReflectionClass($target): \ReflectionClass
    {
        return new \ReflectionClass($target);
    }
}
