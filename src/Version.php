<?php


namespace DreamProduction\Composer;

use Sunra\PhpSimple\HtmlDomParser;

use Composer\Composer;
use Composer\Util\ProcessExecutor;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\EventDispatcher\Event;
use Composer\IO\IOInterface;


class Version implements PluginInterface, EventSubscriberInterface {
  
  // This is used to remove the prefix from the release text and obtaining only
  // the version number
  const DRUPAL_CORE_PREFIX_TEXT = 'Drupal core';
  
  /**
   * @var Composer $composer
   */
  protected $composer;
  /**
   * @var IOInterface $io
   */
  protected $io;
  /**
   * @var EventDispatcher $eventDispatcher
   */
  protected $eventDispatcher;
  /**
   * @var ProcessExecutor $executor
   */
  protected $executor;

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
    // Get the latest stable Drupal version
    $this->getDrupalStableVersion();
		$this->io->write('<info>Drupal version check works.</info>');
	}
  
  protected function getDrupalStableVersion() {
    try {
      // Load the HTML from Drupal.org
      $html =  HtmlDomParser::file_get_html('https://www.drupal.org/project/drupal');
      
      // Find the first release row (assume it's Drupal 8, as being the most important now)
      if ($release_div = $html->find('div.pane-project-downloads-recommended div.views-content div.views-row div.views-field-field-release-version div.field-content', 0)) {
        // Get the release text title
        $release_h4_text = $release_div->find('h4', 0)->text;
        $this->io->write('<info>Drupal version: ' . trim(str_replace(Version::DRUPAL_CORE_PREFIX_TEXT, '', $release_h4_text)) . '</info>');
        
        return $release_h4_text;
      }
      
      return NULL;
    }
    catch (Exception $ex) {
      $this->io->write('<error>Unhandled exception: ' . $ex->getMessage() . '</error>');
    }
  }
}