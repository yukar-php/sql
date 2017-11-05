<?php
namespace Yukar\Sql\Builder\Common\Statements\Sets;

use Yukar\Sql\Interfaces\Builder\Common\Statements\ISelectQuery;

/**
 * UNION 演算子による和集合を表します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Sets
 * @author hiroki sugawara
 */
class Union extends BaseSets
{
    /**
     * Union クラスの新しいインスタンスを初期化します。
     *
     * @param ISelectQuery $first  集合演算の対象となる一つ目の集合
     * @param ISelectQuery $second 集合演算の対象となるもう一方の集合
     * @param bool         $is_all 集合の重複を削除しないかどうか
     */
    public function __construct(ISelectQuery $first, ISelectQuery $second, bool $is_all = false)
    {
        $this->setFirstQuery($first);
        $this->setSecondQuery($second);
        $this->setIsAll($is_all);
    }
}
