<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Statements;

use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;

/**
 * SQLのデータ操作言語の問い合わせクエリを定義するインターフェイス。
 *
 * @package Yukar\Sql\Interfaces\Builder\Common\Statements
 * @author hiroki sugawara
 */
interface ISqlDMLQuery
{
    /**
     * SQLのデータ操作言語の問い合わせクエリの書式を取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリの書式
     */
    public function getQueryFormat(): string;

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを取得します。
     *
     * @return ISqlQuerySource SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function getSqlQuerySource(): ISqlQuerySource;

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param ISqlQuerySource $data_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setSqlQuerySource(ISqlQuerySource $data_source): void;

    /**
     * SQLのデータ操作言語の問い合わせクエリを文字列として取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリ
     */
    public function __toString(): string;
}
