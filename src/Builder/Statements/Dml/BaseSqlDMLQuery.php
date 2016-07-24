<?php
namespace Yukar\Sql\Builder\Statements\Dml;

use Yukar\Sql\Interfaces\Builder\Objects\IDataSource;
use Yukar\Sql\Interfaces\Builder\Statements\ISqlDMLQuery;

/**
 * データ操作言語の問い合わせクエリの基本機能を実装する抽象クラスです。
 *
 * @author hiroki sugawara
 */
abstract class BaseSqlDMLQuery implements ISqlDMLQuery
{
    private $data_source;

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを取得します。
     *
     * @return IDataSource SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function getDataSource(): IDataSource
    {
        return $this->data_source;
    }

    /**
     * SQLのデータ操作言語の対象となる表やサブクエリを設定します。
     *
     * @param IDataSource $data_source SQLのデータ操作言語の対象となる表やサブクエリ
     */
    public function setDataSource(IDataSource $data_source)
    {
        $this->data_source = $data_source;
    }
}
