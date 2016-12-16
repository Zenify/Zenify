<?php

declare(strict_types = 1);

namespace Zenify\CodingStandard\Tests\Sniffs\WhiteSpace\PropertiesMethodsMutualSpacing;

use PHPUnit\Framework\TestCase;
use Zenify\CodingStandard\Tests\CodeSnifferRunner;


/**
 * @covers \ZenifyCodingStandard\Sniffs\WhiteSpace\PropertiesMethodsMutualSpacingSniff
 */
final class PropertiesMethodsMutualSpacingSniffTest extends TestCase
{

	public function testDetection()
	{
		$codeSnifferRunner = new CodeSnifferRunner('ZenifyCodingStandard.WhiteSpace.PropertiesMethodsMutualSpacing');

		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong.php'));
		$this->assertSame(1, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/wrong2.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct2.php'));
		$this->assertSame(0, $codeSnifferRunner->getErrorCountInFile(__DIR__ . '/correct3.php'));
	}

}