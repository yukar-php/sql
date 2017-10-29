<?php
namespace Yukar\Sql\Builder\Common\Operators\AtCondition;

/**
 * IS NULL 演算子または IS NOT NULL 演算子を表します。
 *
 * @author hiroki sugawara
 */
class IsNull extends BaseDeniableOperator
{
    /**
     * IsNull クラスの新しいインスタンスを初期化します。
     *
     * @param string $name   演算子の対象となるカラムの名前
     * @param bool   $is_not 演算子に NOT を付与するかどうか
     */
    public function __construct(string $name, bool $is_not = false)
    {
        $this->setName($name);
        $this->setIsNot($is_not);
        $this->setIsBackEnd(true);
    }

    /**
     * 演算子の名前を取得します。
     *
     * @return string 演算子の名前
     */
    public function getOperator(): string
    {
        return 'IS';
    }

    /**
     * 演算子の対象となる表や列の値と比較する値を取得します。
     *
     * @return string 演算子の対象となる表や列の値と比較する値
     */
    public function getValue(): string
    {
        return 'NULL';
    }
}
