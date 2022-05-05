<?php

namespace Solspace\ExpressForms\fields;

use yii\db\Schema;

class Textarea extends BaseField
{
    public function getType(): string
    {
        return self::TYPE_TEXTAREA;
    }

    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }
}
