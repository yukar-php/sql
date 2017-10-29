<?php
namespace Yukar\Sql\Builder\Common\Statements\Phrases;

use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;

/**
 * SQLクエリのOrderBy句を表します。
 *
 * @author hiroki sugawara
 */
class OrderBy implements IPhrases
{
    private $order_by_list;

    /**
     * OrderBy クラスの新しいインスタンスを初期化します。
     *
     * @param IColumns $order_by OrderBy句に指定するテーブルの任意の列のリスト
     */
    public function __construct(IColumns $order_by)
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
     * @return IColumns OrderBy句に指定するテーブルの任意の列のリスト
     */
    public function getOrderBy(): IColumns
    {
        return $this->order_by_list;
    }

    /**
     * OrderBy句に指定するテーブルの任意の列のリストを設定します。
     *
     * @param IColumns $order_by OrderBy句に指定するテーブルの任意の列のリスト
     *
     * @throws \InvalidArgumentException OrderBy句に指定するテーブルの任意の列のリストの要素が空の場合
     */
    public function setOrderBy(IColumns $order_by)
    {
        if ($order_by->hasOnlyOrderByItems() === false) {
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
