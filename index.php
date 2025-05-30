<?php

/**
 * @defgroup plugins_generic_openAIREBrokerService OpenAIRE Broker Service Plugin
 */
 
/**
 * @file plugins/generic/openAIREBrokerService/index.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_reports_openAIREBrokerService
 * @brief Wrapper for openAIREBrokerService plugin.
 *
 */
require_once('OpenAIREBrokerServicePlugin.inc.php');

return new OpenAIREBrokerServicePlugin();


