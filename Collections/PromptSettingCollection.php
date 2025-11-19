<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018 Brisys. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Collections;

use Project\Biztel\Bases\Collections\AbstractCollection;
use Project\Biztel\Models\PromptSetting\Eloquents\PromptSettingEloquent;

/**
 * Class OpenAiPromptSettingCollection
 */
final class PromptSettingCollection extends AbstractCollection {
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function isSatisfied(): void {
        $this->collection->each(
            static function (PromptSettingEloquent $eloquent) {
                $eloquent->toEntity()->isSatisfied();
            }
        );
    }
}
