<?php
namespace Yukar\Sql\Builder\Specialized\MySQL\Statements\Phrases;

use Yukar\Linq\Collections\ListObject;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;
use Yukar\Sql\Interfaces\Builder\Specialized\MySQL\IMySQLPhrase;

/**
 * MySQL の SQL クエリの FROM 句と一緒に扱うことができる Partition 句を表します。
 *
 * @package Yukar\Sql\Builder\Specialized\MySQL\Statements\Phrases
 * @author  hiroki sugawara
 */
class Partition implements IPhrases, IMySQLPhrase
{
    private $partitions = [];

    /**
     * Partition クラスの新しいインスタンスを初期化します。
     *
     * @param array ...$partitions Partition句に指定するパーティションの名前（複数指定可）
     */
    public function __construct(...$partitions)
    {
        $this->setPartitions(...$partitions);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'PARTITION (%s)';
    }

    /**
     * Partition句に指定したパーティションの名前の一覧を取得します。
     *
     * @return array Partition句に指定したパーティションの名前の一覧
     */
    public function getPartitions(): array
    {
        return $this->partitions;
    }

    /**
     * Partition句に指定するパーティションの名前を設定します。
     *
     * @param array ...$partitions Partition句に指定するパーティションの名前（複数指定可）
     *
     * @throws \InvalidArgumentException 引数 partitions に文字列（空白文字列を除く）以外の値を一つでも渡した場合
     */
    public function setPartitions(...$partitions): void
    {
        if ($this->isAcceptablePartition($partitions) === false) {
            throw new \InvalidArgumentException();
        }

        $this->partitions = $partitions;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return sprintf($this->getPhraseString(), implode(', ', $this->getPartitions()));
    }

    /**
     * パーティションの要素として許容される値かどうかを判別します。
     *
     * @param array $list パーティションの要素として許容できるか判定する値の一覧
     *
     * @return bool パーティションの要素として指定した値が全て適切である場合は、true。それ以外の場合は、false。
     */
    private function isAcceptablePartition(array $list): bool
    {
        return (new ListObject($list))->trueForAll(function ($partition) {
            return (is_string($partition) === true && strlen($partition) > 0);
        });
    }
}
