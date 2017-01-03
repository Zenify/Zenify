<?php

declare(strict_types = 1);

/*
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace ZenifyCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer_File;
use PSR2_Sniffs_Namespaces_UseDeclarationSniff;


/**
 * Rules:
 * - There must be one USE keyword per declaration
 * - USE declarations must go after the first namespace declaration
 */
final class UseDeclarationSniff extends PSR2_Sniffs_Namespaces_UseDeclarationSniff
{

	/**
	 * @var string
	 */
	const NAME = 'ZenifyCodingStandard.Namespaces.UseDeclaration';

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
		return [T_USE];
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

		if ($this->shouldIgnoreUse() === TRUE) {
			return;
		}

		$this->checkIfSingleSpaceAfterUseKeyword();
		$this->checkIfOneUseDeclarationPerStatement();
		$this->checkIfUseComesAfterNamespaceDeclaration();
	}


	/**
	 * Check if this use statement is part of the namespace block.
	 */
	private function shouldIgnoreUse() : bool
	{
		// Ignore USE keywords inside closures.
		$next = $this->file->findNext(T_WHITESPACE, $this->position + 1, NULL, TRUE);
		if ($this->tokens[$next]['code'] === T_OPEN_PARENTHESIS) {
			return TRUE;
		}

		// Ignore USE keywords for traits.
		if ($this->file->hasCondition($this->position, [T_CLASS, T_TRAIT]) === TRUE) {
			return TRUE;
		}

		return FALSE;
	}


	private function checkIfSingleSpaceAfterUseKeyword()
	{
		if ($this->tokens[$this->position + 1]['content'] !== ' ') {
			$fix = $this->file->addFixableError('There must be a single space after the USE keyword', $this->position);
			if ($fix) {
				$this->fixSingleSpaceAfterUseKeyword();
			}
		}
	}


	private function checkIfOneUseDeclarationPerStatement()
	{
		$next = $this->file->findNext([T_COMMA, T_SEMICOLON], $this->position + 1);
		if ($this->tokens[$next]['code'] === T_COMMA) {
			$this->file->addError('There must be one USE keyword per declaration', $this->position);
		}
	}


	private function checkIfUseComesAfterNamespaceDeclaration()
	{
		$prev = $this->file->findPrevious(T_NAMESPACE, $this->position - 1);
		if ($prev !== FALSE) {
			$first = $this->file->findNext(T_NAMESPACE, 1);
			if ($prev !== $first) {
				$this->file->addError('USE declarations must go after namespace declaration.', $this->position);
			}
		}
	}


	private function fixSingleSpaceAfterUseKeyword()
	{
		$this->file->fixer->replaceToken($this->position + 1, ' ');
	}

}
