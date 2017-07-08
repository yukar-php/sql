<?php
namespace Yukar\Sql\Builder\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * EXISTS 演算子または NOT EXISTS 演算子を表現します。
 *
 * @author hiroki sugawara
 */
class Exists extends BaseDeniableOperator
{
    private $needle;

    /**
     * Exists クラスの新しいインスタンスを初期化します。
     *
     * @param string|ISelectQuery $needle 演算子の対象となる問い合わせクエリ
     * @param bool                $is_not 演算子に NOT を付与するかどうか
     */
    public function __construct($needle, bool $is_not = false)
    {
        $this->setNeedle($needle);
        $this->setIsNot($is_not);
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getOperatorFormat(), $this->getOperatorString(), $this->getValue());
    }

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s %s';
    }

    /**
     * 演算子の対象となる表や列の名前を設定します。
     *
     * @param string $name 演算子の対象となる表や列の名前
     *
     * @throws \BadMethodCallException 表や列の名前を設定しようとした場合
     */
    public function setName(string $name)
    {
        throw new \BadMethodCallException();
    }

    /**
     * 演算子を取得します。
     *
     * @return string 演算子
     */
    public function getOperator(): string
    {
        return 'EXISTS';
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return sprintf('(%s)', $this->getNeedle());
    }

    /**
     * EXISTS 演算子の対象となる問い合わせクエリを取得します。
     *
     * @return string EXISTS 演算子の対象となる問い合わせクエリ
     */
    public function getNeedle(): string
    {
        return strval($this->needle);
    }

    /**
     * EXISTS 演算子の対象となる問い合わせクエリを設定します。
     *
     * @param string|ISelectQuery $needle EXISTS 演算子の対象となる問い合わせクエリ
     *
     * @throws \InvalidArgumentException 引数 expression に渡した値が不正な場合
     */
    public function setNeedle($needle)
    {
        if ($this->isAcceptableSubQuery($needle) === false) {
            throw new \InvalidArgumentException();
        }

        $this->needle = $needle;
    }

    /**
     * EXISTS 演算子の対象となる問い合わせクエリにすることができる値かどうかを判別します。
     *
     * @param mixed $sub_query 判別対象となる値
     *
     * @return bool 問い合わせクエリにすることができる値の場合は、true。それ以外の場合は、false。
     */
    private function isAcceptableSubQuery($sub_query): bool
    {
        return ((is_string($sub_query) === true && empty($sub_query) === false)
            || $sub_query instanceof ISelectQuery === true);
    }
}
