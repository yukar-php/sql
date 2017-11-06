<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;

/**
 * ANY 演算子、SOME 演算子、ALL 演算子のいずれかを表現します。
 *
 * @author hiroki sugawara
 */
class GroupExpression extends BaseComparableOperator
{
    /** ANY演算子であることを示す定数 */
    public const ANY_MODIFIER = 1;
    /** SOME演算子であることを示す定数 */
    public const SOME_MODIFIER = 2;
    /** ALL演算子であることを示す定数 */
    public const ALL_MODIFIER = 3;

    private const MODIFIERS = [
        self::ANY_MODIFIER => 'ANY',
        self::SOME_MODIFIER => 'SOME',
        self::ALL_MODIFIER => 'ALL',
    ];

    private $modifier;
    private $needle;

    /**
     * BaseGroupExpression クラスの新しいインスタンスを初期化します。
     *
     * @param string $name     演算子の対象となる表や列の名前
     * @param mixed  $needle   演算子の対象となる問い合わせクエリ
     * @param int    $modifier 演算子の修飾子の種類
     * @param int    $sign     演算子の比較記号の種類
     */
    public function __construct(string $name, $needle, int $modifier, int $sign)
    {
        $this->setName($name);
        $this->setNeedle($needle);
        $this->setModifier($modifier);
        $this->setSign($sign);
    }

    /**
     * 演算子を取得します。
     *
     * @return string 演算子
     */
    public function getOperator(): string
    {
        return sprintf('%s %s', $this->getSign(), $this->getModifier());
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
     * 演算子の修飾子を取得します。
     *
     * @return string 演算子の修飾子
     */
    public function getModifier(): string
    {
        return strval(self::MODIFIERS[$this->modifier]);
    }

    /**
     * 演算子の修飾子を設定します。
     *
     * @param int $modifier 演算子の修飾子
     */
    public function setModifier(int $modifier): void
    {
        if (array_key_exists($modifier, self::MODIFIERS) === false) {
            throw new \InvalidArgumentException();
        }

        $this->modifier = $modifier;
    }

    /**
     * 演算子の対象となる列リストまたは問い合わせクエリを取得します。
     *
     * @return string 演算子の対象となる列リストまたは問い合わせクエリ
     */
    public function getNeedle(): string
    {
        return strval($this->needle);
    }

    /**
     * 演算子の対象となる列リストまたは問い合わせクエリを設定します。
     *
     * @param mixed $needle 演算子の対象となる列リストまたは問い合わせクエリ
     *
     * @throws \InvalidArgumentException 引数 needle に渡した値が不正な場合
     */
    public function setNeedle($needle): void
    {
        if ($this->isAcceptableSubQuery($needle) === false) {
            throw new \InvalidArgumentException();
        }

        $this->needle = $needle;
    }

    /**
     * 演算子の対象となる問い合わせクエリにすることができる値かどうかを判別します。
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
