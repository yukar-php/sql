<?php
namespace Yukar\Sql\Builder\Common\Statements\Phrases;

use Yukar\Linq\Collections\ListObject;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;

/**
 * SQLクエリの VALUES 句を表します。
 *
 * @author hiroki sugawara
 */
class Values implements IPhrases
{
    private $values = [];

    /**
     * Values クラスの新しいインスタンスを初期化します。
     *
     * @param array|\Traversable $values SQLクエリの対象となる値のリスト
     */
    public function __construct($values)
    {
        $this->setValues($values);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'VALUES (%s)';
    }

    /**
     * SQLクエリの対象となる値のリストを取得します。
     *
     * @return array SQLクエリの対象となる値のリスト
     */
    public function getValues(): array
    {
        return $this->toArray($this->values);
    }

    /**
     * SQLクエリの対象となる値のリストを設定します。
     *
     * @param array|\Traversable $values SQLクエリの対象となる値のリスト
     *
     * @throws \InvalidArgumentException 引数 values に許容できない型の値を渡した場合
     */
    public function setValues($values)
    {
        if ($this->isAcceptableValues($values) === false) {
            throw new \InvalidArgumentException();
        }

        $this->values = $values;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return sprintf($this->getPhraseString(), $this->getValuesString());
    }

    /**
     * VALUES 句の内容を文字列にしたものを取得します。
     *
     * @return string VALUES 句の内容
     */
    private function getValuesString(): string
    {
        $selector = function ($v) {
            $converter = function ($val) {
                return is_numeric($val) ? $val : sprintf("'%s'", $val);
            };

            return implode(', ', $this->getListObject($this->toArray($v))->select($converter)->toArray());
        };

        return implode('), (', $this->getListObject($this->getValues())->select($selector)->toArray());
    }

    /**
     * クラス ListObject のオブジェクトインスタンスを取得します。
     *
     * @param array|\Traversable $values 初期化時に保持するリスト
     *
     * @return ListObject クラス ListObject のオブジェクトインスタンス
     */
    private function getListObject($values)
    {
        return new ListObject($this->toArray($values));
    }

    /**
     * リストを配列に変換したものを取得します。配列の場合は、その配列をそのまま返します。
     *
     * @param array|\Traversable $values 変換するリスト
     *
     * @return array リストを変換した配列
     */
    private function toArray($values): array
    {
        return ($values instanceof \Traversable) ? iterator_to_array($values) : $values;
    }

    /**
     * クエリの対象となる値のリストに含めることができる値かどうかを判別します。
     *
     * @param mixed $values 判別対象となる値
     *
     * @return bool クエリの対象となる値のリストに含めることができる場合は true。それ以外の場合は false。
     */
    private function isAcceptableValues($values): bool
    {
        if ($this->isIterable($values) === true) {
            $list = $this->getListObject($values);
            $all_matched = function ($v) {
                return (is_string($v) === true || $this->isIterable($v) === true);
            };

            return ($list->getSize() > 0 && $list->trueForAll($all_matched));
        }

        return false;
    }

    /**
     * 反復可能な型の値であるかどうかを判別します。
     *
     * @param mixed $value 判別対象となる値
     *
     * @return bool 反復可能な型の値である場合は true。それ以外の場合は false。
     */
    private function isIterable($value): bool
    {
        return (is_array($value) === true || $value instanceof \Traversable === true);
    }
}
