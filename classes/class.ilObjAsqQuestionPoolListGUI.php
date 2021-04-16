<?php

use srag\Plugins\AsqQuestionPool\Utils\AsqQuestionPoolTrait;
use srag\DIC\AsqQuestionPool\DICTrait;

/**
 * Class ilObjAsqQuestionPoolListGUI
 *
 * Generated by SrPluginGenerator v2.8.1
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ilObjAsqQuestionPoolListGUI extends ilObjectPluginListGUI
{

    use DICTrait;
    use AsqQuestionPoolTrait;

    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;


    /**
     * ilObjAsqQuestionPoolListGUI constructor
     *
     * @param int $a_context
     */
    public function __construct(/*int*/ $a_context = self::CONTEXT_REPOSITORY)
    {
        parent::__construct($a_context);
    }


    /**
     * @inheritDoc
     */
    public function getGuiClass() : string
    {
        return ilObjAsqQuestionPoolGUI::class;
    }


    /**
     * @inheritDoc
     */
    public function getProperties() : array
    {
        $props = [];

        if (ilObjAsqQuestionPoolAccess::_isOffline($this->obj_id)) {
            $props[] = [
                "alert"    => true,
                "property" => self::plugin()->translate("status", ilObjAsqQuestionPoolGUI::LANG_MODULE_OBJECT),
                "value"    => self::plugin()->translate("offline", ilObjAsqQuestionPoolGUI::LANG_MODULE_OBJECT)
            ];
        }

        return $props;
    }


    /**
     * @inheritDoc
     */
    public function initCommands() : array
    {
        $this->commands_enabled = true;
        $this->copy_enabled = true;
        $this->cut_enabled = true;
        $this->delete_enabled = true;
        $this->description_enabled = true;
        $this->notice_properties_enabled = true;
        $this->properties_enabled = true;

        $this->comments_enabled = false;
        $this->comments_settings_enabled = false;
        $this->expand_enabled = false;
        $this->info_screen_enabled = false;
        $this->link_enabled = false;
        $this->notes_enabled = false;
        $this->payment_enabled = false;
        $this->preconditions_enabled = false;
        $this->rating_enabled = false;
        $this->rating_categories_enabled = false;
        $this->repository_transfer_enabled = false;
        $this->search_fragment_enabled = false;
        $this->static_link_enabled = false;
        $this->subscribe_enabled = false;
        $this->tags_enabled = false;
        $this->timings_enabled = false;

        $commands = [
            [
                "permission" => "read",
                "cmd"        => ilObjAsqQuestionPoolGUI::getStartCmd(),
                "default"    => true
            ]
        ];

        return $commands;
    }


    /**
     * @inheritDoc
     */
    public function initType() : void
    {
        $this->setType(ilAsqQuestionPoolPlugin::PLUGIN_ID);
    }
}
