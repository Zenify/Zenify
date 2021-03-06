<?php

declare(strict_types = 1);

namespace ZenifyCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer_File;
use PHP_CodeSniffer_Sniff;
use ZenifyCodingStandard\Helper\Whitespace\ClassMetrics;
use ZenifyCodingStandard\Helper\Whitespace\WhitespaceFinder;


/**
 * Rules:
 * - There must be x empty line(s) after the namespace declaration or y empty line(s) followed by use statement.
 */
final class NamespaceDeclarationSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * @var string
	 */
	const NAME = 'ZenifyCodingStandard.Namespaces.NamespaceDeclaration';

	/**
	 * @var int|string
	 */
	public $emptyLinesAfterNamespace = 2;

	/**
	 * @var int|string
	 */
	private $emptyLinesBeforeUseStatement = 1;

	/**
	 * @var PHP_CodeSniffer_File
	 */
	private $file;

	/**
	 * @var int
	 */
	private $position;

	/**
	 * @var array[]
	 */
	private $tokens;

	/**
	 * @var ClassMetrics
	 */
	private $classMetrics;


	/**
	 * @return int[]
	 */
	public function register() : array
	{
		return [T_NAMESPACE];
	}


	/**
	 * @param PHP_CodeSniffer_File $file
	 * @param int $position
	 */
	public function process(PHP_CodeSniffer_File $file, $position)
	{
		$classPosition = $file->findNext([T_CLASS, T_TRAIT, T_INTERFACE], $position);
		if ( ! $classPosition) {
			// there is no class, nothing to see here
			return;
		}

		$this->file = $file;
		$this->position = $position;
		$this->tokens = $file->getTokens();

		// Fix type
		$this->emptyLinesAfterNamespace = (int) $this->emptyLinesAfterNamespace;
		$this->emptyLinesBeforeUseStatement = (int) $this->emptyLinesBeforeUseStatement;

		// prepare class metrics class
		$this->classMetrics = new ClassMetrics($file, $classPosition);

		$lineDistanceBetweenNamespaceAndFirstUseStatement =
			$this->classMetrics->getLineDistanceBetweenNamespaceAndFirstUseStatement();
		$lineDistanceBetweenClassAndNamespace =
			$this->classMetrics->getLineDistanceBetweenClassAndNamespace();

		if ($lineDistanceBetweenNamespaceAndFirstUseStatement) {
			$this->processWithUseStatement($lineDistanceBetweenNamespaceAndFirstUseStatement);

		} else {
			$this->processWithoutUseStatement($lineDistanceBetweenClassAndNamespace);
		}
	}


	private function processWithoutUseStatement(int $linesToNextClass)
	{
		if ($linesToNextClass) {
			if ($linesToNextClass !== $this->emptyLinesAfterNamespace) {
				$errorMessage = sprintf(
					'There should be %s empty line(s) after the namespace declaration; %s found',
					$this->emptyLinesAfterNamespace,
					$linesToNextClass
				);

				$fix = $this->file->addFixableError($errorMessage, $this->position);
				if ($fix) {
					$this->fixSpacesFromNamespaceToClass($this->position, $linesToNextClass);
				}
			}
		}
	}


	private function processWithUseStatement(int $linesToNextUse)
	{
		if ($linesToNextUse !== $this->emptyLinesBeforeUseStatement) {
			$errorMessage = sprintf(
				'There should be %s empty line(s) from namespace to use statement; %s found',
				$this->emptyLinesBeforeUseStatement,
				$linesToNextUse
			);

			$fix = $this->file->addFixableError($errorMessage, $this->position);
			if ($fix) {
				$this->fixSpacesFromNamespaceToUseStatements($this->position, $linesToNextUse);
			}
		}

		$linesToNextClass = $this->classMetrics->getLineDistanceBetweenClassAndLastUseStatement();
		if ($linesToNextClass !== $this->emptyLinesAfterNamespace) {
			$errorMessage = sprintf(
				'There should be %s empty line(s) between last use and class; %s found',
				$this->emptyLinesAfterNamespace,
				$linesToNextClass
			);

			$fix = $this->file->addFixableError($errorMessage, $this->position);
			if ($fix) {
				$this->fixSpacesFromUseStatementToClass(
					$this->classMetrics->getLastUseStatementPosition(),
					$linesToNextClass
				);
			}
		}
	}


	private function fixSpacesFromNamespaceToUseStatements(int $position, int $linesToNextUse)
	{
		$nextLinePosition = WhitespaceFinder::findNextEmptyLinePosition($this->file, $position);

		if ($linesToNextUse < $this->emptyLinesBeforeUseStatement) {
			for ($i = $linesToNextUse; $i < $this->emptyLinesBeforeUseStatement; $i++) {
				$this->file->fixer->addContent($nextLinePosition, PHP_EOL);
			}

		} else {
			for ($i = $linesToNextUse; $i > $this->emptyLinesBeforeUseStatement; $i--) {
				$this->file->fixer->replaceToken($nextLinePosition, '');
				$nextLinePosition = WhitespaceFinder::findNextEmptyLinePosition($this->file, $nextLinePosition);
			}
		}
	}


	private function fixSpacesFromNamespaceToClass(int $position, int $linesToClass)
	{
		$nextLinePosition = WhitespaceFinder::findNextEmptyLinePosition($this->file, $position);
		if ($linesToClass < $this->emptyLinesAfterNamespace) {
			for ($i = $linesToClass; $i < $this->emptyLinesAfterNamespace; $i++) {
				$this->file->fixer->addContent($nextLinePosition, PHP_EOL);
			}

		} else {
			for ($i = $linesToClass; $i > $this->emptyLinesAfterNamespace; $i--) {
				$this->file->fixer->replaceToken($nextLinePosition, '');
				$nextLinePosition = WhitespaceFinder::findNextEmptyLinePosition($this->file, $nextLinePosition);
			}
		}
	}


	private function fixSpacesFromUseStatementToClass(int $position, int $linesToClass)
	{
		if ($linesToClass < $this->emptyLinesAfterNamespace) {
			for ($i = $linesToClass; $i < $this->emptyLinesAfterNamespace; $i++) {
				$this->file->fixer->addContent($position, PHP_EOL);
			}

		} else {
			$nextLinePosition = WhitespaceFinder::findNextEmptyLinePosition($this->file, $position);
			for ($i = $linesToClass; $i > $this->emptyLinesAfterNamespace; $i--) {
				$this->file->fixer->replaceToken($nextLinePosition, '');
				$nextLinePosition = WhitespaceFinder::findNextEmptyLinePosition($this->file, $nextLinePosition);
			}
		}
	}

}
