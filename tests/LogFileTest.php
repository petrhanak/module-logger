<?php

/*
	WildPHP - a modular and easily extendable IRC bot written in PHP
	Copyright (C) 2015 WildPHP

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace WPHPTests;


use WildPHP\CoreModules\Logger\LogFileHelper;

class LogFileTest extends \PHPUnit_Framework_TestCase
{
	// Code acquired from comments:
	// http://php.net/manual/en/function.rmdir.php
	public function deleteDirectoryRecursively($dir)
	{
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->deleteDirectoryRecursively("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}

	public function testLogDirCreation()
	{
		$helper = new LogFileHelper();
		$testdir = __DIR__ . '/testlogs';

		// Remove the old directory.
		if (file_exists($testdir))
			$this->deleteDirectoryRecursively($testdir);

		$helper->createLogDir($testdir);

		$this->assertTrue(file_exists($testdir) && is_dir($testdir));
	}

	public function testLogFileCreation()
	{
		$helper = new LogFileHelper();
		$testdir = __DIR__ . '/testlogs';

		// Log dir should exist and be empty from previous tests
		$helper->createNextLogFile($testdir);

		$expectedFileName = 'log ' . date('d-m-Y') . ' (1).log';

		$this->assertTrue(file_exists($testdir . '/' . $expectedFileName));
	}

	public function testCurrentFileNo()
	{
		$helper = new LogFileHelper();
		$testdir = __DIR__ . '/testlogs';

		// A single file should exist from the previous tests
		$no = $helper->getLastFileNo($testdir);

		$this->assertEquals(1, $no);
	}

	public function testCreateNextFile()
	{
		$helper = new LogFileHelper();
		$testdir = __DIR__ . '/testlogs';

		$helper->createNextLogFile($testdir);

		$expectedFileName = 'log ' . date('d-m-Y') . ' (2).log';

		$this->assertTrue(file_exists($testdir . '/' . $expectedFileName));
	}
}