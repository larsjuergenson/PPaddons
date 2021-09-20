<?php

/**
 * @codeCoverageIgnore
 */
class PPaddons {

	/**
	 * Registers the parser functions.
	 */
	public static function onParserFirstCallInit( Parser $parser ) {

		$parser->setFunctionHook( 
			'cscorenum', 
			'PP\ParserFunctions\ParserFunctions::hookCScoreNum' 
		);
   }
}