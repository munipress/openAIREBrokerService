<?php

/**
 * @file OpenAIREBrokerServiceSettingsForm.inc.php
 *
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class OpenAIREBrokerServiceSettingsForm
 * @ingroup plugins_generic_openAIREBrokerService
 *
 * @brief Form for journal managers to modify OpenAIRE Broker Service plugin settings
 */

// $Id$


import('lib.pkp.classes.form.Form');

class OpenAIREBrokerServiceSettingsForm extends Form {

	/** @var int */
	var $_contextId;

	/** @var object */
	var $_plugin;
        
        /** @var context **/
        var $_context;
        
	/**
	 * Constructor
	 * @param $plugin object
	 * @param $contextId int
	 */
	function __construct($plugin, $context) {
		$this->_plugin = $plugin;
                $this->_context = $context;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(		
                    'enrich_more_openaccess_version' => $this->_context->getSetting('enrich_more_openaccess_version'),
                    'enrich_more_pid' => $this->_context->getSetting('enrich_more_pid'),
                    'enrich_missing_author_orcid' => $this->_context->getSetting('enrich_missing_author_orcid'),
                    'enrich_missing_pid' => $this->_context->getSetting('enrich_missing_pid'),
                    'enrich_missing_abstract' => $this->_context->getSetting('enrich_missing_abstract'),
                    'enrich_missing_subject_ddc' => $this->_context->getSetting('enrich_missing_subject_ddc'),
                    'enrich_more_subject_ddc' => $this->_context->getSetting('enrich_more_subject_ddc'),
                    'enrich_missing_subject_jel' => $this->_context->getSetting('enrich_missing_subject_jel'),
                    'enrich_more_subject_jel' => $this->_context->getSetting('enrich_more_subject_jel'),
                    'enrich_missing_publication_date' => $this->_context->getSetting('enrich_missing_publication_date'),
                    'enrich_missing_openaccess_version' => $this->_context->getSetting('enrich_missing_openaccess_version'),
                    'enrich_missing_subject_acm' => $this->_context->getSetting('enrich_missing_subject_acm'),
                    'enrich_more_subject_acm' => $this->_context->getSetting('enrich_more_subject_acm'),
                    'enrich_missing_project' => $this->_context->getSetting('enrich_missing_project'),
                    'enrich_missing_subject_mesheuropmc' => $this->_context->getSetting('enrich_missing_subject_mesheuropmc'),
                    'enrich_more_subject_mesheuropmc' => $this->_context->getSetting('enrich_more_subject_mesheuropmc'),
                    'enrich_missing_subject_arxiv' => $this->_context->getSetting('enrich_missing_subject_arxiv'),
                    'enrich_more_subject_arxiv' => $this->_context->getSetting('enrich_more_subject_arxiv')
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('enrich_more_openaccess_version','enrich_more_pid','enrich_missing_author_orcid','enrich_missing_pid','enrich_missing_abstract','enrich_missing_subject_ddc',
                                            'enrich_more_subject_ddc','enrich_missing_subject_jel','enrich_more_subject_jel','enrich_missing_publication_date','enrich_missing_openaccess_version',
                                            'enrich_missing_subject_acm','enrich_more_subject_acm','enrich_missing_project','enrich_missing_subject_mesheuropmc','enrich_more_subject_mesheuropmc',
                                            'enrich_missing_subject_arxiv','enrich_more_subject_arxiv'));
	}

        /**
	 * @copydoc Form::fetch()
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
            
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request, $template, $display);
	}
        
        /**
	 * @copydoc Form::execute()
	 */
	function execute(...$functionArgs) {
                $this->_context->updateSetting('enrich_more_openaccess_version', trim($this->getData('enrich_more_openaccess_version'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_more_pid', trim($this->getData('enrich_more_pid'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_author_orcid', trim($this->getData('enrich_missing_author_orcid'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_pid', trim($this->getData('enrich_missing_pid'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_abstract', trim($this->getData('enrich_missing_abstract'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_subject_ddc', trim($this->getData('enrich_missing_subject_ddc'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_more_subject_ddc', trim($this->getData('enrich_more_subject_ddc'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_subject_jel', trim($this->getData('enrich_missing_subject_jel'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_more_subject_jel', trim($this->getData('enrich_more_subject_jel'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_publication_date', trim($this->getData('enrich_missing_publication_date'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_openaccess_version', trim($this->getData('enrich_missing_openaccess_version'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_subject_acm', trim($this->getData('enrich_missing_subject_acm'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_more_subject_acm', trim($this->getData('enrich_more_subject_acm'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_project', trim($this->getData('enrich_missing_project'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_subject_mesheuropmc', trim($this->getData('enrich_missing_subject_mesheuropmc'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_more_subject_mesheuropmc', trim($this->getData('enrich_more_subject_mesheuropmc'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_missing_subject_arxiv', trim($this->getData('enrich_missing_subject_arxiv'), "\"\';"), 'string');
                $this->_context->updateSetting('enrich_more_subject_arxiv', trim($this->getData('enrich_more_subject_arxiv'), "\"\';"), 'string');
		parent::execute(...$functionArgs);
	}
}

?>
