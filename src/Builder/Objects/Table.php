<?php
namespace Yukar\Sql\Builder\Objects;

use Yukar\Sql\Interfaces\Builder\Objects\ITable;

/**
 * テーブルを表します。
 *
 * @author hiroki sugawara
 */
class Table implements ITable
{
    private $table_name = '';
    private $column_list = [];

    /**
     * Table クラスの新しいインスタンスを初期化します。
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setTableName($name);
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
    public function setTableName(string $name)
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
     * @param Columns $columns テーブルで定義済みの列のリスト
     *
     * @throws \InvalidArgumentException 引数 $columns の要素が空の場合
     *
     * @return ITable 定義済みの列のリストを設定した状態の ITable を継承するオブジェクトのインスタンス
     */
    public function setDefinedColumns(Columns $columns): ITable
    {
        $column_list = $columns->getColumns();

        if (empty($column_list) === true) {
            throw new \InvalidArgumentException();
        }

        $this->column_list = $column_list;

        return $this;
    }

    /**
     * オブジェクトを表す文字列を取得します。
     *
     * @return string オブジェクトを表す文字列
     */
    public function __toString(): string
    {
        return $this->getTableName();
    }
}
