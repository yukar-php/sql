<?php
namespace Yukar\Sql\Builder\Common\Objects;

use Yukar\Linq\Collections\ListObject;
use Yukar\Sql\Builder\Common\Operators\Order;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Operators\IConditionContainable;
use Yukar\Sql\Interfaces\Builder\Common\Operators\IOperator;

/**
 * SQL で使用するテーブルの列のリストを表します。
 *
 * @package Yukar\Sql\Builder\Common\Objects
 * @author hiroki sugawara
 */
class Columns implements IColumns
{
    use TQuoteIdentifier;

    private $column_list = [];

    /**
     * Columns クラスの新しいインスタンスを初期化します。
     *
     * @param array               $columns    テーブルの任意の列のリスト
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
     * @throws \BadMethodCallException   引数 $columns が空配列の場合
     * @throws \InvalidArgumentException 引数 $columns の配列の要素に一つ以上の許容できない型がある場合
     */
    public function setColumns(array $columns): void
    {
        $list = new ListObject($columns);

        if ($list->getSize() < 1) {
            throw new \BadMethodCallException();
        } elseif ($list->trueForAll($this->getAcceptableColumnValueClosure()) === false) {
            throw new \InvalidArgumentException();
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

        return ($list->getSize() > 0 && $list->trueForAll($this->getValidStringCheckClosure()) === true);
    }

    /**
     * テーブルの任意の列のリストがOrderByに使用可能な項目だけを持つかどうかを判別します。
     *
     * @return bool テーブルの任意の列のリストがOrderByに使用可能な項目だけの場合は true。それ以外の場合は false。
     */
    public function hasOnlyOrderByItems(): bool
    {
        $list = new ListObject($this->getColumns());

        return ($list->getSize() > 0 && $list->trueForAll($this->getValidOrderByValueCheckClosure()) === true);
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

        return empty($columns) ? "*" : implode(", ", $this->getMultiQuotedList($columns));
    }

    /**
     * テーブルの列の値として扱うことができるかどうかを判別するクロージャーを取得します。
     *
     * @return \Closure テーブルの列の値として扱うことができるかどうかを判別するクロージャー
     */
    private function getAcceptableColumnValueClosure(): \Closure
    {
        return function ($value): bool {
            return ((is_string($value) === true && strlen($value) > 0)
                || ($value instanceof IConditionContainable === false && $value instanceof IOperator));
        };
    }

    /**
     * 空白文字列を除く文字列であるかどうかを判別するクロージャーを取得します。
     *
     * @return \Closure 空白文字列を除く文字列であるかどうかを判別するクロージャー
     */
    private function getValidStringCheckClosure(): \Closure
    {
        return function ($value): bool {
            return (is_string($value) === true && strlen($value) > 0);
        };
    }

    /**
     * OrderBy 句に使用可能な値であるかどうかを判別するクロージャーを取得します。
     *
     * @return \Closure OrderBy 句に使用可能な値であるかどうかを判別するクロージャー
     */
    private function getValidOrderByValueCheckClosure(): \Closure
    {
        return function ($value): bool {
            return ((is_string($value) === true && strlen($value) > 0) || ($value instanceof Order));
        };
    }
}
