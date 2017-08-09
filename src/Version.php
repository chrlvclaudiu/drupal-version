<?php


namespace DreamProduction\Composer;

use Composer\Composer;
use Composer\Util\ProcessExecutor;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\Event;
use Composer\IO\IOInterface;


class Version implements PluginInterface, EventSubscriberInterface {

	public function activate(Composer $composer, IOInterface $io) {
	    $this->composer = $composer;
	    $this->io = $io;
	    $this->eventDispatcher = $composer->getEventDispatcher();
	    $this->executor = new ProcessExecutor($this->io);
    }

	public static function getSubscribedEvents() {
	    return array(
	        'init' => 'checkDrupalVersion'
	    );
	}

	public function checkDrupalVersion(Event $event) {
		$thus-
	}
}