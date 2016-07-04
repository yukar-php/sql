<?php
namespace Yukar\Sql\Builder\Functions;

use Yukar\Sql\Interfaces\Builder\Functions\IFunction;

/**
 * テーブルや列の別名を表します。
 *
 * @author hiroki sugawara
 */
class Alias implements IFunction
{
    private $origin_name = null;
    private $alias_name = null;

    /**
     * Alias クラスの新しいインスタンスを初期化します。
     *
     * @param string $origin_name
     * @param string $alias_name
     */
    public function __construct(string $origin_name, string $alias_name)
    {
        $this->setOriginName($origin_name);
        $this->setAliasName($alias_name);
    }

    /**
     * SQLの関数や計算式の書式を取得します。
     *
     * @return string SQLの関数や計算式の書式
     */
    public function getFunctionFormat(): string
    {
        return '%s AS %s';
    }

    /**
     * テーブルや列の本来の名前を取得します。
     *
     * @return string テーブルや列の本来の名前
     */
    public function getOriginName()
    {
        return $this->origin_name;
    }

    /**
     * テーブルや列の本来の名前を設定します。
     *
     * @param string $origin_name テーブルや列の本来の名前
     *
     * @throws \InvalidArgumentException 引数 origin_name に空文字列を渡した場合
     */
    public function setOriginName(string $origin_name)
    {
        if (empty($origin_name) === true) {
            throw new \InvalidArgumentException();
        }

        $this->origin_name = $origin_name;
    }

    /**
     * テーブルや列の別名を取得します。
     *
     * @return string テーブルや列の別名
     */
    public function getAliasName()
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
     * SQLの関数や計算式を文字列として取得します。
     *
     * @return string SQLの関数や計算式
     */
    public function __toString(): string
    {
        return sprintf($this->getFunctionFormat(), $this->getOriginName(), $this->getAliasName());
    }
}
