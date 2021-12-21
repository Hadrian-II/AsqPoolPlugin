<?php

namespace srag\Plugins\AsqQuestionPool\ObjectSettings;

use ActiveRecord;
use arConnector;
use ilAsqQuestionPoolPlugin;
use srag\DIC\AsqQuestionPool\DICTrait;
use srag\Plugins\AsqQuestionPool\AsqQuestionPoolTrait;

/**
 * Class ObjectSettings
 *
 * Generated by SrPluginGenerator v2.8.1
 *
 * @package srag\Plugins\AsqQuestionPool\ObjectSettings
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ObjectSettings extends ActiveRecord
{
    use DICTrait;
    use AsqQuestionPoolTrait;

    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;
    const TABLE_NAME = "rep_robj_" . ilAsqQuestionPoolPlugin::PLUGIN_ID . "_set";
    /**
     * @var bool
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       1
     * @con_is_notnull   true
     */
    protected $is_online = false;
    /**
     * @var int
     *
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     */
    protected $obj_id;
    /**
     * @var string
     *
     * @con_has_field    true
     * @con_fieldtype    clob
     */
    protected $data;

    /**
     * ObjectSettings constructor
     *
     * @param int              $primary_key_value
     * @param arConnector|null $connector
     */
    public function __construct(/*int*/ $primary_key_value = 0, /*?*/ arConnector $connector = null)
    {
        parent::__construct($primary_key_value, $connector);
    }


    /**
     * @inheritDoc
     *
     * @deprecated
     */
    public static function returnDbTableName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @inheritDoc
     */
    public function getConnectorContainerName() : string
    {
        return self::TABLE_NAME;
    }


    /**
     * @return int
     */
    public function getObjId() : int
    {
        return $this->obj_id;
    }


    /**
     * @param int $obj_id
     */
    public function setObjId(int $obj_id) : void
    {
        $this->obj_id = $obj_id;
    }


    /**
     * @return bool
     */
    public function isOnline() : bool
    {
        return $this->is_online;
    }


    /**
     * @param bool $is_online
     */
    public function setOnline(bool $is_online = true) : void
    {
        $this->is_online = $is_online;
    }


    /**
     * @inheritDoc
     */
    public function sleep(/*string*/ $field_name)
    {
        $field_value = $this->{$field_name};

        switch ($field_name) {
            case "is_online":
                return ($field_value ? 1 : 0);

            default:
                return parent::sleep($field_name);
        }
    }


    /**
     * @inheritDoc
     */
    public function wakeUp(/*string*/ $field_name, $field_value)
    {
        switch ($field_name) {
            case "obj_id":
                return intval($field_value);

            case "is_online":
                return boolval($field_value);

            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }

    /**
     * @return string
     */
    public function getData() : ?string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data)
    {
        $this->data = $data;
    }
}
