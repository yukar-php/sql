<?php
namespace Yukar\Sql\Builder\Statements\Phrases;

use Yukar\Sql\Builder\Objects\Columns;
use Yukar\Sql\Builder\Objects\Conditions;
use Yukar\Sql\Interfaces\Builder\Statements\IPhrases;

/**
 * SQLクエリのGroupBy句とHaving句を表します。
 *
 * @author hiroki sugawara
 */
class GroupBy implements IPhrases
{
    private $group_by_list;
    private $having_cond;

    /**
     * GroupBy クラスの新しいインスタンスを初期化します。
     *
     * @param Columns $group_by
     * @param Conditions $having
     */
    public function __construct(Columns $group_by, Conditions $having = null)
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
     * @return Columns GroupBy句に指定するテーブルの任意の列のリスト
     */
    public function getGroupBy(): Columns
    {
        return $this->group_by_list;
    }

    /**
     * GroupBy句に指定するテーブルの任意の列のリストを設定します。
     *
     * @param Columns $group_by GroupBy句に指定するテーブルの任意の列のリスト
     *
     * @throws \InvalidArgumentException GroupBy句に指定するテーブルの任意の列のリストの要素が空の場合
     */
    public function setGroupBy(Columns $group_by)
    {
        if (empty($group_by->getColumns()) === true) {
            throw new \InvalidArgumentException();
        }

        $this->group_by_list = $group_by;
    }

    /**
     * Having句に指定する条件のリストを取得します。
     *
     * @return mixed Having句に指定する条件のリスト。未設定の場合は null。
     */
    public function getHaving()
    {
        return $this->having_cond;
    }

    /**
     * Having句に指定する条件のリストを設定します。
     *
     * @param Conditions $conditions Having句に指定する条件のリスト
     *
     * @throws \InvalidArgumentException Having句に指定する条件のリストの要素が空の場合
     */
    public function setHaving(Conditions $conditions)
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
