<?php
namespace Yukar\Sql\Builder\Operators;

use Yukar\Sql\Interfaces\Builder\Operators\IConditionContainable;

/**
 * IS NULL 演算子または IS NOT NULL 演算子を表します。
 *
 * @author hiroki sugawara
 */
class IsNull implements IConditionContainable
{
    private $column = null;
    private $is_not = false;

    /**
     * IsNull クラスの新しいインスタンスを初期化します。
     *
     * @param string $column 演算子の対象となるカラムの名前
     * @param bool $is_not   演算子に NOT を付与するかどうか
     */
    public function __construct(string $column, bool $is_not = false)
    {
        $this->setColumn($column);
        $this->setIsNot($is_not);
    }

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s IS %sNULL';
    }

    /**
     * NULL演算子の対象となる列の名前を取得します。
     *
     * @return string NULL演算子の対象となる列の名前
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * NULL演算子の対象となる列の名前を設定します。
     *
     * @param string $column NULL演算子の対象となる列の名前
     *
     * @throws \InvalidArgumentException 引数 column に渡した値が空文字列の場合
     */
    public function setColumn(string $column)
    {
        if (empty($column) === true) {
            throw new \InvalidArgumentException();
        }

        $this->column = $column;
    }

    /**
     * NOT演算子を付与するかどうかを取得します。
     *
     * @return bool NOT演算子を付与するかどうか
     */
    public function isNot(): bool
    {
        return $this->is_not;
    }

    /**
     * NOT演算子を付与するかどうかを設定します。
     *
     * @param bool $is_not NOT演算子を付与するかどうか
     */
    public function setIsNot(bool $is_not)
    {
        $this->is_not = $is_not;
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getOperatorFormat(), $this->getColumn(), $this->isNot() ? 'NOT ' : '');
    }
}
