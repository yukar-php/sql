<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

use Yukar\Sql\Interfaces\Builder\Common\Operators\IComparableOperator;

/**
 * 比較可能な演算子の基本機能を提供する抽象クラスです。
 *
 * @author hiroki sugawara
 */
abstract class BaseComparableOperator extends BaseConditionContainable implements IComparableOperator
{
    private $sign = self::SIGN_EQ;

    /**
     * 比較演算子を取得します。
     *
     * @return string 比較演算子
     */
    public function getSign(): string
    {
        return strval(self::SIGNS[$this->sign]);
    }

    /**
     * 比較演算子を設定します。
     *
     * @param int $sign 比較演算子
     */
    public function setSign(int $sign)
    {
        if (array_key_exists($sign, self::SIGNS) === false) {
            throw new \InvalidArgumentException();
        }

        $this->sign = $sign;
    }
}
