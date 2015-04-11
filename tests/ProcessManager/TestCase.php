<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 7:47 PM
 */
namespace ProophTest\Link\ProcessManager;

use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateRootDecorator;
use Prooph\Link\ProcessManager\Model\MessageHandler\MessageHandlerId;
use Prooph\Link\ProcessManager\Model\MessageHandler;
use Prooph\Link\ProcessManager\Model\ProcessingMetadata;
use Prooph\Processing\Processor\NodeName;
use ProophTest\Link\ProcessManager\Mock\ProcessingType\Article;
use ProophTest\Link\ProcessManager\Mock\ProcessingType\ArticleCollection;

/**
 * Class TestCase
 *
 * @package ProophTest\Link\ProcessManager
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AggregateRootDecorator
     */
    private $arTranslator;

    /**
     * @param bool $singleItemMode
     * @return MessageHandler
     */
    protected function getArticleExporterMessageHandler($singleItemMode = false)
    {
        $supportedProcessingType = $singleItemMode? Article::prototype() : ArticleCollection::prototype();

        return MessageHandler::fromDefinition(
            MessageHandlerId::generate(),
            'Article Exporter',
            NodeName::defaultName(),
            MessageHandler\HandlerType::connector(),
            MessageHandler\DataDirection::source(),
            MessageHandler\ProcessingTypes::support([$supportedProcessingType]),
            ProcessingMetadata::fromArray(['chunk_support' => true])
        );
    }

    /**
     * @param bool $singleItemMode
     * @return MessageHandler
     */
    protected function getArticleImporterMessageHandler($singleItemMode = false)
    {
        $supportedProcessingType = $singleItemMode? Article::prototype() : ArticleCollection::prototype();

        return MessageHandler::fromDefinition(
            MessageHandlerId::generate(),
            'Article Importer',
            NodeName::defaultName(),
            MessageHandler\HandlerType::connector(),
            MessageHandler\DataDirection::target(),
            MessageHandler\ProcessingTypes::support([$supportedProcessingType]),
            ProcessingMetadata::fromArray(['chunk_support' => true])
        );
    }

    /**
     * @param AggregateRoot $ar
     * @return \Prooph\EventSourcing\AggregateChanged[]
     */
    protected function extractRecordedEvents(AggregateRoot $ar)
    {
        return $this->getArTranslator()->extractRecordedEvents($ar);
    }

    /**
     * @return AggregateRootDecorator
     */
    private function getArTranslator()
    {
        if (is_null($this->arTranslator)) {
            $this->arTranslator = AggregateRootDecorator::newInstance();
        }

        return $this->arTranslator;
    }
} 