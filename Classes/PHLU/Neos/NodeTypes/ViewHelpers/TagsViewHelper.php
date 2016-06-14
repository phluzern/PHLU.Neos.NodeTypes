<?php
namespace PHLU\Neos\NodeTypes\ViewHelpers;

/*
 * This file is part of the TYPO3.Neos package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use PHLU\Neos\NodeTypes\DataSource\TagsDataSource;
use TYPO3\TYPO3CR\Domain\Model\Node;

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
     * @param menuItems $menuItems
     * @param as $as
     * @return array
     */
    public function render($node,$menuItems,$as)
    {

        $tagsDataSource = $this->tagsDataSource->getData($node,array());
        $tags = array();

        foreach ($menuItems as $item) {

            if (is_array($item['node']->getProperty('tags'))) {
                foreach ($item['node']->getProperty('tags') as $tag) {
                    if (isset($tags[$tag]) === false && isset($tagsDataSource[$tag])) {
                        $tags[$tag] = $tagsDataSource[$tag];
                    }
                }
            }


        }

        if ($this->templateVariableContainer->exists($as)) {
            $this->templateVariableContainer->remove($as);
        }

        $this->templateVariableContainer->add($as,$tags);

        
        return $this->renderChildren();



    }

}