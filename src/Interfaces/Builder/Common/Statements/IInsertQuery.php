<?php
namespace Yukar\Sql\Interfaces\Builder\Common\Statements;

use Yukar\Sql\Builder\Common\Statements\Phrases\Values;

/**
 * 削除の問い合わせクエリを定義するインターフェイス。
 *
 * @author hiroki sugawara
 */
interface IInsertQuery
{
    /**
     * 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリを取得します。
     *
     * @return Values|ISelectQuery 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリ
     */
    public function getValues();

    /**
     * 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリを設定します。
     *
     * @param Values|ISelectQuery $values 挿入の問い合わせクエリの対象となる値のリストまたはサブクエリ
     *
     * @throws \InvalidArgumentException 引数 values に許容できない型の値が渡された場合
     */
    public function setValues($values);
}
