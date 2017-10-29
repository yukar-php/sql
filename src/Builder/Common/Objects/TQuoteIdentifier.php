<?php
namespace Yukar\Sql\Builder\Objects;

/**
 * SQL識別子を区切り識別子を引用する機能を提供するトレイトです。
 *
 * @author hiroki sugawara
 */
trait TQuoteIdentifier
{
    /** @var DelimitedIdentifier|null */
    private $delimited_identifier;

    /**
     * SQL識別子を区切り識別子で引用する機能を持つクラスのインスタンスオブジェクトを取得します。
     *
     * @return null|DelimitedIdentifier SQL識別子を区切り識別子で引用する機能を持つクラスのインスタンスオブジェクト
     */
    public function getDelimitedIdentifier()
    {
        return $this->delimited_identifier;
    }

    /**
     * SQL識別子を区切り識別子で引用する機能を持つクラスのインスタンスオブジェクトを設定します。
     *
     * @param DelimitedIdentifier $identifier SQL識別子を区切り識別子で引用する機能を持つクラスのインスタンスオブジェクト
     */
    public function setDelimitedIdentifier(DelimitedIdentifier $identifier)
    {
        $this->delimited_identifier = $identifier;
    }

    /**
     * SQL識別子を区切り識別子で引用する機能の設定が初期化済みかどうかを判別します。
     *
     * @return bool SQL識別子を区切り識別子で引用する機能の設定が初期化済みの場合は true。それ以外の場合は false。
     */
    protected function isPreparedQuote(): bool
    {
        return ($this->getDelimitedIdentifier() instanceof DelimitedIdentifier === true);
    }

    /**
     * 表や列の名前を区切り識別子を引用した文字列として取得します。
     *
     * @param string $text 区切り識別子で引用する表や列の名前
     *
     * @return string 区切り識別子で引用した表や列の名前の文字列
     */
    protected function getQuotedString(string $text): string
    {
        return ($this->isPreparedQuote() === false) ? $text : $this->getDelimitedIdentifier()->getQuotedString($text);
    }

    /**
     * 表や列の名前のリストを区切り識別子を引用した文字列のリストとして取得します。
     *
     * @param array $list 区切り識別子で引用する表や列の名前のリスト
     *
     * @return array 区切り識別子を引用した表や列の名前文字列ののリスト
     */
    protected function getQuotedList(array $list): array
    {
        return ($this->isPreparedQuote() === false) ? $list : $this->getDelimitedIdentifier()->getQuotedList($list);
    }

    /**
     * スキーマと表や表と列など二種類以上を連結した名前のリストを区切り識別子を引用した文字列のリストとして取得します。
     *
     * @param array $list               区切り識別子で引用する表や列の名前のリスト
     * @param string $line_delimiter    リストの要素の内容を区切る文字または文字列
     *
     * @return array 区切り識別子を引用した表や列の名前文字列ののリスト
     */
    protected function getMultiQuotedList(array $list, string $line_delimiter = '.'): array
    {
        return ($this->isPreparedQuote() === false) ? $list :
            $this->getDelimitedIdentifier()->getMultiQuotedList($list, $line_delimiter);
    }
}
