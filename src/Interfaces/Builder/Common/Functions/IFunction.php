<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Functions;

/**
 * SQLの関数や計算式を表すインターフェイスです。
 *
 * @package Yukar\Sql\Interfaces\Builder\Common\Functions
 * @author hiroki sugawara
 */
interface IFunction
{
    /**
     * SQLの関数の名前を取得します。
     *
     * @return string SQLの関数の名前
     */
    public function getFunctionName(): string;

    /**
     * SQLの関数や計算式の書式を取得します。
     *
     * @return string SQLの関数や計算式の書式
     */
    public function getFunctionFormat(): string;

    /**
     * SQLの関数や計算式を文字列として取得します。
     *
     * @return string SQLの関数や計算式
     */
    public function __toString(): string;
}
