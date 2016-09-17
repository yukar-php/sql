<?php
namespace Yukar\Sql\Builder\Functions;

use Yukar\Sql\Interfaces\Builder\Functions\IFilterableFunction;

/**
 * フィルタ可能な関数の基本機能を提供する抽象クラスです。
 *
 * @author hiroki sugawara
 */
abstract class BaseFilterableFunction implements IFilterableFunction
{
    private $filter = self::FILTER_ALL;

    /**
     * 関数にかけるフィルタを取得します。
     *
     * @return string 関数にかけるフィルタ
     */
    public function getFilter(): string
    {
        return strval(self::FILTERS[$this->filter]);
    }

    /**
     * 関数にかけるフィルターを設定します。
     *
     * @param int $filter 関数にかけるフィルターの種類
     */
    public function setFilter(int $filter)
    {
        $this->filter = array_key_exists($filter, self::FILTERS) ? $filter : self::FILTER_ALL;
    }
}
