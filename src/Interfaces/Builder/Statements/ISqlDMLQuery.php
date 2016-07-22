<?php
namespace Yukar\Sql\Interfaces\Builder\Statements;

use Yukar\Sql\Interfaces\Builder\Objects\IDataSource;

/**
 * SQLのデータ操作言語の問い合わせクエリを定義するインターフェイス。
 *
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
     * @return IDataSource SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function getDataSource(): IDataSource;

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param IDataSource $data_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setDataSource(IDataSource $data_source);

    /**
     * SQLのデータ操作言語の問い合わせクエリを文字列として取得します。
     *
     * @return string SQLのデータ操作言語の問い合わせクエリ
     */
    public function __toString(): string;
}
