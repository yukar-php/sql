<?php
namespace Yukar\Sql\Builder\Common\Statements\Sets;

use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;
use Yukar\Sql\Interfaces\Builder\Common\Statements\ISets;

/**
 * 集合に関する演算子の機能を持つ抽象クラスです。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Sets
 * @author hiroki sugawara
 */
abstract class BaseSets implements ISets
{
    private $is_all = false;
    private $first_query;
    private $second_query;

    /**
     * 集合の重複を削除しないかどうかを取得します。
     *
     * @return bool 集合の重複を削除しない場合は true。それ以外は false。
     */
    public function getIsAll(): bool
    {
        return $this->is_all;
    }

    /**
     * 集合の重複を削除しないかどうかを設定します。
     *
     * @param bool $is_all 集合の重複を削除しない場合は true。それ以外は false。
     */
    public function setIsAll(bool $is_all): void
    {
        $this->is_all = $is_all;
    }

    /**
     * 集合演算の対象となる一つ目の集合を取得します。
     *
     * @return ISelectQuery 集合演算の対象となる一つ目の集合
     */
    public function getFirstQuery(): ISelectQuery
    {
        return $this->first_query;
    }

    /**
     * 集合演算の対象となる一つ目の集合を設定します。
     *
     * @param ISelectQuery $query 集合演算の対象となる一つ目の集合
     */
    public function setFirstQuery(ISelectQuery $query): void
    {
        $this->first_query = $query;
    }

    /**
     * 集合演算の対象となるもう一方の集合を取得します。
     *
     * @return ISelectQuery 集合演算の対象となるもう一方の集合
     */
    public function getSecondQuery(): ISelectQuery
    {
        return $this->second_query;
    }

    /**
     * 集合演算の対象となるもう一方の集合を設定します。
     *
     * @param ISelectQuery $query 集合演算の対象となるもう一方の集合
     */
    public function setSecondQuery(ISelectQuery $query): void
    {
        $this->second_query = $query;
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getSetsFormat(), $this->getFirstQuery(), $this->getSecondQuery());
    }
}
