<?php
namespace Yukar\Sql\Builder\Common\Objects;

use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Operators\IConditionContainable;

/**
 * SQL クエリの条件式を定義します。
 *
 * @package Yukar\Sql\Builder\Common\Objects
 * @author hiroki sugawara
 */
class Conditions implements ICondition
{
    /** 条件式の各項目は「積集合（AND）」であることを示す定数 */
    public const OPERATION_AND = 1;
    /** 条件式の各項目は「和集合（OR）」であることを示す定数 */
    public const OPERATION_OR = 2;

    private const OPERATORS = [
        self::OPERATION_AND => 'AND',
        self::OPERATION_OR => 'OR'
    ];

    private $operator = '';
    private $conditions = [];

    /**
     * Condition クラスの新しいインスタンスを初期化します。
     *
     * @param int $operate_type 論理演算子の種類。
     *                          論理積の場合は、Conditions::OPERATION_ANDを、論理和の場合は、Conditions::OPERATION_OR。
     */
    public function __construct(int $operate_type = self::OPERATION_AND)
    {
        $this->setOperation($operate_type);
    }

    /**
     * 条件式の論理演算子を取得します。
     *
     * @return string 条件式の論理演算子
     */
    public function getOperation(): string
    {
        return $this->operator ?? self::OPERATORS[self::OPERATION_AND];
    }

    /**
     * 条件式の論理演算子を設定します。
     *
     * @param int $operate_type 論理演算子の種類。
     *                          論理積の場合は、Conditions::OPERATION_ANDを、論理和の場合は、Conditions::OPERATION_OR。
     */
    public function setOperation(int $operate_type): void
    {
        $this->operator = self::OPERATORS[$operate_type] ?? self::OPERATORS[self::OPERATION_AND];
    }

    /**
     * 条件式の要素を取得します。
     *
     * @return array 条件式の要素となる配列
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * 条件式の要素を追加します。
     *
     * @param string|ICondition $condition 追加する条件式の要素
     *
     * @throws \TypeError         引数 $condition が、条件式の要素とならない型の場合
     * @throws \OverflowException 条件式の要素が既に2個指定済みであった場合
     *
     * @return ICondition 条件式の要素を追加した状態の ICondition を継承するオブジェクトのインスタンス
     */
    public function addCondition($condition): ICondition
    {
        if ($this->isAcceptableCondition($condition) === false) {
            throw new \TypeError();
        } elseif ($this->isFillConditions()) {
            throw new \OverflowException();
        }

        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * 条件式の要素を設定します。
     *
     * @param string|ICondition $first  条件式の一つ目の要素
     * @param string|ICondition $second 条件式の二つ目の要素
     *
     * @throws \TypeError 引数 $first または $second が、条件式の要素とならない型の場合
     *
     * @return ICondition 条件式の要素を設定した状態の ICondition を継承するオブジェクトのインスタンス
     */
    public function setConditions($first, $second): ICondition
    {
        if ($this->isAcceptableCondition($first) === false || $this->isAcceptableCondition($second) === false) {
            throw new \TypeError();
        }

        $this->conditions = [ $first, $second ];

        return $this;
    }

    /**
     * オブジェクトを表す文字列を取得します。
     *
     * @return string オブジェクトを表す文字列
     */
    public function __toString(): string
    {
        $condition = $this->getConditions();

        array_walk(
            $condition,
            function (&$item) {
                ($item instanceof ICondition) && $item = "({$item})";
            }
        );

        return implode(" {$this->getOperation()} ", $condition);
    }

    /**
     * 引数の値が条件式の要素として許容される型の値かどうかを判別します。
     *
     * @param mixed $condition 条件式の要素として適切か判定する値
     *
     * @return bool 引数 $condition の値が、条件式の要素として適切な場合は、true。それ以外の場合は、false。
     */
    private function isAcceptableCondition($condition): bool
    {
        return (is_string($condition) === true || $condition instanceof ICondition === true
            || $condition instanceof IConditionContainable === true);
    }

    /**
     * 条件式の要素の内容が既に満たされているかどうかを判別します。
     *
     * @return bool 条件式に要素を追加可能な場合は、true。それ以外の場合は、false。
     */
    private function isFillConditions(): bool
    {
        return (count($this->getConditions()) >= 2);
    }
}
