<?php
namespace Phlu\Neos\NodeTypes\NodeTypePostprocessor;

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
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\ContentRepository\Domain\Model\NodeType;
use Neos\ContentRepository\NodeTypePostprocessor\NodeTypePostprocessorInterface;
use Neos\Flow\Security\Context;

/**
 * This Processor updates the PluginViews NodeType with the existing
 * Plugins and it's corresponding available Views
 */
class AdminNodeTypeUiPostprocessor implements NodeTypePostprocessorInterface
{
    /**
     * @var ConfigurationManager
     * @Flow\Inject
     */
    protected $configurationManager;


    /**
     * @Flow\Inject
     * @var Context
     */
    protected $securityContext;


    /**
     * Returns the processed Configuration
     *
     * @param \Neos\ContentRepository\Domain\Model\NodeType $nodeType (uninitialized) The node type to process
     * @param array $configuration input configuration
     * @param array $options The processor options
     * @return void
     */
    public function process(NodeType $nodeType, array &$configuration, array $options)
    {

        if ($this->securityContext->canBeInitialized()) {
            if ($this->securityContext->hasRole('Neos.Neos:Administrator')) {
                $configuration['properties']['_nodeType']['ui']['inspector']['group'] = 'type';
            }
        }

    }
}
