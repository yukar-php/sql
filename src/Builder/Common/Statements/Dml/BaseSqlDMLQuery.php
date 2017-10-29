<?php
namespace Yukar\Sql\Builder\Statements\Dml;

use Yukar\Sql\Interfaces\Builder\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Statements\ISqlDMLQuery;

/**
 * データ操作言語の問い合わせクエリの基本機能を実装する抽象クラスです。
 *
 * @author hiroki sugawara
 */
abstract class BaseSqlDMLQuery implements ISqlDMLQuery
{
    private $sql_query_source;

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを取得します。
     *
     * @return ISqlQuerySource SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function getSqlQuerySource(): ISqlQuerySource
    {
        return $this->sql_query_source;
    }

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param ISqlQuerySource $sql_query_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setSqlQuerySource(ISqlQuerySource $sql_query_source)
    {
        $this->sql_query_source = $sql_query_source;
    }

    /**
     * クエリ文字列を結合します。
     *
     * @param array ...$query_parts 結合するクエリ文字列
     *
     * @return string 結合したクエリ文字列
     */
    protected function joinQuery(...$query_parts): string
    {
        return implode(' ', array_filter($query_parts, 'strlen'));
    }

    /**
     * 設定した書式に従い右端の空白文字を全て削除したSQLクエリ文字列を取得します。
     *
     * @param array ...$query_parts SQLクエリのパーツ
     *
     * @return string 書式に従って出力したSQLクエリ文字列
     */
    protected function getFormatRightTrim(...$query_parts): string
    {
        return rtrim(sprintf($this->getQueryFormat(), ...$query_parts));
    }
}
