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
    private $join;

    /**
     * From クラスの新しいインスタンスを初期化します。
     *
     * @param IDataSource $data_source SQLクエリの対象となる表やサブクエリ
     * @param Join        $join        問い合わせクエリに結合する表の名前またはサブクエリとその結合条件
     */
    public function __construct(IDataSource $data_source, Join $join = null)
    {
        $this->setDataSource($data_source);
        (isset($join) === true) && $this->setJoin($join);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'FROM %s %s';
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
     * 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件を取得します。
     *
     * @return Join 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件
     */
    public function getJoin(): ?Join
    {
        return $this->join;
    }

    /**
     * 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件を設定します。
     *
     * @param Join $join 問い合わせクエリに結合する表の名前またはサブクエリとその結合条件
     */
    public function setJoin(Join $join): void
    {
        $this->join = $join;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return rtrim(sprintf($this->getPhraseString(), $this->getDataSource(), $this->getJoin()));
    }
}
