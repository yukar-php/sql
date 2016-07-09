<?php
namespace Yukar\Sql\Builder\Operators;

use Yukar\Sql\Interfaces\Builder\Operators\IConditionContainable;

/**
 * BETWEEN演算子を表現します。
 *
 * @author hiroki sugawara
 */
class Between implements IConditionContainable
{
    private $column = null;
    private $from_value = null;
    private $to_value = null;

    /**
     * Between クラスの新しいインスタンスを初期化します。
     *
     * @param string $column     演算子の対象となるカラムの名前または値
     * @param string $from_value BETWEEN演算子の範囲の始端となる値
     * @param string $to_value   BETWEEN演算子の範囲の終端となる値
     */
    public function __construct(string $column, string $from_value, string $to_value)
    {
        $this->setColumn($column);
        $this->setFromValue($from_value);
        $this->setToValue($to_value);
    }

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s BETWEEN %s AND %s';
    }

    /**
     * 演算子の対象となるカラムの名前または値を取得します。
     *
     * @return string 演算子の対象となるカラムの名前または値
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * 演算子の対象となるカラムの名前または値を設定します。
     *
     * @param string $column 演算子の対象となるカラムの名前または値
     *
     * @throws \InvalidArgumentException 引数 column に空文字列を渡した場合
     */
    public function setColumn(string $column)
    {
        if (empty($column) === true) {
            throw new \InvalidArgumentException();
        }

        $this->column = $column;
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
    public function setFromValue(string $from_value)
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
    public function setToValue(string $to_value)
    {
        if (empty($to_value) === true) {
            throw new \InvalidArgumentException();
        }

        $this->to_value = $to_value;
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getOperatorFormat(), $this->getColumn(), $this->getFromValue(), $this->getToValue());
    }
}
