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
import('lib.pkp.classes.plugins.GenericPlugin');

/*

  enrich_more_openaccess_version
  enrich_more_pid
  enrich_missing_author_orcid
  enrich_missing_pid
  enrich_missing_abstract
  enrich_missing_subject_ddc
  enrich_more_subject_ddc
  enrich_missing_subject_jel
  enrich_more_subject_jel
  enrich_missing_publication_date
  enrich_missing_openaccess_version
  enrich_missing_subject_acm
  enrich_more_subject_acm
  enrich_missing_project
  enrich_missing_subject_mesheuropmc
  enrich_more_subject_mesheuropmc
  enrich_missing_subject_arxiv
  enrich_more_subject_arxiv



 */

class OpenAIREBrokerServicePlugin extends GenericPlugin {

    /**
     * @copydoc Plugin::register()
     */
    function register($category, $path, $mainContextId = null) {
        $success = parent::register($category, $path, $mainContextId);
        if ($success && $this->getEnabled($mainContextId)) {

            HookRegistry::register('Schema::get::context', [$this, 'addToSchema']);
            HookRegistry::register('Template::Settings::website', array($this, 'callbackShowWebsiteSettingsTabs'));
            HookRegistry::register('Template::Workflow::Publication', array($this, 'addToPublicationForms'));
            
            HookRegistry::register('LoadComponentHandler', array($this, 'setupGridHandler'));
            HookRegistry::register('LoadComponentHandler', array($this, 'setupContextGridHandler'));
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
        $schema->properties->enrich_more_openaccess_version = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_more_pid = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_author_orcid = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_pid = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_abstract = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_subject_ddc = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_more_subject_ddc = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_subject_jel = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_more_subject_jel = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_publication_date = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_openaccess_version = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_subject_acm = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_more_subject_acm = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_project = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_subject_mesheuropmc = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_more_subject_mesheuropmc = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_missing_subject_arxiv = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
        $schema->properties->enrich_more_subject_arxiv = (object) [
                    'type' => 'string',
                    'apiSummary' => true,
                    'multilingual' => false,
                    'validation' => ['nullable']
        ];
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
        if ($component == 'plugins.generic.openAIREBrokerService.controllers.grid.OpenAIREBrokerServiceContextGridHandler') {

            import($component);
            OpenAIREBrokerServiceContextGridHandler::setPlugin($this);
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
        if ($component == 'plugins.generic.openAIREBrokerService.controllers.grid.OpenAIREBrokerServiceGridHandler') {

            import($component);
            OpenAIREBrokerServiceGridHandler::setPlugin($this);
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
        $submission = $smarty->get_template_vars('submission');
        $smarty->assign([
            'submissionId' => $submission->getId(),
        ]);

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

                AppLocale::requireComponents(LOCALE_COMPONENT_APP_COMMON, LOCALE_COMPONENT_PKP_MANAGER);
                $templateMgr = TemplateManager::getManager($request);
                $templateMgr->registerPlugin('function', 'plugin_url', array($this, 'smartyPluginUrl'));

                $this->import('OpenAIREBrokerServiceSettingsForm');
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

    /**
     * Insert Journal enrichments to Publication part of workflow
     */
    function contextEnrichments($hookName, $params) {
//		$smarty =& $params[1];
//		$output =& $params[2];
//		$templateMgr =& TemplateManager::getManager();
//                $request = Application::get()->getRequest();
//                
//		$currentJournal = $smarty->getTemplateVars('currentJournal');
//		
//		$article = $smarty->getTemplateVars('article');
//		if (!empty($currentJournal)) {
//			
//			if ($request->getRequestedPage() == 'article' && $article) {
//				
//				$journal =& $request->getJournal();
//				$journalId = $journal->getId();
//				
//				$doi = $article->getStoredPubId('doi');
//                                if(trim($doi) == "") return false;
//				$user = $this->getSetting($journalId, 'cb_user');
//				$pass = $this->getSetting($journalId, 'cb_pass');
//				
//				$citedByList = $this->GetXML($doi, $user, $pass);
//                                if (sizeof($citedByList)==0) {
//                                    $citedByCount = 0;
//                                    $citedByListOutput = "";
//                                } else{
//                                    $citedByCount = $this->GetCount($citedByList);
//                                    $citedByListOutput = $this->CreateList($citedByList);
//                                }
//                                $smarty->assign('citedByCount', $citedByCount);
//                                $smarty->assign('citedByList', $citedByListOutput);                                
//                                $output .= $smarty->fetch($this->getTemplateResource('articleCitedBy.tpl'));
//			}
//		}
        return false;
    }

    /*
     * Generates and shows output
     * @param $array array
     * @return string
     */

    function CreateList($array) {
        
    }

}
