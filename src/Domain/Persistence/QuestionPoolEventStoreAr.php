<?php
declare(strict_types = 1);

namespace srag\asq\QuestionPool\Domain\Persistence;

use srag\CQRS\Event\AbstractStoredEvent;

/**
 * Class QuestionPoolEventStoreAr
 *
 * @package srag\asq\QuestionPool
 *
 * @author studer + raimann ag - Team Core 2 <al@studer-raimann.ch>
 */
class QuestionPoolEventStoreAr extends AbstractStoredEvent
{
    const STORAGE_NAME = "asq_qp_es";

    /**
     * @return string
     */
    public static function returnDbTableName()
    {
        return self::STORAGE_NAME;
    }
}
