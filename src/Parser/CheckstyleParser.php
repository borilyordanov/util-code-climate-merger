<?php
declare(strict_types=1);

namespace Paysera\Component\CodeClimateMerger\Parser;

use Doctrine\Common\Collections\ArrayCollection;
use Paysera\Component\CodeClimateMerger\Entity\Error;
use Paysera\Component\CodeClimateMerger\Entity\Report;
use SimpleXMLElement;

class CheckstyleParser
{
    public function parse(string $xmlContents)
    {
        $contents = new SimpleXMLElement($xmlContents);
        $reportCollection = new ArrayCollection();

        foreach ($contents as $element) {
            $report = new Report();
            $report->setFilename((string)$element['name']);
            foreach ($element->children() as $child) {
                $error = new Error();
                $error
                    ->setLine((string)$child['line'])
                    ->setColumn((string)$child['column'])
                    ->setMessage((string)$child['message'])
                    ->setSource((string)$child['source'])
                ;
                $report->addError($error);
            }
            $reportCollection->add($report);
        }

        return $reportCollection;
    }
}
