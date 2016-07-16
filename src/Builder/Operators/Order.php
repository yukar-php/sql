<?php
namespace Yukar\Sql\Builder\Operators;

use Yukar\Sql\Interfaces\Builder\Operators\IOperator;

/**
 * テーブルの任意の列のソートを表します。
 *
 * @author hiroki sugawara
 */
class Order implements IOperator
{
    const ASCENDING = 1;
    const DESCENDING = 2;
    const SORTS = [ self::ASCENDING => 'ASC', self::DESCENDING => 'DESC' ];

    private $column_name = '';
    private $order_type = '';

    /**
     * Order クラスの新しいインスタンスを初期化します。
     *
     * @param string $column_name ソートするテーブルの列の名前
     * @param int $order_type     ソートの種類。<br/>
     *                            昇順の場合は、Order::ASCENDING。降順の場合は、Order::DESCENDING。
     */
    public function __construct(string $column_name, int $order_type = self::ASCENDING)
    {
        $this->setColumnName($column_name);
        $this->setOrderType($order_type);
    }

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s %s';
    }

    /**
     * ソートするテーブルの列の名前を取得します。
     *
     * @return string ソートするテーブルの列の名前
     */
    public function getColumnName(): string
    {
        return $this->column_name;
    }

    /**
     * ソートするテーブルの列の名前を設定します。
     *
     * @param string $column_name ソートするテーブルの列の名前
     *
     * @throws \InvalidArgumentException 引数 $column_name に渡した値が空文字列の場合
     */
    public function setColumnName(string $column_name)
    {
        if (empty($column_name) === true) {
            throw new \InvalidArgumentException();
        }

        $this->column_name = $column_name;
    }

    /**
     * ソートの種類を取得します。
     *
     * @return string ソートの種類
     */
    public function getOrderType(): string
    {
        return $this->order_type ?? self::SORTS[self::ASCENDING];
    }

    /**
     * ソートの種類を設定します。
     *
     * @param int $order_type ソートの種類。<br/>
     *                        昇順の場合は、Order::ASCENDING。降順の場合は、Order::DESCENDING。
     *
     * @return Order ソートの種類を設定した状態の Order クラスのオブジェクトのインスタンス
     */
    public function setOrderType(int $order_type): Order
    {
        $this->order_type = self::SORTS[$order_type] ?? self::SORTS[self::ASCENDING];

        return $this;
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getOperatorFormat(), $this->getColumnName(), $this->getOrderType());
    }
}
