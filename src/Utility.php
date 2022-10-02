<?php
namespace OGBitBlt\Electrum;
/**
 * @package OGBitBlt\Electrum
 * @author Anthony Davis <anthonyjohndavis@outlook.com>
 * @version $ $ 
 * @access public  
 * Contains generic functions that are available to consumers of 
 * the library but are not used within the library.
 */
class Utility 
{
	/**
	 * Used in convert functions as a flag to 
	 * specify that the conversion source is 
	 * bitcoin.
	 */
    const CONVERT_FROM_BITCOIN = 0;
	
	/**
	 * Used in convert functions as a flat to 
	 * specify that the conversion source is 
	 * satoshi
	 */
	const CONVERT_FROM_SATOSHI = 1;

    /**
	 * Conversation rate for converting Bitcoin to 
	 * Satoshi and vice versa
	 */
	const BITCOIN_TO_SATOSHI_CONVERSION_RATE = 0x5f5e100;

	/**
	 * Convert a bitcoin amount into a satoshi amount
	 * @param	float	$from	The Bitcoin amount to convert
	 * @return	float			The Satoshi amount converted
	 */
    public static function ConvertBitcoinToSatoshi(float $from) : float
    {
        return self::ConvertFrom($from, self::CONVERT_FROM_BITCOIN);
    }

	/**
	 * Convert a satoshi amount into a bitcoin amount
	 * @param	float	$from	The Satoshi amount to convert
	 * @return 	float			The Bitcoin amount converted
	 */
    public static function ConvertSatoshiToBitcoin(float $from) : float 
    {
        return self::ConvertFrom($from, self::CONVERT_FROM_SATOSHI);
    }

	/**
	 * Helper function called by Convert functions
	 * @param	float	$from 	holds the amount to convert from
	 * @param 	int 	$source value to determins what source we are 
	 * 							converting from
	 * @return 	float 			the result of the value after being converted.
	 */
	public static function ConvertFrom(float $from, int $source) : float
	{
		$to = 0.0;
		if($source == self::CONVERT_FROM_BITCOIN) {
			$to = ($from * self::BITCOIN_TO_SATOSHI_CONVERSION_RATE);
		} else if ($source == self::CONVERT_FROM_SATOSHI) {
			$to = ($from / self::BITCOIN_TO_SATOSHI_CONVERSION_RATE);
		}
		return $to;
	}
}
?>