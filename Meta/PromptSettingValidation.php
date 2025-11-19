<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018 Brisys. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Meta;

use Project\Biztel\Bases\Meta\Validations\BaseValidations;
use Project\Biztel\Bases\Meta\Validations\EntityProperty;
use Project\Biztel\Bases\ValueObjects\Shares\Memo;
use Project\Biztel\Models\AzureOpenAiApiKey\ValueObjects\AzureOpenAiApiKeyId;
use Project\Biztel\Models\InHouseLlmModel\ValueObjects\InHouseLlmModelId;
use Project\Biztel\Models\OpenAiApiKey\ValueObjects\OpenAiApiKeyId;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingDetailSetting;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingLlmApiKeyType;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingMapPrompt;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingName;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingOrderNo;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingPrompt;

/**
 * Class OpenAiPromptSettingValidation
 */
final class PromptSettingValidation extends BaseValidations {
    /**
     * @return array
     */
    protected function getProperties(): array {
        return [
            new EntityProperty('name', PromptSettingName::class),
            new EntityProperty('memo', Memo::class),
            new EntityProperty('order_no', PromptSettingOrderNo::class),
            new EntityProperty('llm_api_key_type', PromptSettingLlmApiKeyType::class),
            new EntityProperty('open_ai_api_key_id', OpenAiApiKeyId::class),
            new EntityProperty('azure_open_ai_api_key_id', AzureOpenAiApiKeyId::class),
            new EntityProperty('in_house_llm_model_id', InHouseLlmModelId::class),
            new EntityProperty('prompt', PromptSettingPrompt::class),
            new EntityProperty('detail_setting', PromptSettingDetailSetting::class),
            new EntityProperty('map_prompt', PromptSettingMapPrompt::class),
        ];
    }
}
