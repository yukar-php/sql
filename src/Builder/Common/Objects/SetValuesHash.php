<?php
namespace Yukar\Sql\Builder\Common\Objects;

use Yukar\Linq\Collections\DictionaryObject;
use Yukar\Linq\Collections\KeyValuePair;

/**
 * SQLクエリの SET 句に使用する表や列の名前とその値のペアを持つ辞書を表します。
 *
 * @author hiroki sugawara
 */
class SetValuesHash extends DictionaryObject
{
    use TQuoteIdentifier;

    /**
     * SetValuesHash クラスの新しいインスタンスを初期化します。
     *
     * @param array               $input      初期化時に保持する要素のリスト
     * @param DelimitedIdentifier $identifier SQL識別子を区切り識別子で引用する設定
     */
    public function __construct(array $input = [], DelimitedIdentifier $identifier = null)
    {
        parent::__construct($input);
        (isset($identifier) === true) && $this->setDelimitedIdentifier($identifier);
    }

    /**
     * 辞書の内容をSQLクエリの文字列として取得します。
     *
     * @return string SQLクエリの文字列に変換した辞書の内容
     */
    public function __toString(): string
    {
        $selector = function (KeyValuePair $v) {
            return $this->getQuotedString($v->getKey()) . ' = ' . $this->getQuotedValueString($v->getValue());
        };

        return implode(', ', $this->select($selector)->toArray());
    }

    /**
     * 表や列の値を区切り識別子を引用した文字列として取得します。
     *
     * @param string $value 区切り識別子で引用する表や列の値
     *
     * @return string 区切り識別子で引用した表や列の値の文字列
     */
    private function getQuotedValueString(string $value): string
    {
        return sprintf(is_numeric($value) ? "%s" : "'%s'", $value);
    }
}
