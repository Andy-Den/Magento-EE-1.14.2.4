<?php
/**
 * TaxType.class.php
 */

/**
 * The Type of the tax.
 *
 * @author    Avalara
 * @copyright � 2004 - 2011 Avalara, Inc.  All rights reserved.
 * @package   Tax
 */

class TaxType// extends Enum
{
	public static $Sales	= 'Sales';
	public static $Use		= 'Use';
	public static $ConsumerUse	= 'ConsumerUse';
    public static $Output	= 'Output';
    public static $Input	= 'Input';
    public static $Nonrecoverable	= 'Nonrecoverable';
    public static $Fee	= 'Fee';
    public static $Rental	= 'Rental';
	public static $Excise ='Excise';
	public static $LodgingTax ='LodgingTax';		//Added for 15.6.0.0
	public static $Hospitality ='Hospitality';		//Added for 15.6.0.0
	public static $Preservation ='Preservation';		//Added for 15.6.0.0
	public static $TransientRoom ='TransientRoom';		//Added for 15.6.0.0
	public static $Hotel ='Hotel';		//Added for 15.6.0.0
	public static $CountyAccommodations ='CountyAccommodations';		//Added for 15.6.0.0
	public static $Accommodations ='Accommodations';		//Added for 15.6.0.0
	public static $StateAccommodations ='StateAccommodations';		//Added for 15.6.0.0
	public static $Tourism ='Tourism';		//Added for 15.6.0.0
	public static $ConventionCenter ='ConventionCenter';		//Added for 15.6.0.0
	//public static $LodgingFee ='LodgingFee';		//Removed from Taxsvc2

	/*
    public static function Values()
	{
		return array(
			$TaxType::$Sales,
			$TaxType::$Use,
			$TaxType::$ConsumerUse
		);
	}
	
    // Unfortunate boiler plate due to polymorphism issues on static functions
    public static function Validate($value) { self::__Validate($value,self::Values(),__CLASS__); }
	
	*/
	
}

	

?>