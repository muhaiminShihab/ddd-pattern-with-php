<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Meta;

use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingLlmApiKeyType;

/**
 * Class PromptSettingTypes
 */
final class PromptSettingTypes {
    /**
     * 種別値一覧取得
     * @return array enum配列
     */
    public static function get(): array {
        return [
            'llm_api_key_type' => PromptSettingLlmApiKeyType::getValues(PromptSettingLlmApiKeyType::class),
        ];
    }
}
