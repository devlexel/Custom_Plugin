<?php
/**
 * @package  ConspiredmindsPlugin
 */

class AlecadddPluginActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}