<?php
namespace Yukar\Sql\Builder\Operators;

use Yukar\Sql\Interfaces\Builder\Operators\IConditionContainable;

/**
 * LIKE 演算子または NOT LIKE 演算子を表します。
 *
 * @author hiroki sugawara
 */
class Like implements IConditionContainable
{
    private $column = null;
    private $pattern = null;
    private $is_not = false;

    /**
     * Like クラスの新しいインスタンスを初期化します。
     *
     * @param string $column  演算子の対象となるカラムの名前
     * @param string $pattern 演算子の対象となる検索パターン
     * @param bool $is_not    演算子に NOT を付与するかどうか
     */
    public function __construct(string $column, string $pattern, bool $is_not = false)
    {
        $this->setColumn($column);
        $this->setPattern($pattern);
        $this->setIsNot($is_not);
    }

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s %sLIKE \'%s\'';
    }

    /**
     * LIKE 演算子の対象となる列の名前を取得します。
     *
     * @return string LIKE 演算子の対象となる列の名前
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * LIKE 演算子の対象となる列の名前を取得します。
     *
     * @param string $column LIKE 演算子の対象となる列の名前
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
     * 演算子の対象となる検索パターンを取得します。
     *
     * @return string 演算子の対象となる検索パターン
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * 演算子の対象となる検索パターンを設定します。
     *
     * @param string $pattern 演算子の対象となる検索パターン
     *
     * @throws \InvalidArgumentException 引数 pattern に渡した値が空文字列の場合
     */
    public function setPattern(string $pattern)
    {
        if (empty($pattern) === true) {
            throw new \InvalidArgumentException();
        }

        $this->pattern = $pattern;
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
        return sprintf(
            $this->getOperatorFormat(),
            $this->getColumn(),
            $this->isNot() ? 'NOT ' : '',
            $this->getPattern()
        );
    }
}
