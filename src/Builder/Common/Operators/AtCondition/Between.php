<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Common\Operators\IDeniableOperator;

/**
 * SQL の BETWEEN 演算子を表現します。
 *
 * @package Yukar\Sql\Builder\Common\Operators\AtCondition
 * @author hiroki sugawara
 */
class Between extends BaseConditionContainable implements IDeniableOperator
{
    private $from_value = '';
    private $to_value = '';

    /**
     * Between クラスの新しいインスタンスを初期化します。
     *
     * @param string $name       演算子の対象となるカラムの名前または値
     * @param string $from_value BETWEEN演算子の範囲の始端となる値
     * @param string $to_value   BETWEEN演算子の範囲の終端となる値
     */
    public function __construct(string $name, string $from_value, string $to_value)
    {
        $this->setName($name);
        $this->setFromValue($from_value);
        $this->setToValue($to_value);
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return sprintf('%s AND %s', $this->getFromValue(), $this->getToValue());
    }

    /**
     * BETWEEN演算子の範囲の始端となる値を取得します。
     *
     * @return string BETWEEN演算子の範囲の始端となる値
     */
    public function getFromValue(): string
    {
        return $this->from_value;
    }

    /**
     * BETWEEN演算子の範囲の始端となる値を設定します。
     *
     * @param string $from_value BETWEEN演算子の範囲の始端となる値
     *
     * @throws \InvalidArgumentException 引数 from_value に空文字列を渡した場合
     */
    public function setFromValue(string $from_value): void
    {
        if (empty($from_value) === true) {
            throw new \InvalidArgumentException();
        }

        $this->from_value = $from_value;
    }

    /**
     * BETWEEN演算子の範囲の終端となる値を取得します。
     *
     * @return string BETWEEN演算子の範囲の終端となる値
     */
    public function getToValue(): string
    {
        return $this->to_value;
    }

    /**
     * BETWEEN演算子の範囲の終端となる値を設定します。
     *
     * @param string $to_value BETWEEN演算子の範囲の終端となる値
     *
     * @throws \InvalidArgumentException 引数 to_value に空文字列を渡した場合
     */
    public function setToValue(string $to_value): void
    {
        if (empty($to_value) === true) {
            throw new \InvalidArgumentException();
        }

        $this->to_value = $to_value;
    }
}
