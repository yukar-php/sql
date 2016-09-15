<?php
namespace Yukar\Sql\Interfaces\Builder\Objects;

/**
 * テーブルの任意の列のリストを表すインターフェイスです。
 *
 * @author hiroki sugawara
 */
interface IColumns
{
    /**
     * テーブルの任意の列のリストを取得します。
     *
     * @return array テーブルの任意の列のリスト
     */
    public function getColumns(): array;

    /**
     * テーブルの任意の列のリストを設定します。
     *
     * @param array $columns テーブルの任意の列のリスト
     *
     * @throws \InvalidArgumentException 引数 $columns が空配列の場合
     * @throws \DomainException          引数 $columns の配列の要素に一つ以上の許容できない型がある場合
     */
    public function setColumns(array $columns);

    /**
     * テーブルの任意の列のリストが文字列の項目だけを持つかどうかを判別します。
     *
     * @return bool テーブルの任意の列のリストが文字列の項目だけの場合は true。それ以外の場合は false。
     */
    public function hasOnlyStringItems(): bool;

    /**
     * テーブルの任意の列のリストがOrderByに使用可能な項目だけを持つかどうかを判別します。
     *
     * @return bool テーブルの任意の列のリストがOrderByに使用可能な項目だけの場合は true。それ以外の場合は false。
     */
    public function hasOnlyOrderByItems(): bool;

    /**
     * テーブルの列のリストを文字列で取得します。
     *
     * @return string テーブルの対象の列のリストを「,」で連結した文字列。<br/>
     *                対象の列が指定されていない場合は「*」。
     */
    public function __toString(): string;
}
