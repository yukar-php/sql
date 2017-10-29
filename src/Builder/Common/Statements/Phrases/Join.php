<?php
namespace Yukar\Sql\Builder\Common\Statements\Phrases;

use Yukar\Sql\Interfaces\Builder\Common\Objects\ICondition;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IDataSource;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;

/**
 * SQLクエリの JOIN 句を表します。
 *
 * @author hiroki sugawara
 */
class Join implements IPhrases
{
    /** JOIN 句が「内部結合」であることを示す定数 */
    const INNER_JOIN = 1;
    /** JOIN 句が「外部結合（左優先）」であることを示す定数 */
    const LEFT_JOIN = 2;
    /** JOIN 句が「外部結合（右優先）」であることを示す定数 */
    const RIGHT_JOIN = 3;
    /** JOIN 句が「交差結合」であることを示す定数 */
    const CROSS_JOIN = 4;

    const JOIN_TYPE = [
        self::INNER_JOIN => 'INNER',
        self::LEFT_JOIN => 'LEFT',
        self::RIGHT_JOIN => 'RIGHT',
        self::CROSS_JOIN => 'CROSS'
    ];

    private $data_source = '';
    private $join_type = '';
    private $on_condition;

    /**
     * Join クラスの新しいインスタンスを初期化します。
     *
     * @param string|IDataSource $data_source  JOIN 句の対象となる表またはサブクエリ
     * @param int                $join_type    JOIN 句の種類
     * @param ICondition         $on_condition ON 句の条件
     */
    public function __construct($data_source, int $join_type = self::INNER_JOIN, ICondition $on_condition = null)
    {
        $this->setDataSource($data_source);
        $this->setJoinType($join_type);
        (isset($on_condition) === true) && $this->setOnCondition($on_condition);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return '%s JOIN %s%s';
    }

    /**
     * JOIN 句の対象となる表またはサブクエリを取得します。
     *
     * @return string JOIN 句の対象となる表またはサブクエリ
     */
    public function getDataSource(): string
    {
        return strval($this->data_source);
    }

    /**
     * JOIN 句の対象となる表またはサブクエリを設定します。
     *
     * @param string|IDataSource $data_source JOIN 句の対象となる表またはサブクエリ
     *
     * @throws \InvalidArgumentException 引数 data_source に不適切な型の値を渡した場合
     */
    public function setDataSource($data_source)
    {
        if ($this->isAcceptableDataSource($data_source) === false) {
            throw new \InvalidArgumentException();
        }

        $this->data_source = $data_source;
    }

    /**
     * JOIN 句の種類を取得します。
     *
     * @return string JOIN 句の種類
     */
    public function getJoinType(): string
    {
        return $this->join_type;
    }

    /**
     * JOIN 句の種類を設定します。
     *
     * @param int $join_type JOIN 句の種類
     */
    public function setJoinType(int $join_type)
    {
        $this->join_type = self::JOIN_TYPE[$join_type] ?? self::JOIN_TYPE[self::INNER_JOIN];
    }

    /**
     * HAVING 句の条件を取得します
     *
     * @return ICondition|null HAVING 句の条件
     */
    public function getOnCondition()
    {
        return $this->on_condition;
    }

    /**
     * HAVING 句の条件を設定します。
     *
     * @param ICondition $on_condition HAVING 句の条件
     *
     * @throws \InvalidArgumentException 引数 on_condition に渡した条件式の内容が空の場合
     */
    public function setOnCondition(ICondition $on_condition)
    {
        if (empty($on_condition->getConditions()) === true) {
            throw new \InvalidArgumentException();
        }

        $this->on_condition = $on_condition;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return sprintf(
            $this->getPhraseString(),
            $this->getJoinType(),
            $this->getDataSource(),
            ((empty($this->getOnCondition()) === true) ? '' : sprintf(' ON %s', $this->getOnCondition()))
        );
    }

    /**
     * JOIN 演算子の対象となるテーブルの名前または問い合わせクエリにすることができる値かどうかを判別します。
     *
     * @param mixed $sub_query 判別対象となる値
     *
     * @return bool テーブルの名前または問い合わせクエリにすることができる値の場合は、true。それ以外の場合は、false。
     */
    private function isAcceptableDataSource($sub_query): bool
    {
        return ((is_string($sub_query) === true && empty($sub_query) === false)
            || $sub_query instanceof IDataSource === true);
    }
}
