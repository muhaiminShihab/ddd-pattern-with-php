<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018 Brisys. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Functions;

use JsonException;
use Project\Biztel\Bases\Exceptions\AzureOpenAIKeyValidateException;
use Project\Biztel\Bases\Exceptions\InvalidCodeException;
use Project\Biztel\Bases\Exceptions\OpenAIKeyValidateException;
use Project\Biztel\Library\AzureOpenAIClient\Facades\AzureOpenAIClientFacade;
use Project\Biztel\Library\OpenAIClient\Facades\OpenAIClientFacade;
use Project\Biztel\Models\AzureOpenAiApiVersion\AzureOpenAiApiVersion;

trait PromptSettingFunction {
    /**
     * LLM種別毎のValidation処理
     * @return void
     * @throws AzureOpenAIKeyValidateException | InvalidCodeException | OpenAIKeyValidateException | JsonException
     * @noinspection ReturnTypeCanBeDeclaredInspection
     * @noinspection MissingReturnTypeInspection
     */
    final public function checkLlmApiKey() {
        $llm_api_key_type = $this->getLlmApiKeyType();
        if ($llm_api_key_type->isOpenAi()) {
            $open_ai_api_key = $this->getOpenAiApiKey();
            if ($open_ai_api_key === null) {
                throw InvalidCodeException::NO_WAY();
            }
            OpenAIClientFacade::checkModelName(
                $open_ai_api_key->getApiKey(),
                $open_ai_api_key->getModelName(),
                $open_ai_api_key->getOrganizationId(),
            );
        } elseif ($llm_api_key_type->isAzureOpenAi()) {
            $azure_open_ai_api_key = $this->getAzureOpenAiApiKey();
            if ($azure_open_ai_api_key === null) {
                throw InvalidCodeException::NO_WAY();
            }
            AzureOpenAIClientFacade::checkApiCredentials(
                $azure_open_ai_api_key->getInstanceName(),
                $azure_open_ai_api_key->getApiKey(),
                AzureOpenAiApiVersion::DEFAULT()->getApiVersion(),
            );
        } elseif ($llm_api_key_type->isInHouseLlmModel()) {
            $in_house_llm_model = $this->getInHouseLlmModel();
            if ($in_house_llm_model === null) {
                throw InvalidCodeException::NO_WAY();
            }
        } else {
            // 上記種別以外はあり得ない
            throw InvalidCodeException::NO_WAY();
        }
    }
}
