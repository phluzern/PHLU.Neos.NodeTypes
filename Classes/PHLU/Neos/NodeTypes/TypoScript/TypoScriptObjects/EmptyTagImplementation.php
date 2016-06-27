<?php
namespace PHLU\Neos\NodeTypes\TypoScript\TypoScriptObjects;

/*
 * This file is part of the TYPO3.TypoScript package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TypoScript\TypoScriptObjects\TagImplementation;

/**
 * A TypoScript object for tag based content
 *
 * //tsPath attributes An array with attributes for this tag (optional)
 * //tsPath content Content for the body of the tag (optional)
 * @api
 */
class EmptyTagImplementation extends TagImplementation
{


    /**
     * Return a tag
     *
     * @return mixed
     */
    public function evaluate()
    {
        
        return $this->tsValue('content');;
    }
}
