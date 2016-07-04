<?php
namespace Yukar\Sql\Builder\Statements\Phrases;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Interfaces\Builder\Statements\IPhrases;

/**
 * SQLクエリのOrderBy句を表します。
 *
 * @author hiroki sugawara
 */
class OrderBy implements IPhrases
{
    private $order_by_list = null;

    /**
     * OrderBy クラスの新しいインスタンスを初期化します。
     *
     * @param Columns $order_by
     */
    public function __construct(Columns $order_by)
    {
        $this->setOrderBy($order_by);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'ORDER BY %s';
    }

    /**
     * OrderBy句に指定するテーブルの任意の列のリストを取得します。
     *
     * @return Columns OrderBy句に指定するテーブルの任意の列のリスト
     */
    public function getOrderBy(): Columns
    {
        return $this->order_by_list;
    }

    /**
     * OrderBy句に指定するテーブルの任意の列のリストを設定します。
     *
     * @param Columns $order_by OrderBy句に指定するテーブルの任意の列のリスト
     *
     * @throws \InvalidArgumentException OrderBy句に指定するテーブルの任意の列のリストの要素が空の場合
     */
    public function setOrderBy(Columns $order_by)
    {
        if (empty($order_by->getColumns()) === true) {
            throw new \InvalidArgumentException();
        }

        $this->order_by_list = $order_by;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return sprintf($this->getPhraseString(), $this->getOrderBy());
    }
}
