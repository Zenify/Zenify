<?php

declare(strict_types = 1);

namespace Zenify\CodingStandard\Tests\Sniffs\Namespaces\UseDeclaration;

use PHPUnit\Framework\TestCase;
use Zenify\CodingStandard\Tests\CodeSnifferRunner;
use ZenifyCodingStandard\Sniffs\Namespaces\UseDeclarationSniff;


final class UseDeclarationSniffTest extends TestCase
{

	public function testDetection()
	{
		$codeSnifferRunner = new CodeSnifferRunner(UseDeclarationSniff::NAME);

		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct2.php'));
		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong.php'));
		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong2.php'));
		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong3.php'));
		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong4.php'));
		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong5.php'));
	}


	public function testFixing()
	{
		$codeSnifferRunner = new CodeSnifferRunner(UseDeclarationSniff::NAME);

		$fixedContent = $codeSnifferRunner->getFixedContent(__DIR__ . '/wrong4.php');
		$this->assertSame(file_get_contents(__DIR__ . '/wrong4-fixed.php'), $fixedContent);

		$fixedContent = $codeSnifferRunner->getFixedContent(__DIR__ . '/wrong5.php');
		$this->assertSame(file_get_contents(__DIR__ . '/wrong4-fixed.php'), $fixedContent);
	}

}
