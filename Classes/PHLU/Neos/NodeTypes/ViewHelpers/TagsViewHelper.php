<?php
namespace PHLU\Neos\NodeTypes\ViewHelpers;

/*
 * This file is part of the Neos.Neos package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use PHLU\Neos\NodeTypes\DataSource\TagsDataSource;
use Neos\ContentRepository\Domain\Model\Node;

/**
 * ViewHelper to find the closest document node to a given node
 */
class TagsViewHelper extends AbstractViewHelper
{

    /**
     * Disable escaping of tag based ViewHelpers so that the rendered tag is not htmlspecialchar'd
     *
     * @var boolean
     */
    protected $escapeOutput = FALSE;


    /**
     * @Flow\Inject
     * @var TagsDataSource
     */
    protected $tagsDataSource;


    /**
     * @param Node $node
     * @param items $items
     * @param filterItems $filterItems show only items in filterItems list
     * @param as $as
     * @return array
     */
    public function render($node,$items,$filterItems,$as)
    {



        $filteredTags = array();

        if (is_array($filterItems)) {
            foreach ($filterItems as $item) {
                // proceed menu item
                if (is_array($item) && is_array($item['node']->getProperty('tags'))) {
                    foreach ($item['node']->getProperty('tags') as $tag) {
                        if (isset($tags[$tag]) === false) {
                            $filteredTags[$tag] = $tag;
                        }
                    }
                }
            }
        }


        $tags = array();

        foreach ($items as $item) {

            // proceed node item
            if ($item instanceof Node) {
                if (count($filteredTags) === 0 || in_array($item->getIdentifier(),$filteredTags))
                $tags[$item->getIdentifier()] = $item;
            }

        }

        if ($this->templateVariableContainer->exists($as)) {
            $this->templateVariableContainer->remove($as);
        }

        $this->templateVariableContainer->add($as,$tags);

        
        return $this->renderChildren();



    }

}