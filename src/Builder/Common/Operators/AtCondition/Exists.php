<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Common\Operators\IDeniableOperator;
use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;

/**
 * SQL の EXISTS 演算子を表現します。
 *
 * @package Yukar\Sql\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class Exists extends BaseConditionContainable implements IDeniableOperator
{
    private $needle;

    /**
     * Exists クラスの新しいインスタンスを初期化します。
     *
     * @param string|ISelectQuery $needle 演算子の対象となる問い合わせクエリ
     */
    public function __construct($needle)
    {
        $this->setNeedle($needle);
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getOperatorFormat(), $this->getOperator(), $this->getValue());
    }

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s %s';
    }

    /**
     * 演算子の対象となる表や列の名前を設定します。
     *
     * @param string $name 演算子の対象となる表や列の名前
     *
     * @throws \BadMethodCallException 表や列の名前を設定しようとした場合
     */
    public function setName(string $name): void
    {
        throw new \BadMethodCallException();
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return sprintf('(%s)', $this->getNeedle());
    }

    /**
     * EXISTS 演算子の対象となる問い合わせクエリを取得します。
     *
     * @return string EXISTS 演算子の対象となる問い合わせクエリ
     */
    public function getNeedle(): string
    {
        return strval($this->needle);
    }

    /**
     * EXISTS 演算子の対象となる問い合わせクエリを設定します。
     *
     * @param string|ISelectQuery $needle EXISTS 演算子の対象となる問い合わせクエリ
     *
     * @throws \InvalidArgumentException 引数 expression に渡した値が不正な場合
     */
    public function setNeedle($needle): void
    {
        if ($this->isAcceptableSubQuery($needle) === false) {
            throw new \InvalidArgumentException();
        }

        $this->needle = $needle;
    }

    /**
     * EXISTS 演算子の対象となる問い合わせクエリにすることができる値かどうかを判別します。
     *
     * @param mixed $sub_query 判別対象となる値
     *
     * @return bool 問い合わせクエリにすることができる値の場合は、true。それ以外の場合は、false。
     */
    private function isAcceptableSubQuery($sub_query): bool
    {
        return ((is_string($sub_query) === true && empty($sub_query) === false)
            || $sub_query instanceof ISelectQuery === true);
    }
}
