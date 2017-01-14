<?php

/**
* @package   s9e\debug
* @copyright Copyright (c) 2017 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\debug;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use s9e\TextFormatter\Utils;

class listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'core.text_formatter_s9e_configure_after' => 'onConfigure',
			'core.text_formatter_s9e_parse_after'     => 'onParse'
		];
	}

	public function onConfigure($event)
	{
		$event['configurator']->BBCodes->addCustom(
			'[debug]',
			'<pre><code><xsl:value-of select="@log"/></code></pre>'
		);
	}

	public function onParse($event)
	{
		$log = $event['parser']->get_parser()->getLogger()->get();

		$event['xml'] = Utils::replaceAttributes(
			$event['xml'],
			'DEBUG',
			function ($attributes) use ($log)
			{
				$attributes['log'] = base64_encode(print_r($log, true));

				return $attributes;
			}
		);
	}
}
