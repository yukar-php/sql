<?php
namespace Yukar\Sql\Builder\Objects;

use Yukar\Linq\Collections\ListObject;
use Yukar\Sql\Builder\Operators\Order;
use Yukar\Sql\Interfaces\Builder\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Operators\IConditionContainable;
use Yukar\Sql\Interfaces\Builder\Operators\IOperator;

/**
 * テーブルの列のリストを表します。
 *
 * @author hiroki sugawara
 */
class Columns implements IColumns
{
    use TQuoteIdentifier;

    private $column_list = [];

    /**
     * Columns クラスの新しいインスタンスを初期化します。
     *
     * @param array $columns                  テーブルの任意の列のリスト
     * @param DelimitedIdentifier $identifier SQL識別子を区切り識別子で引用する設定
     */
    public function __construct(array $columns = [], DelimitedIdentifier $identifier = null)
    {
        (empty($columns) === false) && $this->setColumns($columns);
        (isset($identifier) === true) && $this->setDelimitedIdentifier($identifier);
    }

    /**
     * テーブルの任意の列のリストを取得します。
     *
     * @return array テーブルの任意の列のリスト
     */
    public function getColumns(): array
    {
        return $this->column_list;
    }

    /**
     * テーブルの任意の列のリストを設定します。
     *
     * @param array $columns テーブルの任意の列のリスト
     *
     * @throws \InvalidArgumentException 引数 $columns が空配列の場合
     * @throws \DomainException          引数 $columns の配列の要素に一つ以上の許容できない型がある場合
     */
    public function setColumns(array $columns)
    {
        $list = new ListObject($columns);

        $is_acceptable_array = $list->trueForAll(function ($column) {
            return $this->isAcceptableColumnValue($column);
        });

        if ($list->getSize() === 0) {
            throw new \InvalidArgumentException();
        } elseif ($is_acceptable_array === false) {
            throw new \DomainException();
        }

        $this->column_list = $list->toArray();
    }

    /**
     * テーブルの任意の列のリストが文字列の項目だけを持つかどうかを判別します。
     *
     * @return bool テーブルの任意の列のリストが文字列の項目だけの場合は true。それ以外の場合は false。
     */
    public function hasOnlyStringItems(): bool
    {
        $list = new ListObject($this->getColumns());

        return ($list->getSize() > 0
            && $list->trueForAll(function ($column) {
                return (is_string($column) === true && strlen($column) > 0);
            }) === true);
    }

    /**
     * テーブルの任意の列のリストがOrderByに使用可能な項目だけを持つかどうかを判別します。
     *
     * @return bool テーブルの任意の列のリストがOrderByに使用可能な項目だけの場合は true。それ以外の場合は false。
     */
    public function hasOnlyOrderByItems(): bool
    {
        $list = new ListObject($this->getColumns());

        return ($list->getSize() > 0
            && $list->trueForAll(function ($column) {
                return ((is_string($column) === true && strlen($column) > 0) || ($column instanceof Order));
            }) === true);
    }

    /**
     * テーブルの列のリストを文字列で取得します。
     *
     * @return string テーブルの対象の列のリストを「,」で連結した文字列。<br/>
     *                対象の列が指定されていない場合は「*」。
     */
    public function __toString(): string
    {
        $columns = $this->getColumns();

        return empty($columns) ? "*" : implode(", ", $this->getQuotedList($columns));
    }

    /**
     * テーブルの列のリストに含めることができる値かどうかを判別します。
     *
     * @param mixed $column 判別対象となる値
     *
     * @return bool テーブルの列のリストに含めることができる場合は true。それ以外の場合は false。
     */
    private function isAcceptableColumnValue($column): bool
    {
        return ((is_string($column) === true && strlen($column) > 0)
            || ($column instanceof IConditionContainable === false && $column instanceof IOperator));
    }
}
