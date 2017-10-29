<?php
namespace Yukar\Sql\Builder\Common\Functions;

use Yukar\Sql\Interfaces\Builder\Common\Functions\IAggregateFunction;

/**
 * 集計関数の基本機能を提供する抽象クラスです。
 *
 * @package Yukar\Sql\Builder\Common\Functions
 * @author hiroki sugawara
 */
abstract class BaseAggregateFunction implements IAggregateFunction
{
    private $column = '';

    /**
     * SQLの関数や計算式の書式を取得します。
     *
     * @return string SQLの関数や計算式の書式
     */
    public function getFunctionFormat(): string
    {
        return '%s(%s)';
    }

    /**
     * 集計関数の対象となる表の列名を取得します。
     *
     * @return string 集計関数の対象となる表の列名
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * 集計関数の対象となる表の列名を設定します。
     *
     * @param string $column 集計関数の対象となる表の列名
     */
    public function setColumn(string $column): void
    {
        if (empty($column) === true) {
            throw new \InvalidArgumentException();
        }

        $this->column = $column;
    }

    /**
     * SQLの関数や計算式を文字列として取得します。
     *
     * @return string SQLの関数や計算式
     */
    public function __toString(): string
    {
        return sprintf($this->getFunctionFormat(), $this->getFunctionName(), $this->getColumn());
    }
}
