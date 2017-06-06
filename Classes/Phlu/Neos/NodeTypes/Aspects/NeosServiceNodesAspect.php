<?php

namespace Phlu\Neos\NodeTypes\Aspects;

use Neos\Flow\Annotations as Flow;
use Neos\ContentRepository\Domain\Repository\NodeDataRepository;
use Neos\ContentRepository\Domain\Service\NodeTypeManager;
use Neos\Flow\Http\Exception;
use Neos\Flow\Mvc\FlashMessageContainer;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Neos\Utility\NodeUriPathSegmentGenerator;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Error\Messages\Message;

/**
 * @Flow\Aspect
 */
class NeosServiceNodesAspect
{

    /**
     * @Flow\Inject
     * @var NodeDataRepository
     */
    protected $nodeDataRepository;

    /**
     * @Flow\Inject
     * @var NodeTypeManager
     */
    protected $nodeTypeManager;

    /**
     * @Flow\Inject
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Flow\Inject
     * @var NodeUriPathSegmentGenerator
     */
    protected $nodeUriPathSegmentGenerator;

    /**
     * @Flow\Inject
     * @var FlashMessageContainer
     */
    protected $flashMessageContainer;


    /**
     * @Flow\After("within(Neos\ContentRepository\Domain\Repository\NodeDataRepository) && method(public .+->(add|update)())")
     * @return void
     */
    public function updateShortUrl(JoinPointInterface $joinPoint)
    {

        if ($joinPoint->getMethodArgument('object')->hasProperty('phluNeosNodeTypesShorturl')) {

            /* @var \Neos\ContentRepository\Domain\Model\Node $object */
            $object = $joinPoint->getMethodArgument('object');
            $shorturl = $joinPoint->getMethodArgument('object')->getProperty('phluNeosNodeTypesShorturl');

            if (strlen($shorturl) > 0) {

                $nodes = $this->nodeDataRepository->findByProperties($shorturl, 'Phlu.Neos.NodeTypes:Shorturl', $object->getWorkspace(), $object->getDimensions());
                foreach ($nodes as $node) {
                    if ($node->hasProperty('phluNeosNodeTypesShorturl') && $node->getProperty('phluNeosNodeTypesShorturl') == $shorturl) {
                        $object->setProperty('phluNeosNodeTypesShorturl',time().'-'.$shorturl);
                        return null;
                    }
                }

            }


        }


    }



}
