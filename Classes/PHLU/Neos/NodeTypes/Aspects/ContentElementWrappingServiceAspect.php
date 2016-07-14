<?php

namespace PHLU\Neos\NodeTypes\Aspects;


use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Aop\JoinPointInterface;


/**
 * @Flow\Aspect
 */
class ContentElementWrappingServiceAspect
{


    /**
     * @Flow\Around("method(TYPO3\Neos\Service\ContentElementWrappingService->wrapContentObject(node.nodeType.fullConfiguration.ui.selectHelper == TRUE))")
     * @return void
     */
    public function wrapContentObjectWithBackendHelperIcon(JoinPointInterface $joinPoint)
    {


        $content = $joinPoint->getAdviceChain()->proceed($joinPoint);

        $firstTagEndingPos = strpos($content,">",0)+1;
        $firstTagBefore = substr($content,0,$firstTagEndingPos);
        $firstTagAfter = substr($content,$firstTagEndingPos);

        $nodeType = $joinPoint->getMethodArgument('node')->getNodeType();

        $html = $firstTagBefore;
        $html .= '<span typeof="typo3:'.$nodeType->getName().'" tabindex="0" class="neos-contentelement backend-helper neos" title="'.$nodeType->getFullConfiguration()['ui']['label'].'" data-neos-tooltip data-placement="left"><i class=" '.$nodeType->getFullConfiguration()['ui']['icon'].'"></i></span>';
        $html .= $firstTagAfter;


        return $html;


    }

}
