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

use Monolog\Logger as MonoLogger;
use WildPHP\BaseModule;
use WildPHP\CoreModules\Connection\IrcDataObject;

class Logger extends BaseModule
{
	/**
	 * @var MonoLogger
	 */
	protected $logger;

	public function setup()
	{
		$logger = new \Monolog\Logger('WildPHP');

		$this->logger = $logger;

		$logFileHelper = new LogFileHelper();
		$logFileHelper->setLogger($this);
		$logFileHelper->createNewLogFile();

		$this->getEventEmitter()->on('irc.data.in', function (IrcDataObject $object)
		{
			$this->debug($object->getIrcMessage());
		});

		$this->getEventEmitter()->on('irc.data.out', function (IrcDataObject $object)
		{
			$this->debug($object->getIrcMessage());
		});
	}

	/**
	 * @return MonoLogger
	 */
	public function getLogger()
	{
		return $this->logger;
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function debug($message, array $context = [])
	{
		$replacements = [
			"/^:([^!]+)[^ ]+ PRIVMSG #[^ ]+ :(.*)$/" => "<$1> $2",
			"/^:([^!]+)[^ ]+ JOIN :(#[^ ]+)$/" => "*** $1 has joined $2",
			"/^:([^!]+)[^ ]+ QUIT.*$/" => "*** $1 has quit IRC"
		];
		$message = $this->interpolate($message, $context);
		foreach ($replacements as $match => $replace)
		{
			if(preg_match($match, $message)) {
				$msg = preg_replace($match, $replace, $message);
				$this->logger->debug($msg);
			}
		}
	}

	/**
	 * @param string $original
	 * @param string[] $replacements
	 *
	 * @return string
	 */
	protected function interpolate($original, array $replacements)
	{
		foreach ($replacements as $key => $value)
		{
			unset ($replacements[$key]);
			$replacements['{' . $key . '}'] = $value;
		}

		return strtr($original, $replacements);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function info($message, array $context = [])
	{
		$message = $this->interpolate($message, $context);
		$this->logger->info($message);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function warning($message, array $context = [])
	{
		$message = $this->interpolate($message, $context);
		$this->logger->warning($message);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function error($message, array $context = [])
	{
		$message = $this->interpolate($message, $context);
		$this->logger->error($message);
	}
}
