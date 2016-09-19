<?php
namespace Yukar\Sql\Builder\Functions;

/**
 * SQL の COUNT 関数を表します。
 *
 * @author hiroki sugawara
 */
class Count extends BaseFilterableFunction
{
    /**
     * Count クラスの新しいインスタンスを初期化します。
     *
     * @param string $column 集計関数の対象となる表の列名
     * @param int    $filter 関数にかけるフィルターの種類
     */
    public function __construct(string $column = '*', int $filter = self::FILTER_ALL)
    {
        $this->setColumn($column);
        $this->setFilter($filter);
    }

    /**
     * SQLの関数の名前を取得します。
     *
     * @return string SQLの関数の名前
     */
    public function getFunctionName(): string
    {
        return 'COUNT';
    }
}
