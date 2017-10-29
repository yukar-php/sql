<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Operators;

/**
 * 演算子を表すインターフェイスです。
 *
 * @author hiroki sugawara
 */
interface IOperator
{
    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string;

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string;
}
