<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018 Brisys. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Factories;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\Utils\Valid;
use Project\Biztel\Bases\Validates\ExceptionsBag;
use Project\Biztel\Bases\ValueObjects\Shares\Memo;
use Project\Biztel\Bases\ValueObjects\Shares\Version;
use Project\Biztel\Models\AzureOpenAiApiKey\ValueObjects\AzureOpenAiApiKeyId;
use Project\Biztel\Models\InHouseLlmModel\ValueObjects\InHouseLlmModelId;
use Project\Biztel\Models\OpenAiApiKey\ValueObjects\OpenAiApiKeyId;
use Project\Biztel\Models\PromptSetting\PromptSetting;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingDetailSetting;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingLlmApiKeyType;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingMapPrompt;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingName;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingOrderNo;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingPrompt;

/**
 * trait OpenAiPromptSettingFactory
 */
trait PromptSettingFactory {
    /**
     * @param  Params        $params
     * @return PromptSetting
     */
    public static function new(Params $params): PromptSetting {
        return self::buildEntity($params);
    }

    /**
     * @param  Params        $params
     * @return PromptSetting
     */
    public function update(Params $params): PromptSetting {
        $self = $this;
        return self::buildEntity($params, $self);
    }

    /**
     * @param  Params             $params
     * @param  PromptSetting|null $entity
     * @return PromptSetting
     */
    protected static function buildEntity(Params $params, ?PromptSetting $entity = null): PromptSetting {
        //複数例外を保持する
        $exceptions = new ExceptionsBag();
        //エンティティ状態
        $is_new = $entity === null;

        //ユーザ入力値を評価
        /** @var PromptSettingName $name */
        $name = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getName() : null;
                $name = PromptSettingName::new($params, $is_new, $current, 'name');
                Valid::notEmpty($name, 'name');
                return $name;
            }
        );

        /** @var PromptSettingOrderNo|null $order_no */
        $order_no = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getOrderNo() : null;
                return PromptSettingOrderNo::new($params, $is_new, $current, 'order_no');
            }
        );

        /** @var PromptSettingLlmApiKeyType  $llm_api_key_type */
        $llm_api_key_type = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getLlmApiKeyType() : null;
                $llm_api_key_type = PromptSettingLlmApiKeyType::new($params, $is_new, $current, 'llm_api_key_type');
                Valid::notEmpty($llm_api_key_type, 'llm_api_key_type');
                return $llm_api_key_type;
            }
        );

        /** @var OpenAiApiKeyId|null $open_ai_api_key_id */
        $open_ai_api_key_id = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getOpenAiApiKeyId() : null;
                return OpenAiApiKeyId::new($params, $is_new, $current, 'open_ai_api_key_id', true);
            }
        );

        /** @var AzureOpenAiApiKeyId|null $azure_open_ai_api_key_id */
        $azure_open_ai_api_key_id = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getAzureOpenAiApiKeyId() : null;
                return AzureOpenAiApiKeyId::new($params, $is_new, $current, 'azure_open_ai_api_key_id', true);
            }
        );

        /** @var InHouseLlmModelId|null $in_house_llm_model_id */
        $in_house_llm_model_id = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getInHouseLlmModelId() : null;
                return InHouseLlmModelId::new($params, $is_new, $current, 'in_house_llm_model_id', true);
            }
        );

        /** @var PromptSettingPrompt $prompt */
        $prompt = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getPrompt() : null;
                $prompt = PromptSettingPrompt::new($params, $is_new, $current, 'prompt');
                Valid::notEmpty($prompt, 'prompt');
                return $prompt;
            }
        );

        /**
         * @var PromptSettingDetailSetting $detail_setting
         */
        $detail_setting = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getDetailSetting() : null;
                $detail_setting = PromptSettingDetailSetting::new($params, $is_new, $current, 'detail_setting');
                Valid::notEmpty($detail_setting, 'detail_setting');
                return $detail_setting;
            }
        );

        /**
         * @var PromptSettingMapPrompt|null $map_prompt
         */
        $map_prompt = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getMapPrompt() : null;
                return PromptSettingMapPrompt::new($params, $is_new, $current, 'map_prompt', true);
            }
        );

        /** @var Memo|null $memo */
        $memo = $exceptions->tryFunction(
            static function () use ($entity, $params, $is_new) {
                $current = $entity ? $entity->getMemo() : null;
                return Memo::new($params, $is_new, $current, 'memo', true);
            }
        );

        //ユーザ入力値に問題がある場合は例外を投げる
        if ($exceptions->hasExceptions()) {
            $exceptions->riseException();
        }

        //----------------------------------------------

        //エンティティ生成
        if ($is_new) {
            return new PromptSetting(
                $name,
                $prompt,
                $detail_setting,
                $llm_api_key_type,
                $open_ai_api_key_id,
                $azure_open_ai_api_key_id,
                $in_house_llm_model_id,
                $map_prompt,
                $order_no,
                $memo
            );
        }

        //----------------------------------------------

        $entity
            ->setName($name)
            ->setOrderNo($order_no)
            ->setLlmApiKeyType($llm_api_key_type)
            ->setOpenAiApiKeyId($open_ai_api_key_id)
            ->setAzureOpenAiApiKeyId($azure_open_ai_api_key_id)
            ->setInHouseLlmModelId($in_house_llm_model_id)
            ->setPrompt($prompt)
            ->setDetailSetting($detail_setting)
            ->setMapPrompt($map_prompt)
            ->setMemo($memo);

        //----------------------------------------------

        //楽観ロック検知用のプロパティ
        //DBに保存するデータではない
        $entity->setVersion(Version::new($params));

        return $entity;
    }
}
