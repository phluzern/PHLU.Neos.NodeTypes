<?php
namespace PHLU\Neos\NodeTypes\Command;

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
use TYPO3\Flow\Cli\CommandController;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use TYPO3\Flow\Package\PackageManager;


/**
 * The Site Command Controller
 *
 * @Flow\Scope("singleton")
 */
class NodeDefinitionsCommandController extends CommandController
{

    /**
     * @Flow\Inject
     * @var PackageManager
     */
    protected $packageManager;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Configuration\ConfigurationManager
     */
    protected $configurationManager;


    /**
     * @Flow\Inject
     * @var NodeTypeManager
     */
    protected $nodeTypeManager;

     /**
     * Exports complete node definitions as an excel sheet
     *
     *
     * @param string $filename relative path and filename to the EXCEL export file containing the node types definitions
     * @return void
     */
    public function exportCommand($filename)
    {

        $c = 0;
      $data[$c] = array(

          'nodeType' => array(),
          'properties' => array(),
          'superTypes' => array(),
          'childNodes' => array(),
          'abstract' => array(),
          'package' => array(),
          'filename' => array(),
          'location' => array()

      );



      $c++;

      foreach ($this->configurationManager->getConfiguration('NodeTypes') as $nodeTypeName => $nodeTypeDefinition) {


          $nodeType = $this->nodeTypeManager->getNodeType($nodeTypeName);


              $file = system("find " . $this->packageManager->getPackagesBasePath() . " -type f -name *.yaml -exec grep -l \"^[^ ]" . $nodeTypeName . "\" {} +");

              if (substr_count($file, $this->packageManager->getPackagesBasePath() . "/") === 1) {


                  $data[$c] = array(
                    'nodeType' => '',
                    'properties' => '',
                    'superTypes' => '',
                    'childNodes' => '',
                    'abstract' => '',
                    'package' => '',
                    'filename' => '',
                    'location' => ''
                  );



                  list($location,$package, $nopath, $configurationfile) = explode("/", str_replace($this->packageManager->getPackagesBasePath() . "/", '', $file));



                  $data[$c]['nodeType'] = $nodeTypeName;
                  $data[$c]['location'] = $location;
                  $data[$c]['properties'] = '';

                  foreach ($nodeType->getProperties() as $property => $value) {
                      if ($data[$c]['properties'] !== '') $data[$c]['properties'] .= ', ';
                      $data[$c]['properties'] .= $property;
                  }

                  $data[$c]['childNodes'] = '';
                  if (isset($nodeTypeDefinition['childNodes'])) {
                      foreach ($nodeTypeDefinition['childNodes'] as $path => $value) {
                          if ($data[$c]['childNodes'] !== '') $data[$c]['childNodes'] .= ', ';
                          $data[$c]['childNodes'] .= $path . ": " . $value['type'];
                      }
                  }

                  $data[$c]['superTypes'] = '';
                  if (isset($nodeTypeDefinition['superTypes'])) {
                      foreach ($nodeTypeDefinition['superTypes'] as $superType => $value) {
                          if ($data[$c]['superTypes'] !== '') $data[$c]['superTypes'] .= ', ';
                          if ($value === TRUE) $data[$c]['superTypes'] .= $superType;
                      }
                  }


                  $data[$c]['abstract'] = isset($nodeTypeDefinition['abstract']) && $nodeTypeDefinition['abstract'] ? 1 : 0;

                  $data[$c]['package'] = $package;
                  $data[$c]['filename'] = $configurationfile;


                  $c++;


              }

      }

        $csvdata = '';
        
        

        foreach ($data as $key => $val) {


            foreach ($val as $k => $v) {

                if ($key === 0) {
                    $csvdata .= $k.";";
                } else {
                    $csvdata .= $v.";";
                }

            }


            $csvdata .= "\n";
        }


        $fp = \fopen(system("pwd")."/$filename", 'w');
        \fwrite($fp, $csvdata);
        \fclose($fp);


        $this->outputLine('Export of "%s" finished.', array(system("pwd")."/$filename"));
        
    }




}
