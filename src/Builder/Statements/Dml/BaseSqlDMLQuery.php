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
}
