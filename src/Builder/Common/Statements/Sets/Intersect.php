<?php
namespace Yukar\Sql\Builder\Statements\Sets;

use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * INTERSECT 演算子による積集合を表します。
 *
 * @author hiroki sugawara
 */
class Intersect extends BaseSets
{
    /**
     * Intersect クラスの新しいインスタンスを初期化します。
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

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getSetsFormat(): string
    {
        return '%s INTERSECT %s';
    }
}
