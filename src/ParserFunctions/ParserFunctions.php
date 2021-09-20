<?php 
namespace PP\ParserFunctions;

/**
 * Implements small parser functions that do not require their own class.
 */
class ParserFunctions {
    /**
    * Implements the {{#cscorenum}} parser function.
    *
    * This is a small wrapper around the {{#cscore}} and takes the same
    * arguments.
    *
    * All the function does is remove the "formatting" (points indicating
    * thousands) form the numer returned by #cscore, so that it can be used
    * as an integer argument to another function.
    */

	public static function hookCScoreNum( &$parser, $usertext, $metric = 'score' ) {
        // In ContributionScores 1.25, the parser function hook is just a function
        // in the global name space.
        // Later versions of ContributionScores properly encapsulate the hook in 
        // a class. Hence, if ContributionScores is updated, the call needs to
        // be adjusted.
		return str_replace('.', '', efContributionScores_Render($parser, $usertext, $metric));
	}
}