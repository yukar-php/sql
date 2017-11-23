<?php
namespace Yukar\Sql\Builder\Common\Objects;

use Yukar\Linq\Collections\ListObject;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ITable;

/**
 * SQL で使用するテーブルを表します。
 *
 * @package Yukar\Sql\Builder\Common\Objects
 * @author hiroki sugawara
 */
class Table implements ITable
{
    use TQuoteIdentifier;

    private $table_name = '';
    private $column_list = [];

    /**
     * Table クラスの新しいインスタンスを初期化します。
     *
     * @param string $name                    テーブルの名前
     * @param DelimitedIdentifier $identifier SQL識別子を区切り識別子で引用する設定
     */
    public function __construct(string $name, DelimitedIdentifier $identifier = null)
    {
        $this->setTableName($name);
        (isset($identifier) === true) && $this->setDelimitedIdentifier($identifier);
    }

    /**
     * テーブルの名前を取得します。
     *
     * @return string テーブルの名前
     */
    public function getTableName(): string
    {
        return $this->table_name;
    }

    /**
     * テーブルの名前を設定します。
     *
     * @param string $name テーブルの名前
     *
     * @throws \InvalidArgumentException 引数 $columns が空文字列の場合
     */
    public function setTableName(string $name): void
    {
        if (empty($name) === true) {
            throw new \InvalidArgumentException();
        }

        $this->table_name = $name;
    }

    /**
     * テーブルで定義済みの列のリストを取得します。
     *
     * @throws \BadMethodCallException テーブルで定義済みの列のリストが未定義の場合
     *
     * @return array テーブルで定義されている列のリスト
     */
    public function getDefinedColumns(): array
    {
        if (empty($this->column_list) === true) {
            throw new \BadMethodCallException();
        }

        return $this->column_list;
    }

    /**
     * テーブルで定義済みの列のリストを設定します。
     *
     * @param IColumns $columns テーブルで定義済みの列のリスト
     *
     * @throws \InvalidArgumentException 引数 $columns の要素が空、または、要素が文字列型の値以外の場合
     *
     * @return ITable 定義済みの列のリストを設定した状態の ITable を継承するオブジェクトのインスタンス
     */
    public function setDefinedColumns(IColumns $columns): ITable
    {
        $list = new ListObject($columns->getColumns());

        if ($list->getSize() < 1 || $list->trueForAll($this->getValidStringCheckClosure()) === false) {
            throw new \InvalidArgumentException();
        }

        $this->column_list = $this->getQuotedList($list->toArray());

        return $this;
    }

    /**
     * オブジェクトを表す文字列を取得します。
     *
     * @return string オブジェクトを表す文字列
     */
    public function __toString(): string
    {
        $table_name = $this->getTableName();

        // 引用書式を使用時にスキーマを含む場合は文字列を分割して引用に変換（そのままだと「"schema.table"」になるため）
        if ($this->isPreparedQuote() === true && strpos($table_name, '.') !== false) {
            return implode('.', $this->getQuotedList(explode('.', $table_name)));
        }

        return $this->getQuotedString($table_name);
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
}
