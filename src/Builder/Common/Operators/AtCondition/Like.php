<?php
namespace Yukar\Sql\Builder\Operators\AtCondition;

/**
 * LIKE 演算子または NOT LIKE 演算子を表します。
 *
 * @author hiroki sugawara
 */
class Like extends BaseDeniableOperator
{
    private $pattern = '';

    /**
     * Like クラスの新しいインスタンスを初期化します。
     *
     * @param string $column  演算子の対象となるカラムの名前
     * @param string $pattern 演算子の対象となる検索パターン
     * @param bool   $is_not  演算子に NOT を付与するかどうか
     */
    public function __construct(string $column, string $pattern, bool $is_not = false)
    {
        $this->setName($column);
        $this->setPattern($pattern);
        $this->setIsNot($is_not);
    }

    /**
     * 演算子の名前を取得します。
     *
     * @return string 演算子の名前
     */
    public function getOperator(): string
    {
        return 'LIKE';
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return sprintf('\'%s\'', $this->getPattern());
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
}
