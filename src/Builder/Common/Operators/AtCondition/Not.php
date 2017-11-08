<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Common\Operators\IDeniableOperator;

/**
 * SQL の否定演算子を表します。
 *
 * @package Yukar\Sql\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class Not extends BaseConditionContainable
{
    private $deniable_operator;

    /**
     * Not クラスの新しいインスタンスを初期化します。
     *
     * @param IDeniableOperator $condition 否定演算子を付与可能な演算子のオブジェクト
     */
    public function __construct(IDeniableOperator $condition)
    {
        $this->setDeniableoperator($condition);
    }

    /**
     * 演算子の対象となる表や列の名前を取得します。
     *
     * @return string 演算子の対象となる表や列の名前
     *
     * @throws \BadMethodCallException 表や列の名前を取得しようとした場合
     */
    public function getName(): string
    {
        throw new \BadMethodCallException();
    }

    /**
     * 演算子の対象となる表や列の名前を設定します。
     *
     * @param string $name 演算子の対象となる表や列の名前
     *
     * @throws \BadMethodCallException 表や列の名前を設定しようとした場合
     */
    public function setName(string $name): void
    {
        throw new \BadMethodCallException();
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return $this->getDeniableOperator()->getValue();
    }

    /**
     * 否定演算子を付与可能な演算子のオブジェクトを取得します。
     *
     * @return IDeniableOperator 否定演算子を付与可能な演算子のオブジェクト
     */
    public function getDeniableOperator(): IDeniableOperator
    {
        return $this->deniable_operator;
    }

    /**
     * 否定演算子を付与可能な演算子のオブジェクトを設定します。
     *
     * @param IDeniableOperator $deniable_operator 否定演算子を付与可能な演算子のオブジェクト
     */
    public function setDeniableOperator(IDeniableOperator $deniable_operator): void
    {
        $this->deniable_operator = $deniable_operator;
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return ltrim(sprintf(
            $this->getOperatorFormat(),
            $this->getDeniableOperator()->getName(),
            $this->getOperatorString(),
            $this->getValue()
        ));
    }

    /**
     * 否定演算子を付与した演算子の文字列を取得します。
     *
     * @return string 否定演算子を付与した演算子の文字列
     */
    private function getOperatorString(): string
    {
        $operator_params = [ $this->getOperator(), $this->getDeniableOperator()->getOperator() ];
        $operators = ($this->getDeniableOperator() instanceof IsNull) ?
            array_reverse($operator_params) : $operator_params;

        return sprintf('%s %s', ...$operators);
    }
}
