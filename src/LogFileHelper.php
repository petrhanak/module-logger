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

namespace WildPHP\CoreModules\Logger;


use Monolog\Handler\StreamHandler;

class LogFileHelper
{
	/**
	 * @var Logger
	 */
	protected $parent = null;

	/**
	 * @var string
	 */
	protected $logFileRegex = '/log (\d{1,2}-\d{1,2}-\d{4}) \((\d+)\)/';

	/**
	 * 1: Date
	 * 2: #
	 * @var string
	 */
	protected $logFilePattern = 'log %s (%s)';

	/**
	 * @var string
	 */
	protected $logFileExtension = '.log';

	public function __construct(Logger $logger)
	{
		$this->parent = $logger;

		try
		{
			$path = WPHP_LOG_DIR;
			$this->createLogDir($path);
			$file = $this->createNextLogFile($path);

			$fullPath = $path . $file;
			var_dump($fullPath);
			$this->parent->getLogger()->pushHandler(new StreamHandler($fullPath));
		}
		catch (InvalidLogDirException $e)
		{
			$this->parent->error('Could not write to log directory with error: ' . $e->getMessage());
		}
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public function createLogDir($path)
	{
		if (file_exists($path) && !is_dir($path))
			throw new InvalidLogDirException('Path ' . $path . ' exists but is not a directory.');

		if (!file_exists($path) && !mkdir($path, 0755))
			throw new InvalidLogDirException('Path ' . $path . ' did not exist and could not be created.');

		if (!file_exists($path))
			throw new InvalidLogDirException('All attempts to create path ' . $path . ' failed.');

		return $path;
	}

	/**
	 * @param string $dir
	 * @return string
	 */
	public function createNextLogFile($dir)
	{
		$this->createLogDir($dir);

		$files = scandir($dir, SCANDIR_SORT_ASCENDING);

		var_dump($dir, $files);

		$lastFileNo = 0;
		foreach ($files as $file)
		{
			if (!in_array($file, ['.', '..']))
				continue;

			preg_match($this->logFileRegex, $file, $matches);

			var_dump($matches);

			if (empty($matches))
				continue;

			if ($matches[1] == date('d-m-Y'))
				$lastFileNo = $matches[2];
		}

		$newFileNo = $lastFileNo + 1;


		$fileName = sprintf($this->logFilePattern, date('d-m-Y'), $newFileNo) . $this->logFileExtension;
		touch($fileName);
		return $fileName;
	}
}