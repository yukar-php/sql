<?php
namespace Yukar\Sql\Builder\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * IN 演算子または NOT IN 演算子を表現します。
 *
 * @author hiroki sugawara
 */
class In extends BaseDeniableOperator
{
    private $needle = '';

    /**
     * In クラスの新しいインスタンスを初期化します。
     *
     * @param string $name  演算子の対象となる表や列の値と比較する値
     * @param mixed $needle 演算子の対象となる列リストまたは問い合わせクエリ
     * @param bool $is_not  演算子に NOT を付与するかどうか
     */
    public function __construct(string $name, $needle, bool $is_not = false)
    {
        $this->setName($name);
        $this->setNeedle($needle);
        $this->setIsNot($is_not);
    }

    /**
     * 演算子の名前を取得します。
     *
     * @return string 演算子の名前
     */
    public function getOperator(): string
    {
        return 'IN';
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
     * IN 演算子の対象となる列リストまたは問い合わせクエリを取得します。
     *
     * @return string IN 演算子の対象となる列リストまたは問い合わせクエリ
     */
    public function getNeedle(): string
    {
        return $this->needle;
    }

    /**
     * IN 演算子の対象となる列リストまたは問い合わせクエリを設定します。
     *
     * @param mixed $needle IN 演算子の対象となる列リストまたは問い合わせクエリ
     *
     * @throws \InvalidArgumentException 引数 needle に渡した値が不正な場合
     */
    public function setNeedle($needle)
    {
        if ($this->isAcceptableNeedle($needle) === false) {
            throw new \InvalidArgumentException();
        }

        ($this->isAcceptableExpression($needle) === true) && $this->setExpression($needle);
        ($this->isAcceptableSubQuery($needle) === true) && $this->setSubQuery($needle);
    }

    /**
     * IN 演算子の対象となる列リストを設定します。
     *
     * @param array|\Traversable $expression IN 演算子の対象となる列リスト
     *
     * @throws \InvalidArgumentException 引数 expression に渡した値が不正な場合
     *
     * @return In 列リストを設定した状態のクラス IN のオブジェクトインスタンス
     */
    public function setExpression($expression): self
    {
        if ($this->isAcceptableExpression($expression) === false) {
            throw new \InvalidArgumentException();
        }

        $this->needle = implode(
            ', ',
            ($expression instanceof \Traversable === true) ? iterator_to_array($expression) : $expression
        );

        return $this;
    }

    /**
     * IN 演算子の対象となる問い合わせクエリを設定します。
     *
     * @param string|ISelectQuery $sub_query IN 演算子の対象となる問い合わせクエリ
     *
     * @throws \InvalidArgumentException 引数 expression に渡した値が不正な場合
     *
     * @return In 問い合わせクエリを設定した状態のクラス IN のオブジェクトインスタンス
     */
    public function setSubQuery($sub_query): self
    {
        if ($this->isAcceptableSubQuery($sub_query) === false) {
            throw new \InvalidArgumentException();
        }

        $this->needle = strval($sub_query);

        return $this;
    }

    /**
     * IN 演算子の対象となる列リストまたは問い合わせクエリにすることができる値かどうかを判別します。
     *
     * @param mixed $needle 判別対象となる値
     *
     * @return bool IN 演算子の対象となる値の場合は、true。それ以外の場合は、false。
     */
    private function isAcceptableNeedle($needle): bool
    {
        return ($this->isAcceptableExpression($needle) === true || $this->isAcceptableSubQuery($needle) === true);
    }

    /**
     * IN 演算子の対象となる列リストにすることができる値かどうかを判別します。
     *
     * @param mixed $expression 判別対象となる値
     *
     * @return bool 列リストにすることができる値の場合は、true。それ以外の場合は、false。
     */
    private function isAcceptableExpression($expression): bool
    {
        return ((is_array($expression) === true && empty($expression) === false)
            || $expression instanceof \Traversable === true);
    }

    /**
     * IN 演算子の対象となる問い合わせクエリにすることができる値かどうかを判別します。
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
