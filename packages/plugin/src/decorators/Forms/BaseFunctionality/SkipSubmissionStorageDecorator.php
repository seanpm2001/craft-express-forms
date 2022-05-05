<?php

namespace Solspace\ExpressForms\decorators\Forms\BaseFunctionality;

use Solspace\ExpressForms\decorators\AbstractDecorator;
use Solspace\ExpressForms\events\submissions\SaveSubmissionEvent;
use Solspace\ExpressForms\services\Submissions;

class SkipSubmissionStorageDecorator extends AbstractDecorator
{
    public function getEventListenerList(): array
    {
        return [
            [Submissions::class, Submissions::EVENT_BEFORE_SAVE_SUBMISSION, [$this, 'skipSubmissionSaveIfOpted']],
        ];
    }

    public function skipSubmissionSaveIfOpted(SaveSubmissionEvent $event): void
    {
        if (!$event->getSubmission()->getForm()->isSaveSubmissions()) {
            $event->isValid = false;
        }
    }
}
