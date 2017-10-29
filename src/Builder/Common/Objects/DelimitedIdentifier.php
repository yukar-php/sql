<?php
namespace Yukar\Sql\Builder\Common\Objects;

use Yukar\Linq\Collections\ListObject;

/**
 * SQL の区切り識別子を表します。
 *
 * @package Yukar\Sql\Builder\Common\Objects
 * @author hiroki sugawara
 */
class DelimitedIdentifier
{
    /** 区切り識別子を使用しないことを示す定数 */
    public const NONE_QUOTES_TYPE = -1;
    /** ANSI の標準の区切り識別子を使用することを示す定数 */
    public const ANSI_QUOTES_TYPE = 0;
    /** MYSQL の区切り識別子を使用することを示す定数 */
    public const MYSQL_QUOTES_TYPE = 1;
    /** SQL Server の区切り識別子を使用することを示す定数 */
    public const SQL_SERVER_QUOTES_TYPE = 2;

    private const QUOTES_SET = [
        self::NONE_QUOTES_TYPE => '%s',
        self::ANSI_QUOTES_TYPE => '"%s"',
        self::MYSQL_QUOTES_TYPE => '`%s`',
        self::SQL_SERVER_QUOTES_TYPE => '[%s]',
    ];

    /** @var DelimitedIdentifier */
    private static $instance;
    /** @var int */
    private $quote_type = self::NONE_QUOTES_TYPE;

    /**
     * DelimitedIdentifier クラスの新しいインスタンスを初期化します。
     */
    private function __construct()
    {
    }

    /**
     * 区切り識別子の使用設定を初期化します。
     *
     * @param int $quote_type 区切り識別子の種類
     */
    public static function init(int $quote_type = self::ANSI_QUOTES_TYPE): void
    {
        if (self::$instance instanceof DelimitedIdentifier === false) {
            self::$instance = new DelimitedIdentifier();
        }

        self::$instance->setQuoteType($quote_type);
    }

    /**
     * 区切り識別子の使用設定が初期化済みかどうかを判別します。
     *
     * @return bool 区切り識別子の使用設定が初期化済みの場合は true。それ以外の場合は false。
     */
    public static function isAlreadyInit(): bool
    {
        return (isset(self::$instance) === true && self::$instance instanceof DelimitedIdentifier === true);
    }

    /**
     * 区切り識別子のインスタンスオブジェクトを取得します。
     *
     * @return DelimitedIdentifier 区切り識別子のインスタンスオブジェクト
     */
    public static function get(): DelimitedIdentifier
    {
        if (self::isAlreadyInit() === false) {
            throw new \BadFunctionCallException();
        }

        return self::$instance;
    }

    /**
     * 区切り識別子で引用した書式に変換した対象の文字列を取得します。
     *
     * @param string $text 区切り識別子で引用した書式に変換する文字列
     *
     * @return string 区切り識別子で引用した書式に変換した文字列
     */
    public static function quoted(string $text): string
    {
        return (self::isAlreadyInit() === false) ? $text : self::get()->getQuotedString($text);
    }

    /**
     * 区切り識別子で引用した書式に変換した対象の文字列のリストを取得します。
     *
     * @param array $list 区切り識別子で引用した書式に変換する文字列のリスト
     *
     * @return array 区切り識別子で引用した書式に変換した文字列のリスト
     */
    public static function quotedRange(array $list): array
    {
        return (self::isAlreadyInit() === false) ? $list : self::get()->getQuotedList($list);
    }

    /**
     * 使用する区切り識別子の種類を取得します。
     *
     * @return int 使用する区切り識別子の種類
     */
    public function getQuoteType(): int
    {
        return $this->quote_type;
    }

    /**
     * 使用する区切り識別子の種類を設定します。
     *
     * @param int $quote_type 使用する区切り識別子の種類
     */
    public function setQuoteType(int $quote_type): void
    {
        $this->quote_type = array_key_exists($quote_type, self::QUOTES_SET) ? $quote_type : 0;
    }

    /**
     * 区切り識別子で引用した書式に変換した対象の文字列を取得します。
     *
     * @param string $text 区切り識別子で引用した書式に変換する文字列
     *
     * @return string 区切り識別子で引用した書式に変換した文字列
     */
    public function getQuotedString(string $text): string
    {
        return (strlen($text) === 0) ? $text : sprintf(self::QUOTES_SET[$this->getQuoteType()], $text);
    }

    /**
     * 区切り識別子で引用した書式に変換した対象の文字列のリストを取得します。
     *
     * @param array $list 区切り識別子で引用した書式に変換する文字列のリスト
     *
     * @return array 区切り識別子で引用した書式に変換した文字列のリスト
     */
    public function getQuotedList(array $list): array
    {
        return (new ListObject($list))->select(function ($v) {
            return (is_string($v) === true) ? $this->getQuotedString($v) : $v;
        })->toArray();
    }

    /**
     * インライン区切り文字の前後を区切り識別子で引用した書式に変換した文字列のリストを取得します。
     *
     * @param array $list               区切り識別子で引用した書式に変換する文字列のリスト
     * @param string $line_delimiter    リストの要素の内容を区切る文字または文字列
     *
     * @return array インライン区切り文字の前後を区切り識別子で引用した書式に変換した文字列
     */
    public function getMultiQuotedList(array $list, string $line_delimiter = '.'): array
    {
        return (new ListObject($list))->select(function ($v) use ($line_delimiter) {
            return (is_string($v) === false) ? $v :
                implode($line_delimiter, $this->getQuotedList(explode($line_delimiter, $v)));
        })->toArray();
    }
}
