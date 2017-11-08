<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

/**
 * 算術演算子を表現します。
 *
 * @author hiroki sugawara
 */
class Expression extends BaseComparableOperator
{
    private $value = '';

    /**
     * Expression クラスの新しいインスタンスを初期化します。
     *
     * @param string $name  演算子の対象となる表や列の名前
     * @param string $value 演算子の対象となる表や列の値と比較する値
     * @param int    $sign  演算子の種類。演算子に指定した名前の値と比較に使用する値の比較に使う。<br />
     *                      値が等しい時は、Expression::SIGN_EQ、値が等しくない時は、Expression::SIGN_NE。<br />
     *                      値が比較値よりも小さい時は、Expression::SIGN_LT、大きい時は、Expression::SIGN_GT。<br />
     *                      値が比較値以下の場合は、Expression::SIGN_OU、以上の時は、Expression::SIGN_AO。
     */
    public function __construct(string $name, string $value, int $sign = self::SIGN_EQ)
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setSign($sign);
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を設定します。
     *
     * @param string $value 演算子の対象となる表や列の値と比較する値
     *
     * @throws \InvalidArgumentException 引数 value に渡した値が空文字列の場合
     */
    public function setValue(string $value): void
    {
        if (empty($value) === true && is_numeric($value) === false) {
            throw new \InvalidArgumentException();
        }

        $this->value = $value;
    }

    /**
     * 演算子を取得します。
     *
     * @return string 演算子
     */
    public function getOperator(): string
    {
        return $this->getSign();
    }
}
