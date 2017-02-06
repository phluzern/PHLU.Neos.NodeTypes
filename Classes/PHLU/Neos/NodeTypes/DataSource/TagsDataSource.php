<?php
namespace PHLU\Neos\NodeTypes\DataSource;

use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use Neos\Eel\FlowQuery\FlowQuery;

class TagsDataSource extends AbstractDataSource {


    /**
     * @var string
     */
    static protected $identifier = 'phlu-neos-nodetypes-tags';

    

    /**
     * Get data
     *
     * @param NodeInterface $node The node that is currently edited (optional)
     * @param array $arguments Additional arguments (key / value)
     * @return array JSON serializable data
     */
    public function getData(NodeInterface $node = NULL, array $arguments)
    {



        $tags = array();

        if ($node->getParent()->getNodeType()->getName() == 'PHLU.Corporate:Page.View.Default.Default') {
            $flowQuery = new FlowQuery(array($node->getParent()->getParent()));
        } else {
            $flowQuery = new FlowQuery(array($node->getParent()));
        }


        $nodes = $flowQuery->find("[instanceof PHLU.Neos.NodeTypes:Tag]")->get();

        foreach ($nodes as $tag) {

            $group = '';
            if ($tag->getParent() && $tag->getParent()->getProperty('title')) $group = $tag->getParent()->getProperty('title');
            $tags[$group][$tag->getIdentifier()] = array('value' => $tag->getIdentifier(), 'label' => $tag->getProperty('label'), 'group' => $group, 'icon' => $tag->getProperty('icon'));
        }
//
//        $tags['Fächer & Schwerpunkte']['06632130-206f-4f23-ae71-052ac125e2fa'] = array('value' => '06632130-206f-4f23-ae71-052ac125e2fa', 'label' => 'Fächer', 'group' => 'Fächer & Schwerpunkte', 'icon' => 'oi oi-media-stop ');
//        $tags['Fächer & Schwerpunkte']['ce8ba827-11c3-4394-a1ff-ceb211d52c6a'] = array('value' => 'ce8ba827-11c3-4394-a1ff-ceb211d52c6a', 'label' => 'Strategische Schwerpunkte', 'group' => 'Fächer & Schwerpunkte', 'icon' => 'strategieIco');
//        $tags['Fächer & Schwerpunkte']['824db82e-f0bf-4eba-a859-1d5b2cec11e2'] = array('value' => '824db82e-f0bf-4eba-a859-1d5b2cec11e2', 'label' => 'Forschungschwerpunkte', 'group' => 'Fächer & Schwerpunkte', 'icon' => 'fa icon-circle');
//        $tags['Fächer & Schwerpunkte']['969b9422-6f1f-4dc6-98ff-b313274b6a4b'] = array('value' => '969b9422-6f1f-4dc6-98ff-b313274b6a4b', 'label' => 'Thematische Schwerpunkte', 'group' => 'Fächer & Schwerpunkte', 'icon' => 'fa icon-star');
//


        // TODO refactoring, see https://jira.neos.io/browse/NEOS-1476
        $tagsfinal = array();
        foreach ($tags as $key => $val) {
            foreach ($val as $id => $v) {
                $tagsfinal[(string)$id] = $v;
            }
        }



        return $tagsfinal;
    }




}