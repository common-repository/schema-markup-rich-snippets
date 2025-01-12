<?php
/**
 * The String helpers.
 *
 * @since      1.0.0
 * @package    MyThemeShop
 * @subpackage MyThemeShop\Helpers
 * @author     MyThemeShop <admin@mythemeshop.com>
 */

namespace MyThemeShop\Helpers;

/**
 * Str class.
 */
class Str {

	/**
	 * Validates whether the passed variable is a non-empty string.
	 *
	 * @param mixed $variable The variable to validate.
	 *
	 * @return bool Whether or not the passed value is a non-empty string.
	 */
	public static function is_non_empty( $variable ) {
		return is_string( $variable ) && '' !== $variable;
	}

	/**
	 * Check if the string contains the given value.
	 *
	 * @param string $needle   The sub-string to search for.
	 * @param string $haystack The string to search.
	 *
	 * @return bool
	 */
	public static function contains( $needle, $haystack ) {
		return strpos( $haystack, $needle ) !== false;
	}

	/**
	 * Check if the string begins with the given value.
	 *
	 * @param string $needle   The sub-string to search for.
	 * @param string $haystack The string to search.
	 *
	 * @return bool
	 */
	public static function starts_with( $needle, $haystack ) {
		return '' === $needle || substr( $haystack, 0, strlen( $needle ) ) === (string) $needle;
	}

	/**
	 * Check if the string end with the given value.
	 *
	 * @param string $needle   The sub-string to search for.
	 * @param string $haystack The string to search.
	 *
	 * @return bool
	 */
	public static function ends_with( $needle, $haystack ) {
		return '' === $needle || substr( $haystack, -strlen( $needle ) ) === (string) $needle;
	}

	/**
	 * Check the string for desired comparison.
	 *
	 * @param string $needle     The sub-string to search for.
	 * @param string $haystack   The string to search.
	 * @param string $comparison The type of comparison.
	 *
	 * @return bool
	 */
	public static function comparison( $needle, $haystack, $comparison = '' ) {

		$hash = [
			'regex'    => 'preg_match',
			'end'      => [ __CLASS__, 'ends_with' ],
			'start'    => [ __CLASS__, 'starts_with' ],
			'contains' => [ __CLASS__, 'contains' ],
		];

		if ( $comparison && isset( $hash[ $comparison ] ) ) {
			return call_user_func( $hash[ $comparison ], $needle, $haystack );
		}

		// Exact.
		return $needle === $haystack;
	}

	/**
	 * Convert string to array with defined seprator.
	 *
	 * @param string $str String to convert.
	 * @param string $sep Seprator.
	 *
	 * @return bool|array
	 */
	public static function to_arr( $str, $sep = ',' ) {
		$parts = explode( $sep, trim( $str ) );

		return empty( $parts ) ? false : $parts;
	}

	/**
	 * Convert string to array, weed out empty elements and whitespaces.
	 *
	 * @param string $str         User-defined list.
	 * @param string $sep_pattern Separator pattern for regex.
	 *
	 * @return array
	 */
	public static function to_arr_no_empty( $str, $sep_pattern = '\r\n|[\r\n]' ) {
		$array = empty( $str ) ? [] : preg_split( '/' . $sep_pattern . '/', $str, -1, PREG_SPLIT_NO_EMPTY );
		$array = array_filter( array_map( 'trim', $array ) );

		return $array;
	}

	/**
	 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
	 *
	 * @param string $size The size.
	 *
	 * @return int
	 */
	public static function let_to_num( $size ) {
		$char = substr( $size, -1 );
		$ret  = substr( $size, 0, -1 );

		// @codingStandardsIgnoreStart
		switch ( strtoupper( $char ) ) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		// @codingStandardsIgnoreEnd

		return $ret;
	}

	/**
	 * Convert a number to K, M, B, etc.
	 *
	 * @param int|double $number Number which to convert to pretty string.
	 *
	 * @return string
	 */
	public static function human_number( $number ) {

		if ( ! is_numeric( $number ) ) {
			return 0;
		}

		$negative = '';
		if ( abs( $number ) != $number ) {
			$negative = '-';
			$number   = abs( $number );
		}

		if ( $number < 1000 ) {
			return $negative ? -1 * $number : $number;
		}

		$unit  = intval( log( $number, 1000 ) );
		$units = [ '', 'K', 'M', 'B', 'T', 'Q' ];

		if ( array_key_exists( $unit, $units ) ) {
			return sprintf( '%s%s%s', $negative, rtrim( number_format( $number / pow( 1000, $unit ), 1 ), '.0' ), $units[ $unit ] );
		}

		return $number;
	}
}
