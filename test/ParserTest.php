<?php
declare(strict_types=1);

namespace Paysera\Component\CodeClimateMerger\Test;

use Doctrine\Common\Collections\ArrayCollection;
use Paysera\Component\CodeClimateMerger\Entity\Error;
use Paysera\Component\CodeClimateMerger\Entity\Report;
use Paysera\Component\CodeClimateMerger\Parser\CheckstyleParser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    /**
     * @var CheckstyleParser
     */
    private $checkstyleParser;

    public function setUp()
    {
        $this->checkstyleParser = new CheckstyleParser();
    }

    public function testChecksyleParser()
    {
        $checkstyle = file_get_contents(__DIR__ . '/Fixtures/checkstyle_fixer.xml');

        $actual = $this->checkstyleParser->parse($checkstyle);
        $expected = $this->getExpected();

        $this->assertEquals($expected, $actual);
    }

    private function getExpected()
    {
        $expected = new ArrayCollection();

        $expected->add(
            (new Report())
                ->setFilename('/var/lib/jenkins/workspace/Releases/eslint Release/eslint/fullOfProblems.js')
                ->setErrors(
                    new ArrayCollection(
                        [
                            (new Error())
                                ->setMessage('Missing semicolon. (semi)')
                                ->setSource('eslint.rules.semi')
                                ->setLine('5')
                                ->setColumn('13'),
                            (new Error())
                                ->setMessage('Unnecessary semicolon. (no-extra-semi)')
                                ->setSource('eslint.rules.no-extra-semi')
                                ->setLine('7')
                                ->setColumn('2'),
                        ]
                    )
                )
        );
        $expected->add(
            (new Report())
                ->setFilename('/src/Bundle/index.js')
                ->setErrors(
                    new ArrayCollection(
                        [
                            (new Error())
                                ->setMessage('Violations found: violation')
                                ->setSource('eslint.rules.violation')
                                ->setLine('45')
                                ->setColumn('13'),
                            (new Error())
                                ->setMessage('Unnecessary semicolon. (no-extra-semi)')
                                ->setSource('eslint.rules.no-extra-semi')
                                ->setLine('777')
                                ->setColumn('2'),
                        ]
                    )
                )
        );
        $expected->add(
            (new Report())
                ->setFilename('src/Entity/Email.php')
                ->setErrors(
                    new ArrayCollection(
                        [
                            (new Error())
                                ->setMessage('Found violation(s) of type: php_basic_comment_php_doc_on_properties')
                                ->setSource('PHP-CS-Fixer.php_basic_comment_php_doc_on_properties')
                                ->setLine('')
                                ->setColumn(''),
                            (new Error())
                                ->setMessage('Found violation(s) of type: php_basic_code_style_directory_and_namespace')
                                ->setSource('PHP-CS-Fixer.php_basic_code_style_directory_and_namespace')
                                ->setLine('')
                                ->setColumn(''),
                        ]
                    )
                )
        );

        return $expected;
    }
}
