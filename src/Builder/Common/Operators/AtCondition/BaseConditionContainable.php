<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Common\Operators\IConditionContainable;

/**
 * 条件式に含めることのできる演算子の基本機能を提供する抽象クラスです。
 *
 * @package Yukar\Sql\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
abstract class BaseConditionContainable implements IConditionContainable
{
    private $name = '';

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s %s %s';
    }

    /**
     * 演算子の名前を取得します。
     *
     * @return string 演算子の名前
     */
    public function getOperator(): string
    {
        return strtoupper(str_replace(__NAMESPACE__ . "\\", '', static::class));
    }

    /**
     * 演算子の対象となる表や列の名前を取得します。
     *
     * @return string 演算子の対象となる表や列の名前
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 演算子の対象となる表や列の名前を設定します。
     *
     * @param string $name 演算子の対象となる表や列の名前
     *
     * @throws \InvalidArgumentException 引数 name に渡した値が空文字列の場合
     */
    public function setName(string $name): void
    {
        if (empty($name) === true) {
            throw new \InvalidArgumentException();
        }

        $this->name = $name;
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getOperatorFormat(), $this->getName(), $this->getOperator(), $this->getValue());
    }
}
