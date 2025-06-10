<?php

/**
 * @file plugins/generic/openAIREBrokerService/OpenAIREBrokerServicePlugin.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAIREBrokerServicePlugin
 * @ingroup plugins_generic_openAIREBrokerService
 *
 * @brief OpenAIRE Broker Service plugin class
 */
namespace APP\plugins\generic\openAIREBrokerService;

use PKP\core\JSONMessage;
use APP\template\TemplateManager;
use PKP\linkAction\LinkAction;
use PKP\linkAction\request\AjaxModal;
use PKP\plugins\GenericPlugin;
use PKP\plugins\Hook;

use APP\plugins\generic\openAIREBrokerService\OpenAIREBrokerServiceSettingsForm;
use APP\plugins\generic\openAIREBrokerService\controllers\grid\OpenAIREBrokerServiceGridHandler;
use APP\plugins\generic\openAIREBrokerService\controllers\grid\OpenAIREBrokerServiceContextGridHandler;



class OpenAIREBrokerServicePlugin extends GenericPlugin {

    const CONFIG_VARS = array(
        'enrich_more_openaccess_version' => 'string',
        'enrich_more_pid' => 'string',
        'enrich_missing_author_orcid' => 'string',
        'enrich_missing_pid' => 'string',
        'enrich_missing_abstract' => 'string',
        'enrich_missing_subject_ddc' => 'string',
        'enrich_more_subject_ddc' => 'string',
        'enrich_missing_subject_jel' => 'string',
        'enrich_more_subject_jel' => 'string',
        'enrich_missing_publication_date' => 'string',
        'enrich_missing_openaccess_version' => 'string',
        'enrich_missing_subject_acm' => 'string',
        'enrich_more_subject_acm' => 'string',
        'enrich_missing_project' => 'string',
        'enrich_missing_subject_mesheuropmc' => 'string',
        'enrich_more_subject_mesheuropmc' => 'string',
        'enrich_missing_subject_arxiv' => 'string',
        'enrich_more_subject_arxiv' => 'string'
    );

    /**
     * @copydoc Plugin::register()
     */
    function register($category, $path, $mainContextId = null) {
        $success = parent::register($category, $path, $mainContextId);
        if ($success && $this->getEnabled($mainContextId)) {

            Hook::add('Schema::get::context', [$this, 'addToSchema']);
            Hook::add('Template::Settings::website', array($this, 'callbackShowWebsiteSettingsTabs'));
            Hook::add('Template::Workflow::Publication', array($this, 'addToPublicationForms'));

            Hook::add('LoadComponentHandler', array($this, 'setupGridHandler'));
            Hook::add('LoadComponentHandler', array($this, 'setupContextGridHandler'));
        }
        return $success;
    }

    /**
     * Get the name of this plugin. The name must be unique within
     * its category.
     * @return String name of plugin
     */
    function getName() {
        return 'OpenAIREBrokerServicePlugin';
    }

    /**
     * @copydoc Plugin::getDisplayName()
     */
    function getDisplayName() {
        return __('plugins.generic.openAIREBrokerService.displayName');
    }

    /**
     * @copydoc Plugin::getDescription()
     */
    function getDescription() {
        return __('plugins.generic.openAIREBrokerService.description');
    }

    /**
     * Extend the context entity's schema with an aditionals properties
     */
    public function addToSchema(string $hookName, array $args) {
        $schema = $args[0];/** @var stdClass */
        foreach (self::CONFIG_VARS as $configVar => $type) {
            $schema->properties->$configVar = (object) [
                        'type' => $type,
                        'apiSummary' => true,
                        'multilingual' => false,
                        'validation' => ['nullable']
            ];
        }
        return false;
    }

    /**
     * Extend the website settings tabs to include OpenAIRE enrichments
     * @param $hookName string The name of the invoked hook
     * @param $args array Hook parameters
     * @return boolean Hook handling status
     */
    function callbackShowWebsiteSettingsTabs($hookName, $args) {
        $templateMgr = $args[1];
        $output = & $args[2];

        $output .= $templateMgr->fetch($this->getTemplateResource('contextEnrichments.tpl'));

        // Permit other plugins to continue interacting with this hook
        return false;
    }

    /**
     * Permit requests to the OpenAIRE Broker Service grid handler
     * @param $hookName string The name of the hook being invoked
     * @param $args array The parameters to the invoked hook
     */
    function setupContextGridHandler($hookName, $params) {
        $component = & $params[0];
        $handler = & $params[2];
        if ($component == 'plugins.generic.openAIREBrokerService.controllers.grid.OpenAIREBrokerServiceContextGridHandler') {            
            $handler = new OpenAIREBrokerServiceContextGridHandler($this);
            return true;
        }
        return false;
    }

    /**
     * Permit requests to the OpenAIRE Broker Service grid handler
     * @param $hookName string The name of the hook being invoked
     * @param $args array The parameters to the invoked hook
     */
    function setupGridHandler($hookName, $params) {
        $component = & $params[0];
        $handler = & $params[2];
        if ($component == 'plugins.generic.openAIREBrokerService.controllers.grid.OpenAIREBrokerServiceGridHandler') {

            $handler = new OpenAIREBrokerServiceGridHandler($this);
            return true;
        }
        return false;
    }

    /**
     * Insert article's enrichments in the publication tabs
     */
    function addToPublicationForms($hookName, $params) {
        $smarty = & $params[1];
        $output = & $params[2];

        $output .= sprintf(
                '<tab id="openAireEnrichmentsInWorkflow" label="%s">%s</tab>',
                __('plugins.generic.openAIREBrokerService'),
                $smarty->fetch($this->getTemplateResource('articleEnrichments.tpl'))
        );
        return false;
    }

    /**
     * @copydoc Plugin::getActions()
     */
    function getActions($request, $verb) {
        $router = $request->getRouter();
        import('lib.pkp.classes.linkAction.request.AjaxModal');
        return array_merge(
                $this->getEnabled() ? array(
            new LinkAction(
                    'settings',
                    new AjaxModal(
                            $router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
                            $this->getDisplayName()
                    ),
                    __('manager.plugins.settings'),
                    null
            ),
                ) : array(),
                parent::getActions($request, $verb)
        );
    }

    /**
     * @copydoc Plugin::manage()
     */
    function manage($args, $request) {
        switch ($request->getUserVar('verb')) {
            case 'settings':
                $context = $request->getContext();

                $templateMgr = TemplateManager::getManager($request);
                $templateMgr->registerPlugin('function', 'plugin_url', array($this, 'smartyPluginUrl'));

                $form = new OpenAIREBrokerServiceSettingsForm($this, $context);

                if ($request->getUserVar('save')) {
                    $form->readInputData();
                    if ($form->validate()) {
                        $form->execute();
                        return new JSONMessage(true);
                    }
                } else {
                    $form->initData();
                }
                return new JSONMessage(true, $form->fetch($request));
        }
        return parent::manage($args, $request);
    }
}
