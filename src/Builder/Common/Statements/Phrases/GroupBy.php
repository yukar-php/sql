<?php
namespace Yukar\Sql\Builder\Common\Statements\Phrases;

use Yukar\Sql\Interfaces\Builder\Common\Objects\IColumns;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;

/**
 * SQLクエリのGroupBy句とHaving句を表します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class GroupBy implements IPhrases
{
    private $group_by_list;
    private $having_cond;

    /**
     * GroupBy クラスの新しいインスタンスを初期化します。
     *
     * @param IColumns   $group_by GroupBy句に指定するテーブルの任意の列のリスト
     * @param ICondition $having   Having句に指定する条件のリスト
     */
    public function __construct(IColumns $group_by, ICondition $having = null)
    {
        $this->setGroupBy($group_by);
        (isset($having) === true) && $this->setHaving($having);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'GROUP BY %s';
    }

    /**
     * GroupBy句に指定するテーブルの任意の列のリストを取得します。
     *
     * @return IColumns GroupBy句に指定するテーブルの任意の列のリスト
     */
    public function getGroupBy(): IColumns
    {
        return $this->group_by_list;
    }

    /**
     * GroupBy句に指定するテーブルの任意の列のリストを設定します。
     *
     * @param IColumns $group_by GroupBy句に指定するテーブルの任意の列のリスト
     *
     * @throws \InvalidArgumentException GroupBy句に指定するテーブルの任意の列のリストの要素が空の場合
     */
    public function setGroupBy(IColumns $group_by): void
    {
        if ($group_by->hasOnlyStringItems() === false) {
            throw new \InvalidArgumentException();
        }

        $this->group_by_list = $group_by;
    }

    /**
     * Having句に指定する条件のリストを取得します。
     *
     * @return ICondition Having句に指定する条件のリスト。未設定の場合は null。
     */
    public function getHaving(): ?ICondition
    {
        return $this->having_cond;
    }

    /**
     * Having句に指定する条件のリストを設定します。
     *
     * @param ICondition $conditions Having句に指定する条件のリスト
     *
     * @throws \InvalidArgumentException Having句に指定する条件のリストの要素が空の場合
     */
    public function setHaving(ICondition $conditions): void
    {
        if (empty($conditions->getConditions()) === true) {
            throw new \InvalidArgumentException();
        }

        $this->having_cond = $conditions;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        $having = $this->getHaving() ?? '';

        return sprintf(
            $this->getPhraseString(),
            $this->getGroupBy() . (empty($having) ? "" : " HAVING {$having}")
        );
    }
}
