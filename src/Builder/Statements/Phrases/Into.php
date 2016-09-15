<?php
namespace Yukar\Sql\Builder\Statements\Phrases;

use Yukar\Sql\Interfaces\Builder\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Objects\ITable;
use Yukar\Sql\Interfaces\Builder\Statements\IPhrases;

/**
 * SQLクエリの INTO 句を表します。
 *
 * @author hiroki sugawara
 */
class Into implements IPhrases, ISqlQuerySource
{
    private $data_source;
    private $columns;

    /**
     * Into クラスの新しいインスタンスを初期化します。
     *
     * @param ITable $data_source    SQLクエリの対象となる表
     * @param IColumns|null $columns SQLクエリの対象となる表の列。未指定の場合は、表の全ての列。
     */
    public function __construct(ITable $data_source, IColumns $columns = null)
    {
        $this->setDataSource($data_source);
        (empty($columns) === false) && $this->setColumns($columns);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'INTO %s %s';
    }

    /**
     * SQLクエリの対象となる表を取得します。
     *
     * @return ITable SQLクエリの対象となる表
     */
    public function getDataSource(): ITable
    {
        return $this->data_source;
    }

    /**
     * SQLクエリの対象となる表を設定します。
     *
     * @param ITable $data_source SQLクエリの対象となる表
     */
    public function setDataSource(ITable $data_source)
    {
        $this->data_source = $data_source;
    }

    /**
     * SQLクエリの対象となる表の列を取得します。
     *
     * @return IColumns|null SQLクエリの対象となる表の列
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * SQLクエリの対象となる表の列を設定します。
     *
     * @param IColumns $columns SQLクエリの対象となる表の列
     */
    public function setColumns(IColumns $columns)
    {
        if ($columns->hasOnlyStringItems() === false) {
            throw new \InvalidArgumentException();
        }

        $this->columns = $columns;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return rtrim(sprintf($this->getPhraseString(), $this->getDataSource(), $this->getColumnsString()));
    }

    /**
     * SQLクエリの対象となる表の列のリストを文字列として取得します。
     *
     * @return string SQLクエリの対象となる表の列のリスト
     */
    private function getColumnsString(): string
    {
        return (empty($this->getColumns()) === true) ? '' :
            sprintf('(%s)', implode(', ', $this->getColumns()->getColumns()));
    }
}
