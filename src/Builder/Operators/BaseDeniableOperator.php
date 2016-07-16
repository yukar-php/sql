<?php
namespace Yukar\Sql\Builder\Operators;

use Yukar\Sql\Interfaces\Builder\Operators\IDeniableOperator;

/**
 * 否定演算子を付与することができる演算子の基本機能を提供する抽象クラスです。
 *
 * @author hiroki sugawara
 */
abstract class BaseDeniableOperator extends BaseConditionContainable implements IDeniableOperator
{
    private $is_not = false;
    private $back_end = false;

    /**
     * NOT 演算子を付与するかどうかを取得します。
     *
     * @return bool NOT演算子を付与するかどうか
     */
    public function isNot(): bool
    {
        return $this->is_not;
    }

    /**
     * NOT 演算子を付与するかどうかを設定します。
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
        $base_params = [ $this->isNot() === true ? 'NOT' : '', ' ', $this->getOperator() ];
        $params = ($this->isBackEnd() === true) ? array_reverse($base_params) : $base_params;

        return sprintf(
            $this->getOperatorFormat(),
            $this->getName(),
            trim(sprintf('%s%s%s', ...$params)),
            $this->getValue()
        );
    }

    /**
     * NOT 演算子を対象の演算子に後置するかどうかを取得します。
     *
     * @return bool 対象の演算子に後置する場合は true。それ以外の場合は false。
     */
    protected function isBackEnd(): bool
    {
        return $this->back_end;
    }

    /**
     * NOT 演算子を対象の演算子に後置するかどうかを設定します。
     *
     * @param bool $back_end NOT 演算子を対象の演算子に後置するかどうか
     */
    protected function setIsBackEnd(bool $back_end)
    {
        $this->back_end = $back_end;
    }
}
