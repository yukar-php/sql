<?php
namespace Yukar\Sql\Builder\Statements\Phrases;

use Yukar\Linq\Collections\DictionaryObject;
use Yukar\Linq\Collections\KeyValuePair;
use Yukar\Sql\Interfaces\Builder\Statements\IPhrases;

/**
 * SQLクエリの SET 句を表します。
 *
 * @author hiroki sugawara
 */
class Set implements IPhrases
{
    private $dictionary;

    /**
     * Set クラスの新しいインスタンスを初期化します。
     *
     * @param DictionaryObject $dictionary SET 句の対象となる表の列名とその値の辞書
     */
    public function __construct(DictionaryObject $dictionary)
    {
        $this->setDictionary($dictionary);
    }

    /**
     * SQLクエリの句の書式文字列を取得します。
     *
     * @return string SQLクエリの句の書式
     */
    public function getPhraseString(): string
    {
        return 'SET %s';
    }

    /**
     * SET 句の対象となる表の列名とその値の辞書を取得します。
     *
     * @return DictionaryObject SET 句の対象となる表の列名とその値の辞書
     */
    public function getDictionary(): DictionaryObject
    {
        return $this->dictionary;
    }

    /**
     * SET 句の対象となる表の列名とその値の辞書を設定します。
     *
     * @param DictionaryObject $dictionary SET 句の対象となる表の列名とその値の辞書
     */
    public function setDictionary(DictionaryObject $dictionary)
    {
        if ($dictionary->getSize() === 0) {
            throw new \InvalidArgumentException();
        }

        $this->dictionary = $dictionary;
    }

    /**
     * SQLクエリの句を文字列として取得します。
     *
     * @return string SQLクエリの句
     */
    public function __toString(): string
    {
        return sprintf($this->getPhraseString(), $this->getDictionaryString());
    }

    /**
     * SET 句の対象となる表の列名とその値の辞書の内容を文字列として取得します。
     *
     * @return string SET 句の対象となる表の列名とその値の辞書の内容
     */
    private function getDictionaryString(): string
    {
        $selector = function (KeyValuePair $v) {
            $converter = function ($val) {
                return is_numeric($val) ? $val : sprintf("'%s'", $val);
            };

            return sprintf('%s = %s', $v->getKey(), $converter($v->getValue()));
        };

        return implode(', ', $this->getDictionary()->select($selector)->toArray());
    }
}
