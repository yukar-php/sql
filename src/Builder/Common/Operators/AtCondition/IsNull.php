<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Common\Operators\IDeniableOperator;

/**
 * SQL の IS NULL 演算子を表します。
 *
 * @package Yukar\Sql\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class IsNull extends BaseConditionContainable implements IDeniableOperator
{
    /**
     * IsNull クラスの新しいインスタンスを初期化します。
     *
     * @param string $name 演算子の対象となるカラムの名前
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * 演算子の名前を取得します。
     *
     * @return string 演算子の名前
     */
    public function getOperator(): string
    {
        return 'IS';
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return 'NULL';
    }
}
