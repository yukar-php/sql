<?php
namespace Yukar\Sql\Builder\Operators\AtCondition;

/**
 * 算術演算子を表現します。
 *
 * @author hiroki sugawara
 */
class Expression extends BaseConditionContainable
{
    const SIGN_EQ = 1;
    const SIGN_NE = 2;
    const SIGN_GT = 3;
    const SIGN_AO = 4;
    const SIGN_LT = 5;
    const SIGN_OU = 6;

    const SIGNS = [
        self::SIGN_EQ => '=',
        self::SIGN_NE => '<>',
        self::SIGN_GT => '>',
        self::SIGN_AO => '>=',
        self::SIGN_LT => '<',
        self::SIGN_OU => '<=',
    ];

    private $value = '';
    private $sign = '';

    /**
     * Expression クラスの新しいインスタンスを初期化します。
     *
     * @param string $name  演算子の対象となる表や列の名前
     * @param string $value 演算子の対象となる表や列の値と比較する値
     * @param int $sign     演算子の種類。演算子に指定した名前の値と比較に使用する値の比較に使う。<br />
     *                      値が等しい時は、Expression::SIGN_EQ、値が等しくない時は、Expression::SIGN_NE。<br />
     *                      値が比較値よりも小さい時は、Expression::SIGN_LT、大きい時は、Expression::SIGN_GT。<br />
     *                      値が比較値以下の場合は、Expression::SIGN_OU、以上の時は、Expression::SIGN_AO。
     */
    public function __construct(string $name, string $value, int $sign = self::SIGN_EQ)
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setOperator($sign);
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
    public function setValue(string $value)
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
        return $this->sign;
    }

    /**
     * 演算子を設定します。
     *
     * @param int $sign 演算子の種類。演算子に指定した名前の値と比較に使用する値の比較に使う。<br />
     *                  値が等しい時は、Expression::SIGN_EQ、値が等しくない時は、Expression::SIGN_NE。<br />
     *                  値が比較値よりも小さい時は、Expression::SIGN_LT、大きい時は、Expression::SIGN_GT。<br />
     *                  値が比較値以下の場合は、Expression::SIGN_OU、以上の時は、Expression::SIGN_AO。
     *
     * @throws \InvalidArgumentException 引数 sign に渡した値が許容できない場合
     *
     * @return Expression 演算子を設定した状態のクラス Expression のオブジェクトインスタンス
     */
    public function setOperator(int $sign): Expression
    {
        if (empty(self::SIGNS[$sign]) === true) {
            throw new \InvalidArgumentException();
        }

        $this->sign = self::SIGNS[$sign];

        return $this;
    }
}
