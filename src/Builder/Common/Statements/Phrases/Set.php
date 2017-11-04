<?php
namespace Yukar\Sql\Builder\Common\Statements\Phrases;

use Yukar\Sql\Builder\Common\Objects\SetValuesHash;
use Yukar\Sql\Interfaces\Builder\Common\Statements\IPhrases;

/**
 * SQLクエリの SET 句を表します。
 *
 * @package Yukar\Sql\Builder\Common\Statements\Phrases
 * @author hiroki sugawara
 */
class Set implements IPhrases
{
    private $dictionary;

    /**
     * Set クラスの新しいインスタンスを初期化します。
     *
     * @param SetValuesHash $dictionary SET 句の対象となる表の列名とその値の辞書
     */
    public function __construct(SetValuesHash $dictionary)
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
     * @return SetValuesHash SET 句の対象となる表の列名とその値の辞書
     */
    public function getDictionary(): SetValuesHash
    {
        return $this->dictionary;
    }

    /**
     * SET 句の対象となる表の列名とその値の辞書を設定します。
     *
     * @param SetValuesHash $dictionary SET 句の対象となる表の列名とその値の辞書
     */
    public function setDictionary(SetValuesHash $dictionary): void
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
        return sprintf($this->getPhraseString(), $this->getDictionary());
    }
}
