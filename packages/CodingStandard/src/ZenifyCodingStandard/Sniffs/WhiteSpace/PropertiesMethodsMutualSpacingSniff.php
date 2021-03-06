<?php

declare(strict_types = 1);

namespace ZenifyCodingStandard\Sniffs\WhiteSpace;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;
use ZenifyCodingStandard\Helper\PositionFinder;
use ZenifyCodingStandard\Helper\Whitespace\EmptyLinesResizer;


/**
 * Rules:
 * - Between properties and methods should be x empty line(s).
 */
final class PropertiesMethodsMutualSpacingSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * @var string
	 */
	const NAME = 'ZenifyCodingStandard.WhiteSpace.PropertiesMethodsMutualSpacing';

	/**
	 * @var int|string
	 */
	public $desiredBlankLinesInBetween = 2;

	/**
	 * @var PHP_CodeSniffer_File
	 */
	private $file;

	/**
	 * @var int
	 */
	private $position;

	/**
	 * @var array
	 */
	private $tokens;


	/**
	 * @return int[]
	 */
	public function register() : array
	{
		return [T_VARIABLE];
	}


	/**
	 * @param PHP_CodeSniffer_File $file
	 * @param int $position
	 */
	public function process(PHP_CodeSniffer_File $file, $position)
	{
		$this->file = $file;
		$this->position = $position;
		$this->tokens = $file->getTokens();

		// Fix type
		$this->desiredBlankLinesInBetween = (int) $this->desiredBlankLinesInBetween;

		if ($this->isLastProperty() === FALSE) {
			return;
		}

		if ($this->areMethodsPresent() === FALSE) {
			return;
		}

		$next = $file->findNext([T_DOC_COMMENT_OPEN_TAG, T_FUNCTION], $position);

		$positionOfLastProperty = $this->getPositionOfLastProperty();
		$blankLines = $this->tokens[$next]['line'] - $this->tokens[$positionOfLastProperty]['line'] - 1;
		if ($blankLines !== $this->desiredBlankLinesInBetween) {
			$error = sprintf(
				'Between properties and methods should be %s empty line(s); %s found.',
				$this->desiredBlankLinesInBetween,
				$blankLines
			);
			$fix = $file->addFixableError($error, $position);
			if ($fix) {
				$this->fixSpacingInBetween($blankLines);
			}
		}
	}


	private function isLastProperty() : bool
	{
		if ($this->isInsideMethod()) {
			return FALSE;
		}

		$next = $this->file->findNext([T_VARIABLE, T_FUNCTION], $this->position + 1);
		return $this->tokens[$next]['code'] !== T_VARIABLE;
	}


	private function isInsideMethod() : bool
	{
		$previousMethod = $this->file->findPrevious(T_FUNCTION, $this->position);
		return $this->tokens[$previousMethod]['code'] === T_FUNCTION;
	}


	private function areMethodsPresent() : bool
	{
		$next = $this->file->findNext(T_FUNCTION, $this->position + 1);
		return $this->tokens[$next]['code'] === T_FUNCTION;
	}


	private function getPositionOfLastProperty() : int
	{
		$arrayPosition = $this->file->findNext(T_ARRAY, $this->position);
		if ($this->tokens[$arrayPosition]['line'] === $this->tokens[$this->position]['line']) {
			if ($this->tokens[$arrayPosition]['parenthesis_closer']) {
				return $this->tokens[$arrayPosition]['parenthesis_closer'];
			}
		}

		$openShortArrayPosition = $this->file->findNext(T_OPEN_SHORT_ARRAY, $this->position);
		if ($this->tokens[$openShortArrayPosition]['line'] === $this->tokens[$this->position]['line']) {
			return $this->tokens[$openShortArrayPosition]['bracket_closer'];
		}

		return $this->position;
	}


	private function fixSpacingInBetween(int $blankLinesInBetween)
	{
		$position = PositionFinder::findLastPositionInCurrentLine($this->file, $this->getPositionOfLastProperty());

		EmptyLinesResizer::resizeLines(
			$this->file,
			$position,
			$blankLinesInBetween,
			$this->desiredBlankLinesInBetween
		);
	}

}
