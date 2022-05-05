<?php

namespace Solspace\ExpressForms\factories;

use Solspace\ExpressForms\events\forms\FormBuildFromArrayEvent;
use Solspace\ExpressForms\models\Form;
use Symfony\Component\PropertyAccess\PropertyAccess;
use yii\base\Event;

class FormFactory
{
    public const EVENT_BEFORE_BUILD_FROM_ARRAY = 'beforeBuildFromArray';
    public const EVENT_AFTER_BUILD_FROM_ARRAY = 'afterBuildFromArray';

    public function __construct(private IntegrationMappingFactory $mappingFactory)
    {
    }

    public function populateFromArray(Form $form, array $data): ?Form
    {
        $event = new FormBuildFromArrayEvent(['form' => $form, 'data' => $data]);
        Event::trigger($this, self::EVENT_BEFORE_BUILD_FROM_ARRAY, $event);

        if (!$event->isValid) {
            return null;
        }

        $integrations = $data['integrations'] ?? [];

        unset($data['fields'], $data['integrations']);

        $propertyAccess = PropertyAccess::createPropertyAccessor();
        foreach ($data as $key => $value) {
            if ($propertyAccess->isWritable($form, $key)) {
                $propertyAccess->setValue($form, $key, $value);
            }
        }

        $form->setIntegrations($this->mappingFactory->fromArray($form, $integrations));

        $event = new FormBuildFromArrayEvent(['form' => $form]);
        Event::trigger($this, self::EVENT_AFTER_BUILD_FROM_ARRAY, $event);

        if (!$event->isValid) {
            return null;
        }

        return $form;
    }
}
