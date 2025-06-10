<?php

/**
 * @file plugins/generic/openAIREBrokerService/controllers/grid/OpenAIREBrokerServiceGridHandler.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OpenAIREBrokerServiceGridHandler
 * @ingroup plugins_generic_openAIREBrokerService
 *
 * @brief Handle OpenAIREBrokerService grid requests.
 */
import('lib.pkp.classes.controllers.grid.GridHandler');
import('plugins.generic.openAIREBrokerService.controllers.grid.OpenAIREBrokerServiceGridCellProvider');
import('plugins.generic.openAIREBrokerService.classes.OpenAIREBrokerServiceEnrichments');

class OpenAIREBrokerServiceGridHandler extends GridHandler {

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

        $submission = $this->getSubmission();
        $submissionId = $submission->getId();

        // Set the grid details.
        $this->setTitle('plugins.generic.openAIREBrokerService');
        $this->setEmptyRowText('plugins.generic.openAIREBrokerService.noData');

        // Get the items and add the data to the grid
        $openAIREBrokerServiceEnrichments = new OpenAIREBrokerServiceEnrichments();
        $articleEnrichments = $openAIREBrokerServiceEnrichments->articleEnrichments($submissionId);

        $gridData = array();
        foreach ($articleEnrichments as $submissionId => $articleEnrichmentById) {
            foreach($articleEnrichmentById as $articleEnrichment){
                $gridData[] = array(
                    'enrichmentsTopic' => $articleEnrichment['enrichmentsTopic'],                
                    'enrichmentsTrust' => $articleEnrichment['enrichmentsTrust'],
                    'enrichmentsMessage' => $articleEnrichment['enrichmentsMessage']
                );
            }
        }

        $this->setGridDataElements($gridData);

        $this->setReadOnly(true);

        // Columns
        $cellProvider = new OpenAIREBrokerServiceGridCellProvider();
        $this->addColumn(new GridColumn(
                        'enrichmentsTopic',
                        'plugins.generic.openAIREBrokerService.topic',
                        null,
                        'controllers/grid/gridCell.tpl',
                        $cellProvider
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
                        array('html' => true,'alignment' => COLUMN_ALIGNMENT_LEFT,
				'width' => 100)
        ));
    }

}

?>