<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Statements;

/**
 * SQLクエリの句を定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IPhrases
{
    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string;

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string;
}
