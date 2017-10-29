<?php
namespace Yukar\Sql\Builder\Common\Statements\Dml;

use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IConditionalDMLQuery;

/**
 * 条件式を付与することができる問い合わせクエリの機能を持つ抽象クラスです。
 *
 * @author hiroki sugawara
 */
abstract class BaseConditionalDMLQuery extends BaseSqlDMLQuery implements IConditionalDMLQuery
{
    private $where;

    /**
     * 問い合わせクエリの条件式を取得します。
     *
     * @return ICondition|null 問い合わせクエリの条件式
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * 問い合わせクエリの条件式を設定します。
     *
     * @param ICondition $condition 問い合わせクエリの条件式
     *
     * @throws \InvalidArgumentException 引数 condition の要素が空の場合
     */
    protected function setWhereCondition(ICondition $condition)
    {
        if (empty($condition->getConditions()) === true) {
            throw new \InvalidArgumentException();
        }

        $this->where = $condition;
    }

    /**
     * 問い合わせクエリの条件式を WHERE 句として取得します。
     *
     * @return string 問い合わせクエリの WHERE 句
     */
    protected function getWhereString(): string
    {
        return (empty($this->getWhere()) === false) ? sprintf('WHERE %s', $this->getWhere()) : '';
    }
}
