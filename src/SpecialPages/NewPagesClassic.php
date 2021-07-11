<?php

/**
 * Implements Special:NewPages (klassische Serie)
 *
 * We extend the class for the standard NewPages page. The actual filtering is done,
 * however, in the NewPagesNeo-class, which implements the hook
 * onSpecialNewpagesConditions.
 *
 *
 * @file
 * @ingroup SpecialPage
 */

namespace PP\SpecialPages;

use SpecialNewpages;
use IncludableSpecialPage;


/**
 * A special page that lists most linked NEO pages that do not exist.
 *
 * Unlike other classes for special pages, this class is never instantiated, 
 * but implements hooks. Because Mediawiki is crazy that way.
 *
 * @ingroup SpecialPage
 */
class NewPagesClassic extends SpecialNewpages {

	function __construct( $name = 'NewPages_(klassische Serie)' ) {
		// IncludableSpecialPage is the parent class of SpecialNewpages,
		// that is, the grandparent of the current class.
		// Calling the grandparents' constructer is pretty much awful in
		// terms of good OO practices, but we are left no choice because
		// sommeone decided to break the pattern with the SpecialNewpages
		// constructor.
		IncludableSpecialPage::__construct( $name );
	}

}