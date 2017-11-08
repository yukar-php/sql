<?php
namespace Yukar\Sql\Builder\Common\Statements\Phrases;

use Yukar\Sql\Interfaces\Builder\Common\Objects\IDataSource;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;

/**
 * SQLクエリの FROM 句を表します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class From implements IPhrases, ISqlQuerySource
{
    private $data_source;

    /**
     * From クラスの新しいインスタンスを初期化します。
     *
     * @param IDataSource $data_source SQLクエリの対象となる表やサブクエリ
     */
    public function __construct(IDataSource $data_source)
    {
        $this->setDataSource($data_source);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'FROM %s';
    }

    /**
     * SQLクエリの対象となる表やサブクエリを取得します。
     *
     * @return IDataSource SQLクエリの対象となる表やサブクエリ
     */
    public function getDataSource(): IDataSource
    {
        return $this->data_source;
    }

    /**
     * SQLクエリの対象となる表やサブクエリを設定します。
     *
     * @param IDataSource $data_source SQLクエリの対象となる表やサブクエリ
     */
    public function setDataSource(IDataSource $data_source): void
    {
        $this->data_source = $data_source;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return sprintf($this->getPhraseString(), $this->getDataSource());
    }
}
