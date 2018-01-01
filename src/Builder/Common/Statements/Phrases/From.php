<?php
namespace Yukar\Sql\Builder\Common\Statements\Phrases;

use Yukar\Linq\Collections\ListObject;
use Yukar\Sql\Interfaces\Builder\Common\Objects\IDataSource;
use Yukar\Sql\Interfaces\Builder\Common\Objects\ISqlQuerySource;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;

/**
 * SQLクエリの FROM 句を表します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class From implements IPhrases, ISqlQuerySource
{
    private $query_source_list = [];

    /**
     * From クラスの新しいインスタンスを初期化します。
     *
     * @param ISqlQuerySource[] ...$query_source_list SQLクエリの対象となる表やサブクエリ、または Join句（複数指定可）
     */
    public function __construct(ISqlQuerySource ...$query_source_list)
    {
        $this->setQuerySourceList(...$query_source_list);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'FROM %s';
    }

    /**
     * SQLクエリの対象となる表やサブクエリのリストを取得します。
     *
     * @return array SQLクエリの対象となる表やサブクエリ、または Join句（複数指定可）のリスト
     */
    public function getQuerySourceList(): array
    {
        return $this->query_source_list;
    }

    /**
     * SQLクエリの対象となる表やサブクエリを設定します。
     *
     * @param ISqlQuerySource[] ...$query_source_list SQLクエリの対象となる表やサブクエリ、または Join句（複数指定可）
     *
     * @throws \BadMethodCallException   引数にSQLクエリの対象となる表やサブクエリが一つ以上指定されていない場合
     * @throws \InvalidArgumentException 引数に表やサブクエリ、または Join句として指定できないオブジェクトが含まれる場合
     */
    public function setQuerySourceList(ISqlQuerySource ...$query_source_list): void
    {
        $list = new ListObject($query_source_list);

        if ($list->count($this->getContainsDataSourceClosure()) < 1) {
            throw new \BadMethodCallException();
        } elseif ($list->trueForAll($this->getAcceptableQuerySourceClosure()) === false) {
            throw new \InvalidArgumentException();
        }

        $this->query_source_list = $list->toArray();
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return sprintf($this->getPhraseString(), $this->getQuerySourceString());
    }

    /**
     * From 句に必要な表、サブクエリが一つ以上含まれているかどうかを判定するクロージャを取得します。
     *
     * @return \Closure From 句に必要な表、サブクエリが一つ以上含まれているかどうかを判定するクロージャ
     */
    private function getContainsDataSourceClosure(): \Closure
    {
        return function ($item): bool {
            return ($item instanceof IDataSource === true);
        };
    }

    /**
     * From 句に含めることができる表、サブクエリ、または Join 句であるかどうかを判定するクロージャを取得します。
     *
     * @return \Closure From 句に含めることができる表、サブクエリ、または Join 句であるかどうかを判定するクロージャ
     */
    private function getAcceptableQuerySourceClosure(): \Closure
    {
        return function ($item): bool {
            return ($item instanceof IDataSource === true || $item instanceof Join === true);
        };
    }

    /**
     * SQLクエリの対象となる表やサブクエリのリストを SQL として実行可能な文字列として取得します。
     *
     * @return string SQLクエリの対象となる表やサブクエリのリストの SQL として実行可能な文字列
     */
    private function getQuerySourceString(): string
    {
        $table_list = [];
        $join_list = [];

        foreach ($this->getQuerySourceList() as $item) {
            if ($item instanceof Join === true) {
                $join_list[] = $item;
            } else {
                $table_list[] = $item;
            }
        }

        return trim(sprintf('%s %s', implode(', ', $table_list), implode(' ', $join_list)));
    }
}
