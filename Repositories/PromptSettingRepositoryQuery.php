<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Repositories;

use Database\TableDefinitions\Biztel\PromptSettingTableDefinition;
use Project\Biztel\Models\AzureOpenAiApiKey\AzureOpenAiApiKey;
use Project\Biztel\Models\InHouseLlmModel\InHouseLlmModel;
use Project\Biztel\Models\OpenAiApiKey\OpenAiApiKey;
use Project\Biztel\Models\PromptSetting\Collections\PromptSettingCollection;
use Project\Biztel\Models\PromptSetting\Eloquents\PromptSettingEloquent;
use PromptSettingMigration390rc000;

/**
 * trait OpenAiPromptSettingRepositoryQuery
 */
trait PromptSettingRepositoryQuery {
    /**
     * @param  OpenAiApiKey            $apiKey
     * @return PromptSettingCollection
     */
    public static function findByOpenAiApiKey(OpenAiApiKey $apiKey): PromptSettingCollection {
        $eloquent = PromptSettingEloquent::builder()
            ->where(PromptSettingTableDefinition::FK_OPEN_AI_API_KEY, $apiKey->getId()->getValue())
            ->get();

        return new PromptSettingCollection($eloquent);
    }

    /**
     * @param  AzureOpenAiApiKey       $apiKey
     * @return PromptSettingCollection
     */
    public static function findByAzureOpenAiApiKey(AzureOpenAiApiKey $apiKey): PromptSettingCollection {
        $eloquent = PromptSettingEloquent::builder()
            ->where(PromptSettingTableDefinition::FK_AZURE_OPEN_AI_API_KEY, $apiKey->getId()->getValue())
            ->get();

        return new PromptSettingCollection($eloquent);
    }

    /**
     * @param  InHouseLlmModel         $inHouseLlmModel
     * @return PromptSettingCollection
     */
    public static function findByInHouseLlmModel(InHouseLlmModel $inHouseLlmModel): PromptSettingCollection {
        $eloquent = PromptSettingEloquent::builder()
            ->where(PromptSettingTableDefinition::FK_IN_HOUSE_LLM_MODEL, $inHouseLlmModel->getId()->getValue())
            ->get();

        return new PromptSettingCollection($eloquent);
    }

    /**
     * 最新の表示順を取得
     * @return int
     * @throws \ReflectionException
     */
    public static function findLatestOrderNo(): int {
        /** @var PromptSettingEloquent $eloquent */
        $eloquent = PromptSettingEloquent::builder(true)
            ->orderBy('order_no', 'desc')
            ->first();
        return $eloquent
            ? $eloquent->toEntity()->getOrderNo()->getNextOrderNo()
            : 1;
    }

    /**
     * 全件取得(order_noによる昇順)
     * @return PromptSettingCollection
     */
    public static function getAllSorted(): PromptSettingCollection {
        return new PromptSettingCollection(
            PromptSettingEloquent::eagerBuilder(true)
                ->orderBy(PromptSettingMigration390rc000::COLUMN_NAME_ORDER_NO, 'asc')
                ->get()
        );
    }
}
