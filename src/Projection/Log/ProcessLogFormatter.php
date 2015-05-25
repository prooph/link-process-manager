<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 5/25/15 - 7:26 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Log;

use Prooph\Link\Application\Projection\ProcessingConfig;
use Zend\I18n\Translator\TranslatorInterface;

/**
 * Class ProcessLogFormatter
 *
 * @package Prooph\Link\ProcessManager\Projection\Log
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class ProcessLogFormatter 
{
    /**
     * @param array $processLogs
     * @param ProcessingConfig $config
     * @param TranslatorInterface $translator
     */
    public static function formatProcessLogs(array &$processLogs, ProcessingConfig $config, TranslatorInterface $translator)
    {
        $processDefinitions = $config->getProcessDefinitions();
        foreach ($processLogs as &$processLog) {
            self::addPrcessName($processLog, $processDefinitions, $translator);
        }
    }

    /**
     * @param array $processLog
     * @param ProcessingConfig $config
     * @param TranslatorInterface $translator
     */
    public static function formatProcessLog(array &$processLog, ProcessingConfig $config, TranslatorInterface $translator)
    {
        $processDefinitions = $config->getProcessDefinitions();
        self::addPrcessName($processLog, $processDefinitions, $translator);
    }

    /**
     * @param array $processLog
     * @param array $processDefinitions
     * @param TranslatorInterface $translator
     */
    private static function addPrcessName(array &$processLog, array &$processDefinitions, TranslatorInterface $translator)
    {
        if (isset($processDefinitions[$processLog['start_message']])) {

            $processLog['process_name'] = $processDefinitions[$processLog['start_message']]['name'];
        } else {
            $processLog['process_name'] = $translator->translate('Unknown');
        }
    }
} 