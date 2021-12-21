<?php

namespace srag\Plugins\AsqQuestionPool;

use srag\Plugins\AsqQuestionPool\Config\Repository as ConfigRepository;
use srag\Plugins\AsqQuestionPool\ObjectSettings\Repository as ObjectSettingsRepository;
use ilAsqQuestionPoolPlugin;
use srag\DIC\AsqQuestionPool\DICTrait;

/**
 * Class Repository
 *
 * Generated by SrPluginGenerator v2.8.1
 *
 * @package srag\Plugins\AsqQuestionPool
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository
{

    use DICTrait;
    use AsqQuestionPoolTrait;

    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;
    /**
     * @var self|null
     */
    protected static $instance = null;


    /**
     * Repository constructor
     */
    private function __construct()
    {

    }


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * @return ConfigRepository
     */
    public function config() : ConfigRepository
    {
        return ConfigRepository::getInstance();
    }


    /**
     *
     */
    public function dropTables() : void
    {
        $this->config()->dropTables();
        $this->objectSettings()->dropTables();
    }


    /**
     *
     */
    public function installTables() : void
    {
        $this->config()->installTables();
        $this->objectSettings()->installTables();
    }


    /**
     * @return ObjectSettingsRepository
     */
    public function objectSettings() : ObjectSettingsRepository
    {
        return ObjectSettingsRepository::getInstance();
    }
}
