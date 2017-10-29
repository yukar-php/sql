<?php
namespace Yukar\Sql\Interfaces\Builder\Statements;

/**
 * 集合に関する演算子を定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface ISets
{
    /**
     * 集合の重複を削除しないかどうかを取得します。
     *
     * @return bool 集合の重複を削除しない場合は true。それ以外は false。
     */
    public function getIsAll(): bool;

    /**
     * 集合の重複を削除しないかどうかを設定します。
     *
     * @param bool $is_all 集合の重複を削除しない場合は true。それ以外は false。
     */
    public function setIsAll(bool $is_all);

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getSetsFormat(): string;

    /**
     * 集合演算の対象となる一つ目の集合を取得します。
     *
     * @return ISelectQuery 集合演算の対象となる一つ目の集合
     */
    public function getFirstQuery(): ISelectQuery;

    /**
     * 集合演算の対象となる一つ目の集合を設定します。
     *
     * @param ISelectQuery $query 集合演算の対象となる一つ目の集合
     */
    public function setFirstQuery(ISelectQuery $query);

    /**
     * 集合演算の対象となるもう一方の集合を取得します。
     *
     * @return ISelectQuery 集合演算の対象となるもう一方の集合
     */
    public function getSecondQuery(): ISelectQuery;

    /**
     * 集合演算の対象となるもう一方の集合を設定します。
     *
     * @param ISelectQuery $query 集合演算の対象となるもう一方の集合
     */
    public function setSecondQuery(ISelectQuery $query);

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string;
}
