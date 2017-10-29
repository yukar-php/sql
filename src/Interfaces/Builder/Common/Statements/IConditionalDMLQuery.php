<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Statements;

use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;

/**
 * 条件式を付与することができる問い合わせクエリを定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IConditionalDMLQuery extends ISqlDMLQuery
{
    /**
     * 問い合わせクエリの条件式を取得します。
     *
     * @return ICondition|null 問い合わせクエリの条件式
     */
    public function getWhere();
}
