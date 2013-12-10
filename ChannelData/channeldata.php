<?php

function __autoLoad($className)
{
	$className   = ltrim($className, '\\');
	$extension   = '.php';
	$directories = array(
		APPPATH . 'models',
		'base',
		'components',
		'models',
		'responses',
		'../../models',
		'../../models/channels'
	);

	if(!class_exists($className))
	{
		foreach($directories as $directory)
		{
			$fileName  = '';
			$namespace = '';

			if ($lastNsPos = strrpos($className, '\\')) {
			    $namespace = substr($className, 0, $lastNsPos);
			    $className = substr($className, $lastNsPos + 1);
			    $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
			}

			$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
			$filePath  = __DIR__ . '/' . $directory . '/' . $fileName;
			
			if(file_exists($filePath))
			{
				require_once($filePath);
			}
		}
	}
}

if(!function_exists('is_closure'))
{
	function is_closure($t)
	{
		return is_object($t) && ($t instanceof Closure);
	}
}

/**
 * ChannelData CodeIgniter Driver
 *
 * Dummy class to trigger autoloading, and give access to helper methods
 *
 * @return	void
 */

class ChannelData {}