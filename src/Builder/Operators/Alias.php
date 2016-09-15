<?php
namespace Yukar\Sql\Builder\Operators;

use Yukar\Sql\Interfaces\Builder\Objects\IDataSource;
use Yukar\Sql\Interfaces\Builder\Objects\ITable;
use Yukar\Sql\Interfaces\Builder\Operators\IOperator;
use Yukar\Sql\Interfaces\Builder\Statements\ISelectQuery;

/**
 * テーブルや列の別名または名前をつけた問い合わせクエリを表します。
 *
 * @author hiroki sugawara
 */
class Alias implements IOperator, IDataSource
{
    private $origin_name;
    private $alias_name = '';

    /**
     * Alias クラスの新しいインスタンスを初期化します。
     *
     * @param mixed $origin_name テーブルや列の本来の名前
     * @param string $alias_name テーブルや列の別名
     */
    public function __construct($origin_name, string $alias_name)
    {
        $this->setOriginName($origin_name);
        $this->setAliasName($alias_name);
    }

    /**
     * SQLの演算子の書式を取得します。
     *
     * @return string SQLの演算子の書式
     */
    public function getOperatorFormat(): string
    {
        return '%s AS %s';
    }

    /**
     * テーブルや列の本来の名前または名前を付けた問い合わせクエリを取得します。
     *
     * @return string テーブルや列の本来の名前
     */
    public function getOriginName(): string
    {
        return sprintf(($this->origin_name instanceof ISelectQuery === true) ? '(%s)' : '%s', $this->origin_name);
    }

    /**
     * テーブルや列の本来の名前または名前を付けた問い合わせクエリを設定します。
     *
     * @param mixed $origin_name テーブルや列の本来の名前または名前をつける問い合わせクエリ
     *
     * @throws \InvalidArgumentException 引数 origin_name に空文字列を渡した場合
     */
    public function setOriginName($origin_name)
    {
        if ($this->isAcceptableAlias($origin_name) === false) {
            throw new \InvalidArgumentException();
        }

        $this->origin_name = $origin_name;
    }

    /**
     * テーブルや列の別名を取得します。
     *
     * @return string テーブルや列の別名
     */
    public function getAliasName(): string
    {
        return $this->alias_name;
    }

    /**
     * テーブルや列の別名を設定します。
     *
     * @param string $alias_name テーブルや列の別名
     *
     * @throws \InvalidArgumentException 引数 alias_name に空文字列を渡した場合
     */
    public function setAliasName(string $alias_name)
    {
        if (empty($alias_name) === true) {
            throw new \InvalidArgumentException();
        }

        $this->alias_name = $alias_name;
    }

    /**
     * SQLの演算子を文字列として取得します。
     *
     * @return string SQLの演算子
     */
    public function __toString(): string
    {
        return sprintf($this->getOperatorFormat(), $this->getOriginName(), $this->getAliasName());
    }

    /**
     * AS 演算子の対象となる表や列の名前または問い合わせクエリにすることができる値かどうかを判別します。
     *
     * @param mixed $origin_name 判別対象となる値
     *
     * @return bool 表や列の名前または問い合わせクエリにすることができる値の場合は、true。それ以外の場合は、false。
     */
    private function isAcceptableAlias($origin_name): bool
    {
        return ((is_string($origin_name) === true && empty($origin_name) === false) ||
            ($origin_name instanceof ITable === true || $origin_name instanceof ISelectQuery === true));
    }
}
