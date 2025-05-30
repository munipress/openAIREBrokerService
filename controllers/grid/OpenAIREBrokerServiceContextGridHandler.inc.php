<?php

/**
 * @file plugins/generic/openAIREBrokerService/controllers/grid/OpenAIREBrokerServiceContextGridHandler.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAIREBrokerServiceContextGridHandlers
 * @ingroup plugins_generic_openAIREBrokerService
 *
 * @brief Handle OpenAIREBrokerService Contextg grid requests.
 */
import('lib.pkp.classes.controllers.grid.GridHandler');
import('plugins.generic.openAIREBrokerService.controllers.grid.OpenAIREBrokerServiceContextGridCellProvider');
import('plugins.generic.openAIREBrokerService.classes.OpenAIREBrokerServiceEnrichments');

class OpenAIREBrokerServiceContextGridHandler extends GridHandler {

    static $plugin;

    /** @var boolean */
    var $_readOnly;

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();
        $this->addRoleAssignment(
                array(ROLE_ID_MANAGER, ROLE_ID_SUB_EDITOR),
                array('fetchGrid', 'fetchRow')
        );
    }

    //
    // Getters/Setters
    //

    /**
     * Set the Funder plugin.
     * @param $plugin FundingPlugin
     */
    static function setPlugin($plugin) {
        self::$plugin = $plugin;
    }

    /**
     * Get the submission associated with this grid.
     * @return Submission
     */
    function getSubmission() {
        return $this->getAuthorizedContextObject(ASSOC_TYPE_SUBMISSION);
    }

    /**
     * Get whether or not this grid should be 'read only'
     * @return boolean
     */
    function getReadOnly() {
        return $this->_readOnly;
    }

    /**
     * Set the boolean for 'read only' status
     * @param boolean
     */
    function setReadOnly($readOnly) {
        $this->_readOnly = $readOnly;
    }

    //
    // Overridden template methods
    //

    /**
     * @copydoc PKPHandler::authorize()
     */
    function authorize($request, &$args, $roleAssignments) {
        import('lib.pkp.classes.security.authorization.SubmissionAccessPolicy');
        $this->addPolicy(new SubmissionAccessPolicy($request, $args, $roleAssignments));
        return parent::authorize($request, $args, $roleAssignments);
    }

    /**
     * @copydoc Gridhandler::initialize()
     */
    function initialize($request, $args = null) {
        parent::initialize($request, $args);
        $context = $request->getContext();

        // Load submission-specific translations.
        AppLocale::requireComponents(
                LOCALE_COMPONENT_APP_SUBMISSION, // title filter
                LOCALE_COMPONENT_PKP_SUBMISSION, // authors filter
                LOCALE_COMPONENT_APP_MANAGER
        );

        // Set the grid details.
        $this->setTitle('plugins.generic.openAIREBrokerService');
        $this->setEmptyRowText('plugins.generic.openAIREBrokerService.noData');

        // Get the items and add the data to the grid
        $openAIREBrokerServiceEnrichments = new OpenAIREBrokerServiceEnrichments();
        $contextEnrichments = $openAIREBrokerServiceEnrichments->contextEnrichments();

        $gridData = $this->setGridDataEnrichments($contextEnrichments, $context);

        $this->setGridDataElements($gridData);

        $this->setReadOnly(true);

        // Columns
        $cellProvider = new OpenAIREBrokerServiceContextGridCellProvider();
        $this->addColumn(
                new GridColumn(
                        'id',
                        null,
                        __('common.id'),
                        'controllers/grid/gridCell.tpl',
                        $cellProvider,
                        array('alignment' => COLUMN_ALIGNMENT_LEFT,
                    'width' => 5)
                )
        );
        $this->addColumn(
                new GridColumn(
                        'title',
                        'grid.submission.itemTitle',
                        null,
                        null,
                        $cellProvider,
                        array('html' => true,
                    'alignment' => COLUMN_ALIGNMENT_LEFT, 'width' => 25)
                )
        );
        $this->addColumn(
                new GridColumn(
                        'issue',
                        'issue.issue',
                        null,
                        null,
                        $cellProvider,
                        array('alignment' => COLUMN_ALIGNMENT_LEFT,
                    'width' => 20)
                )
        );
        $this->addColumn(new GridColumn(
                        'enrichmentsTopic',
                        'plugins.generic.openAIREBrokerService.topic',
                        null,
                        'controllers/grid/gridCell.tpl',
                        $cellProvider,
                        array('alignment' => COLUMN_ALIGNMENT_LEFT,
                    'width' => 10)
        ));
        $this->addColumn(new GridColumn(
                        'enrichmentsTrust',
                        'plugins.generic.openAIREBrokerService.trust',
                        null,
                        'controllers/grid/gridCell.tpl',
                        $cellProvider
        ));
        $this->addColumn(new GridColumn(
                        'enrichmentsMessage',
                        'plugins.generic.openAIREBrokerService.message',
                        null,
                        'controllers/grid/gridCell.tpl',
                        $cellProvider,
                        array('html' => true, 'alignment' => COLUMN_ALIGNMENT_LEFT, 'width' => 20)
        ));
    }

    function setGridDataEnrichments($contextEnrichments, $context) {
        $gridData = array();
        foreach ($contextEnrichments as $submissionId => $articleEnrichmentsById) {
            foreach ($articleEnrichmentsById as $articleEnrichment) {
                $submissionDao = DAORegistry::getDAO('SubmissionDAO');
                $submission = $submissionDao->getById($submissionId);
                if (empty($title))
                    $title = __('common.untitled');

                if ($submission && !empty($submission->getCurrentPublication())) {
                    $authorsInTitle = $submission->getShortAuthorString();
                    $title = $submission->getCurrentPublication()->getLocalizedData('title');
                    $title = $authorsInTitle . '; ' . $title;
                } else {
                    continue;
                }

                $issueId = $submission->getCurrentPublication()->getData('issueId');
                $issueDao = DAORegistry::getDAO('IssueDAO'); /* @var $issueDao IssueDAO */
                $issue = $issueDao->getById($issueId, $context->getId());
                if($issue){
                    $issueIdentification = htmlspecialchars($issue->getIssueIdentification());
                } else{
                    $issueIdentification = "";
                }
                
                $gridData[] = array(
                    'id' => $submissionId,
                    'title' => htmlspecialchars($title),
                    'issue' => $issueIdentification,
                    'enrichmentsTopic' => $articleEnrichment['enrichmentsTopic'],
                    'enrichmentsTrust' => $articleEnrichment['enrichmentsTrust'],
                    'enrichmentsMessage' => $articleEnrichment['enrichmentsMessage']
                );
            }
        }
        return $gridData;
    }

    //
    // Implemented methods from GridHandler.
    //

    /**
     * @copydoc GridHandler::initFeatures()
     */
//    function initFeatures($request, $args) {
//        import('lib.pkp.classes.controllers.grid.feature.PagingFeature');
//        return array(new PagingFeature());
//    }

}

?>