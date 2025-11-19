<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Eloquents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Project\Biztel\Bases\Eloquents\AbstractEloquent;
use Project\Biztel\Bases\Utils\EloquentToEntity;
use Project\Biztel\Bases\ValueObjects\Shares\Memo;
use Project\Biztel\Models\AzureOpenAiApiKey\Eloquents\AzureOpenAiApiKeyEloquent;
use Project\Biztel\Models\AzureOpenAiApiKey\ValueObjects\AzureOpenAiApiKeyId;
use Project\Biztel\Models\InHouseLlmModel\Eloquents\InHouseLlmModelEloquent;
use Project\Biztel\Models\InHouseLlmModel\ValueObjects\InHouseLlmModelId;
use Project\Biztel\Models\OpenAiApiKey\Eloquents\OpenAiApiKeyEloquent;
use Project\Biztel\Models\OpenAiApiKey\ValueObjects\OpenAiApiKeyId;
use Project\Biztel\Models\PromptSetting\PromptSetting;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingDetailSetting;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingId;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingLlmApiKeyType;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingMapPrompt;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingName;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingOrderNo;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingPrompt;
use PromptSettingMigration390rc000;
use ReflectionException;

/**
 * class PromptSettingEloquent
 *
 * @mixin IdeHelperPromptSettingEloquent
 */
final class PromptSettingEloquent extends AbstractEloquent {
    //Laravelへテーブル名を指示
    protected $table = PromptSettingMigration390rc000::TABLE_NAME;

    /**
     * @return PromptSetting
     * @throws ReflectionException
     */
    public function toEntity(): PromptSetting {
        $id                         = new PromptSettingId($this->id);
        $name                       = new PromptSettingName($this->name);
        $order_no                   = new PromptSettingOrderNo($this->order_no);
        $llm_api_key_type           = new PromptSettingLlmApiKeyType($this->llm_api_key_type);
        $open_ai_api_key_id         = $this->open_ai_api_key_id
            ? new OpenAiApiKeyId($this->open_ai_api_key_id)
            : null;
        $azure_open_ai_api_key_id = $this->azure_open_ai_api_key_id
            ? new AzureOpenAiApiKeyId($this->azure_open_ai_api_key_id)
            : null;
        $in_house_llm_model_id = $this->in_house_llm_model_id
            ? new InHouseLlmModelId($this->in_house_llm_model_id)
            : null;
        $prompt                     = new PromptSettingPrompt($this->prompt);
        $detail_setting             = new PromptSettingDetailSetting($this->detail_setting);
        $map_prompt                 = $this->map_prompt
            ? new PromptSettingMapPrompt($this->map_prompt)
            : null;
        $memo                       = $this->memo
            ? new Memo($this->memo)
            : null;

        //エンティティ変換
        /** @var PromptSetting $entity */
        $entity = (new EloquentToEntity($this, PromptSetting::class))
            ->set('id', $id)
            ->set('name', $name)
            ->set('order_no', $order_no)
            ->set('llm_api_key_type', $llm_api_key_type)
            ->set('open_ai_api_key_id', $open_ai_api_key_id)
            ->set('azure_open_ai_api_key_id', $azure_open_ai_api_key_id)
            ->set('in_house_llm_model_id', $in_house_llm_model_id)
            ->set('prompt', $prompt)
            ->set('detail_setting', $detail_setting)
            ->set('map_prompt', $map_prompt)
            ->set('memo', $memo)
            ->getInstance();

        return $entity;
    }

    /**
     * @param  PromptSettingId $id
     * @param  bool|null       $is_eager
     * @return self|null
     */
    public static function findEloquent(
        PromptSettingId $id,
        ?bool $is_eager = null
    ): ?self {
        $builder = $is_eager ? self::eagerBuilder() : self::builder();
        /** @var PromptSettingEloquent $eloquent */
        $eloquent = $builder->find($id->getValue());
        return $eloquent;
    }

    public static function eagerBuilder(bool $clear_order_by_settings = false): Builder {
        return self::builder($clear_order_by_settings)->with(
            //FK正引き
            'openAiApiKey',
            'azureOpenAiApiKey',
            'inHouseLlmModel'
        );
    }

    /**
     * @param  PromptSetting       $entity
     * @return PromptSetting
     * @throws ReflectionException
     */
    public static function saveEntity(
        PromptSetting $entity
    ): PromptSetting {
        //エンティティ保存
        $eloquent                                      = self::getInstance($entity);
        $eloquent->name                                = $entity->getName()->getValue();
        $eloquent->order_no                            = $entity->getOrderNo()->getValue();
        $eloquent->llm_api_key_type                    = $entity->getLlmApiKeyType()->getValue();
        $eloquent->open_ai_api_key_id                  = $entity->getOpenAiApiKeyId() !== null
            ? $entity->getOpenAiApiKeyId()->getValue()
            : null;
        $eloquent->azure_open_ai_api_key_id = $entity->getAzureOpenAiApiKeyId() !== null
            ? $entity->getAzureOpenAiApiKeyId()->getValue()
            : null;
        $eloquent->in_house_llm_model_id = $entity->getInHouseLlmModelId() !== null
            ? $entity->getInHouseLlmModelId()->getValue()
            : null;
        $eloquent->prompt                             = $entity->getPrompt()->getValue();
        $eloquent->detail_setting                     = $entity->getDetailSetting()->getValue();
        $eloquent->map_prompt                         = $entity->getMapPrompt()
            ? $entity->getMapPrompt()->getValue()
            : null;
        $eloquent->memo                       = $entity->getMemo() !== null
            ? $entity->getMemo()->getValue()
            : null;
        $eloquent->save();

        //IDでエンティティ取得し直して返却
        $id = new PromptSettingId($eloquent->id);
        return (self::findEloquent($id))->toEntity();
    }

    /**
     * @param  PromptSetting $entity
     * @return self
     */
    private static function getInstance(
        PromptSetting $entity
    ): self {
        if ($entity->isSaved()) {
            return self::findEloquent($entity->getId());
        }
        return new self();
    }

    /**
     * Eagerフェッチ用
     * ＊メソッド名は上記 builder() で文字列参照される
     * @return BelongsTo
     */
    public function openAiApiKey(): BelongsTo {
        return $this->belongsTo(OpenAiApiKeyEloquent::class);
    }

    /**
     * Eagerフェッチ用
     * ＊メソッド名は上記 builder() で文字列参照される
     * @return BelongsTo
     */
    public function azureOpenAiApiKey(): BelongsTo {
        return $this->belongsTo(AzureOpenAiApiKeyEloquent::class);
    }

    /**
     * Eagerフェッチ用
     * ＊メソッド名は上記 builder() で文字列参照される
     * @return BelongsTo
     */
    public function inHouseLlmModel(): BelongsTo {
        return $this->belongsTo(InHouseLlmModelEloquent::class);
    }

    //----------------------------------
    //region JSON変換

    //JSON変換時に含めないフィールド
    protected $hidden = [];

    //JSON変換時に含めるフィールド
    protected $appends = [];

    //endregion
    //----------------------------------
}
