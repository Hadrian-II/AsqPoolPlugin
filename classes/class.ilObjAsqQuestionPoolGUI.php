<?php

use srag\asq\QuestionPool\UI\QuestionListGUI;
use srag\Plugins\AsqQuestionPool\Utils\AsqQuestionPoolTrait;
use srag\asq\Application\Service\AuthoringContextContainer;
use srag\asq\Application\Service\ASQDIC;
use srag\DIC\AsqQuestionPool\DICTrait;
use srag\asq\QuestionPool\Application\QuestionPoolService;
use ILIAS\Data\UUID\Factory;
use ILIAS\Data\UUID\Uuid;
use srag\asq\Application\Service\AsqServices;
use srag\asq\Application\Service\IAuthoringCaller;
use srag\asq\Domain\QuestionDto;
use srag\asq\Infrastructure\Helpers\PathHelper;

/**
 * Class ilObjAsqQuestionPoolGUI
 *
 * Generated by SrPluginGenerator v2.8.1
 *
 * @author studer + raimann ag - Team Custom 2 <support-custom2@studer-raimann.ch>
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy ilObjAsqQuestionPoolGUI: ilRepositoryGUI
 * @ilCtrl_isCalledBy ilObjAsqQuestionPoolGUI: ilObjPluginDispatchGUI
 * @ilCtrl_isCalledBy ilObjAsqQuestionPoolGUI: ilAdministrationGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilPermissionGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilInfoScreenGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilObjectCopyGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: ilCommonActionDispatcherGUI
 * @ilCtrl_Calls      ilObjAsqQuestionPoolGUI: AsqQuestionAuthoringGUI
 */
class ilObjAsqQuestionPoolGUI extends ilObjectPluginGUI implements IAuthoringCaller
{
    use PathHelper;
    use DICTrait;
    use AsqQuestionPoolTrait;

    const CMD_PERMISSIONS = "perm";
    const CMD_SETTINGS = "settings";
    const CMD_SETTINGS_STORE = "settingsStore";
    const CMD_SHOW_QUESTIONS = "showQuestions";
    const LANG_MODULE_OBJECT = "object";
    const LANG_MODULE_SETTINGS = "settings";
    const PLUGIN_CLASS_NAME = ilAsqQuestionPoolPlugin::class;
    const TAB_CONTENTS = "contents";
    const TAB_PERMISSIONS = "perm_settings";
    const TAB_SETTINGS = "settings";
    const TAB_SHOW_QUESTIONS = "show_questions";



    /**
     * @var ilObjAsqQuestionPool
     */
    public $object;

    /**
     * @var Uuid
     */
    private $pool_id;

    /**
     * @var QuestionPoolService
     */
    private $pool_service;

    /**
     * @var AsqServices
     */
    private $asq_service;

    /**
     * @var Factory
     */
    private $uuid_factory;

    private QuestionListGUI $question_list_gui;

    /**
     * @inheritDoc
     */
    protected function afterConstructor() : void
    {
        global $DIC, $ASQDIC;

        ASQDIC::initiateASQ($DIC);
        $this->asq_service = $ASQDIC->asq();
        $this->pool_service = new QuestionPoolService();
        $this->uuid_factory = new Factory();

        $this->loadPool();

        $this->question_list_gui = new QuestionListGUI($this->pool_id);
    }

    private function loadPool() : void
    {
        if (!is_null($this->object)) {
            $raw_pool_id = $this->object->getData();

            if (is_null($raw_pool_id)) {
                $this->pool_id = $this->pool_service->createQuestionPool(
                    $this->object->getTitle(),
                    $this->object->getDescription());
                $this->object->setData($this->pool_id->toString());
                $this->object->doUpdate();
            }
            else {
                $this->pool_id = $this->uuid_factory->fromString($raw_pool_id);
            }
        }
    }


    /**
     * @return string
     */
    public static function getStartCmd() : string
    {
        return self::CMD_SHOW_QUESTIONS;
    }


    /**
     * @inheritDoc
     *
     * @param ilObjAsqQuestionPool $a_new_object
     */
    public function afterSave(/*ilObjAsqQuestionPool*/ ilObject $a_new_object) : void
    {
        parent::afterSave($a_new_object);
    }


    /**
     * @inheritDoc
     */
    public function getAfterCreationCmd() : string
    {
        return self::getStartCmd();
    }


    /**
     * @inheritDoc
     */
    public function getStandardCmd() : string
    {
        return self::getStartCmd();
    }


    /**
     * @inheritDoc
     */
    public final function getType() : string
    {
        return ilAsqQuestionPoolPlugin::PLUGIN_ID;
    }


    /**
     * @inheritDoc
     */
    public function initCreateForm(/*string*/ $a_new_type) : ilPropertyFormGUI
    {
        $form = parent::initCreateForm($a_new_type);

        return $form;
    }


    /**
     * @param string $cmd
     */
    public function performCommand(string $cmd) : void
    {
        self::dic()->help()->setScreenIdComponent(ilAsqQuestionPoolPlugin::PLUGIN_ID);
        self::dic()->ui()->mainTemplate()->setPermanentLink(ilAsqQuestionPoolPlugin::PLUGIN_ID, $this->object->getRefId());

        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            case strtolower(AsqQuestionAuthoringGUI::class):
                self::dic()->tabs()->activateTab(self::TAB_SHOW_QUESTIONS);
                $this->showAuthoring();
                return;
            default:
                switch ($cmd) {
                    case self::CMD_SHOW_QUESTIONS:
                        // Read commands
                        if (!ilObjAsqQuestionPoolAccess::hasReadAccess()) {
                            ilObjAsqQuestionPoolAccess::redirectNonAccess(ilRepositoryGUI::class);
                        }

                        $this->{$cmd}();
                        break;

                    case self::CMD_SETTINGS:
                    case self::CMD_SETTINGS_STORE:
                    case self::CMD_QUESTION_ACTION:
                        // Write commands
                        if (!ilObjAsqQuestionPoolAccess::hasWriteAccess()) {
                            ilObjAsqQuestionPoolAccess::redirectNonAccess($this);
                        }

                        $this->{$cmd}();
                        break;

                    default:
                        // Unknown command
                        ilObjAsqQuestionPoolAccess::redirectNonAccess(ilRepositoryGUI::class);
                        break;
                }
                break;
        }
    }

    /**
     *
     */
    protected function setTabs() : void
    {
        self::dic()->tabs()->addTab(self::TAB_SHOW_QUESTIONS, self::plugin()->translate("show_contents", self::LANG_MODULE_OBJECT), self::dic()->ctrl()
            ->getLinkTarget($this, self::CMD_SHOW_QUESTIONS));

        if (ilObjAsqQuestionPoolAccess::hasWriteAccess()) {
            self::dic()->tabs()->addTab(self::TAB_SETTINGS, self::plugin()->translate("settings", self::LANG_MODULE_SETTINGS), self::dic()->ctrl()
                ->getLinkTarget($this, self::CMD_SETTINGS));
        }

        if (ilObjAsqQuestionPoolAccess::hasEditPermissionAccess()) {
            self::dic()->tabs()->addTab(self::TAB_PERMISSIONS, self::plugin()->translate(self::TAB_PERMISSIONS, "", [], false), self::dic()->ctrl()
                ->getLinkTargetByClass([
                    self::class,
                    ilPermissionGUI::class
                ], self::CMD_PERMISSIONS));
        }

        self::dic()->tabs()->manual_activation = true; // Show all tabs as links when no activation
    }


    /**
     *
     */
    protected function settings() : void
    {
        self::dic()->tabs()->activateTab(self::TAB_SETTINGS);

        $form = self::asqQuestionPool()->objectSettings()->factory()->newFormBuilderInstance($this, $this->object);

        self::output()->output($form);
    }


    /**
     *
     */
    protected function settingsStore() : void
    {
        self::dic()->tabs()->activateTab(self::TAB_SETTINGS);

        $form = self::asqQuestionPool()->objectSettings()->factory()->newFormBuilderInstance($this, $this->object);

        if (!$form->storeForm()) {
            self::output()->output($form);

            return;
        }

        ilUtil::sendSuccess(self::plugin()->translate("saved", self::LANG_MODULE_SETTINGS), true);

        self::dic()->ctrl()->redirect($this, self::CMD_SETTINGS);
    }


    /**
     * @param string $html
     */
    protected function show(string $html) : void
    {
        if (!self::dic()->ctrl()->isAsynch()) {
            self::dic()->ui()->mainTemplate()->setTitle($this->object->getTitle());

            self::dic()->ui()->mainTemplate()->setDescription($this->object->getDescription());

            if (!$this->object->isOnline()) {
                self::dic()->ui()->mainTemplate()->setAlertProperties([
                    [
                        "alert"    => true,
                        "property" => self::plugin()->translate("status", self::LANG_MODULE_OBJECT),
                        "value"    => self::plugin()->translate("offline", self::LANG_MODULE_OBJECT)
                    ]
                ]);
            }
        }

        self::output()->output($html);
    }

    protected function showQuestions() : void
    {
        if ($_POST[QuestionListGUI::VAR_ACTION] !== null) {
            $this->question_list_gui->{$_POST[QuestionListGUI::VAR_ACTION]}();
        }

        self::dic()->tabs()->activateTab(self::TAB_SHOW_QUESTIONS);

        foreach ($this->question_list_gui->getToolbarButtons() as $button) {
            self::dic()->toolbar()->addButtonInstance($button);
        }

        $question_table = $this->question_list_gui->createQuestionTable($this);

        $this->show($this->renderQuestionPool($question_table));
    }

    private function renderQuestionPool(ilTable2GUI $table) : string
    {
        $tpl = new ilTemplate($this->getBasePath(__DIR__) . 'templates/default/tpl.show_questions.html', true, true);
        $tpl->setVariable('QUESTION_TABLE', $table->getHTML());
        return $tpl->get();
    }

    /**
     * {@inheritDoc}
     * @see \srag\asq\Application\Service\IAuthoringCaller::afterQuestionCreated()
     */
    public function afterQuestionCreated(QuestionDto $question) : void
    {
        $this->pool_service->addQuestion($this->pool_id, $question->getId());
    }

    private function showAuthoring()
    {
        global $ASQDIC;

        $backLink = self::dic()->ui()->factory()->link()->standard(
            self::dic()->language()->txt('back'),
            self::dic()->ctrl()->getLinkTarget($this, self::CMD_SHOW_QUESTIONS)
        );


        $authoring_context_container = new AuthoringContextContainer(
            $backLink,
            $this->object->getRefId(),
            $this->object->getId(),
            $this->object->getType(),
            self::dic()->user()->getId(),
            $this
        );

        $asq = new AsqQuestionAuthoringGUI(
            $authoring_context_container,
            self::dic()->language(),
            self::dic()->ui(),
            self::dic()->ctrl(),
            self::dic()->tabs(),
            self::dic()->access(),
            self::dic()->http(),
            $ASQDIC->asq()
        );

        self::dic()->ctrl()->forwardCommand($asq);
    }
}
