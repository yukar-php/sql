<?php
namespace Yukar\Sql\Interfaces\Builder\Objects;

use Yukar\Sql\Builder\Objects\Columns;

/**
 * テーブルを定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface ITable
{
    /**
     * テーブルの名前を取得します。
     *
     * @throws \BadMethodCallException テーブルの名前が未定義の場合
     *
     * @return string テーブルの名前
     */
    public function getTableName(): string;

    /**
     * テーブルの名前を設定します。
     *
     * @param string $name テーブルの名前
     *
     * @throws \InvalidArgumentException 引数 $columns が空文字列の場合
     */
    public function setTableName(string $name);

    /**
     * テーブルで定義済みの列のリストを取得します。
     *
     * @throws \BadMethodCallException テーブルで定義済みの列のリストが未定義の場合
     *
     * @return array テーブルで定義されている列のリスト
     */
    public function getDefinedColumns(): array;

    /**
     * テーブルで定義済みの列のリストを設定します。
     *
     * @param Columns $columns テーブルで定義済みの列のリスト
     *
     * @throws \InvalidArgumentException 引数 $columns の要素が空の場合
     *
     * @return ITable 定義済みの列のリストを設定した状態の ITable を継承するオブジェクトのインスタンス
     */
    public function setDefinedColumns(Columns $columns): ITable;

    /**
     * オブジェクトを表す文字列を取得します。
     *
     * @return string オブジェクトを表す文字列
     */
    public function __toString(): string;
}
