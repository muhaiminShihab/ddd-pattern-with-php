<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting;

use Project\Biztel\Bases\AbstractEntity;
use Project\Biztel\Bases\Annotations\AnnotationParser;
use Project\Biztel\Bases\Exceptions\InvalidCodeException;
use Project\Biztel\Bases\Meta\Types\EntityType;
use Project\Biztel\Bases\Utils\ClassUtils;
use Project\Biztel\Bases\Utils\Valid;
use Project\Biztel\Bases\ValueObjects\Shares\Memo;
use Project\Biztel\Models\AzureOpenAiApiKey\AzureOpenAiApiKey;
use Project\Biztel\Models\AzureOpenAiApiKey\ValueObjects\AzureOpenAiApiKeyId;
use Project\Biztel\Models\InHouseLlmModel\InHouseLlmModel;
use Project\Biztel\Models\InHouseLlmModel\ValueObjects\InHouseLlmModelId;
use Project\Biztel\Models\License\License;
use Project\Biztel\Models\OpenAiApiKey\OpenAiApiKey;
use Project\Biztel\Models\OpenAiApiKey\ValueObjects\OpenAiApiKeyId;
use Project\Biztel\Models\PromptSetting\Eloquents\PromptSettingEloquent;
use Project\Biztel\Models\PromptSetting\Factories\PromptSettingFactory;
use Project\Biztel\Models\PromptSetting\Functions\PromptSettingFunction;
use Project\Biztel\Models\PromptSetting\Repositories\PromptSettingRepository;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingDetailSetting;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingId;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingLlmApiKeyType;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingMapPrompt;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingName;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingOrderNo;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingPrompt;

/**
 * Class OpenAiPromptSetting
 */
final class PromptSetting extends AbstractEntity {
    //新規・更新用トレイト
    use PromptSettingFactory;
    use PromptSettingFunction;

    /**
     * @param PromptSettingName           $name
     * @param PromptSettingPrompt         $prompt
     * @param PromptSettingDetailSetting  $detail_setting
     * @param PromptSettingLlmApiKeyType  $llm_api_key_type
     * @param OpenAiApiKeyId|null         $open_ai_api_key_id
     * @param AzureOpenAiApiKeyId|null    $azure_open_ai_api_key_id
     * @param InHouseLlmModelId|null      $in_house_llm_model_id
     * @param PromptSettingMapPrompt|null $map_prompt
     * @param PromptSettingOrderNo|null   $order_no
     * @param Memo|null                   $memo
     */
    public function __construct(
        PromptSettingName $name,
        PromptSettingPrompt $prompt,
        PromptSettingDetailSetting $detail_setting,
        PromptSettingLlmApiKeyType $llm_api_key_type,
        ?OpenAiApiKeyId $open_ai_api_key_id = null,
        ?AzureOpenAiApiKeyId $azure_open_ai_api_key_id = null,
        ?InHouseLlmModelId $in_house_llm_model_id = null,
        ?PromptSettingMapPrompt $map_prompt = null,
        ?PromptSettingOrderNo $order_no = null,
        ?Memo $memo = null
    ) {
        $this->name                     = $name;
        $this->order_no                 = $order_no ?? PromptSettingOrderNo::getLatestOrderNo();
        $this->llm_api_key_type         = $llm_api_key_type;
        $this->open_ai_api_key_id       = $open_ai_api_key_id;
        $this->azure_open_ai_api_key_id = $azure_open_ai_api_key_id;
        $this->in_house_llm_model_id    = $in_house_llm_model_id;
        $this->prompt                   = $prompt;
        $this->detail_setting           = $detail_setting;
        $this->map_prompt               = $map_prompt;
        $this->memo                     = $memo;
    }

    /**
     * @return EntityType
     */
    public function entityType(): EntityType {
        return EntityType::PromptSetting();
    }

    /**
     * @var PromptSettingId
     */
    protected $id;

    /**
     * @\Project\Biztel\Bases\Annotations\NotEmpty
     * @var PromptSettingName
     */
    protected $name;

    /**
     * @\Project\Biztel\Bases\Annotations\NotEmpty(hasDefault=true)
     * @var PromptSettingOrderNo
     */
    protected $order_no;

    /**
     * @\Project\Biztel\Bases\Annotations\NotEmpty
     * @var PromptSettingLlmApiKeyType
     */
    protected $llm_api_key_type;

    /**
     * @var OpenAiApiKeyId|null
     */
    protected $open_ai_api_key_id;

    /**
     * @var AzureOpenAiApiKeyId|null
     */
    protected $azure_open_ai_api_key_id;

    /**
     * @var InHouseLlmModelId|null
     */
    protected $in_house_llm_model_id;

    /**
     * @\Project\Biztel\Bases\Annotations\NotEmpty
     * @var PromptSettingPrompt
     */
    protected $prompt;

    /**
     * @\Project\Biztel\Bases\Annotations\NotEmpty
     * @var PromptSettingDetailSetting
     */
    protected $detail_setting;

    /**
     * @var PromptSettingMapPrompt
     */
    protected $map_prompt;

    /**
     * @var Memo
     */
    protected $memo;

    /**
     * @return PromptSettingId
     */
    public function getId(): PromptSettingId {
        if ($this->id === null) {
            throw InvalidCodeException::INVALID_FLOW(trans('exceptions.InvalidFlowException.ENTITY_IS_NOT_SAVED'));
        }
        return $this->id;
    }

    /**
     * @return PromptSettingName
     */
    public function getName(): PromptSettingName {
        return $this->name;
    }

    /**
     * @param  PromptSettingName $name
     * @return PromptSetting
     */
    public function setName(PromptSettingName $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * @return PromptSettingOrderNo
     */
    public function getOrderNo(): PromptSettingOrderNo {
        return $this->order_no;
    }

    /**
     * @param  PromptSettingOrderNo $order_no
     * @return PromptSetting
     */
    public function setOrderNo(PromptSettingOrderNo $order_no): self {
        $this->order_no = $order_no;
        return $this;
    }

    /**
     * @return PromptSettingLlmApiKeyType
     */
    public function getLlmApiKeyType(): PromptSettingLlmApiKeyType {
        return $this->llm_api_key_type;
    }

    /**
     * @param  PromptSettingLlmApiKeyType $llm_api_key_type
     * @return $this
     */
    public function setLlmApiKeyType(PromptSettingLlmApiKeyType $llm_api_key_type): self {
        $this->llm_api_key_type = $llm_api_key_type;
        return $this;
    }

    /**
     * @return OpenAiApiKeyId|null
     */
    public function getOpenAiApiKeyId(): ?OpenAiApiKeyId {
        return $this->open_ai_api_key_id;
    }

    /**
     * @param  OpenAiApiKeyId|null $open_ai_api_key_id
     * @return PromptSetting
     */
    public function setOpenAiApiKeyId(
        ?OpenAiApiKeyId $open_ai_api_key_id
    ): self {
        $this->open_ai_api_key_id = $open_ai_api_key_id;
        return $this;
    }

    /**
     * @return AzureOpenAiApiKeyId|null
     */
    public function getAzureOpenAiApiKeyId(): ?AzureOpenAiApiKeyId {
        return $this->azure_open_ai_api_key_id;
    }

    /**
     * @param  AzureOpenAiApiKeyId|null $azure_open_ai_api_key_id
     * @return $this
     */
    public function setAzureOpenAiApiKeyId(
        ?AzureOpenAiApiKeyId $azure_open_ai_api_key_id
    ): self {
        $this->azure_open_ai_api_key_id = $azure_open_ai_api_key_id;
        return $this;
    }

    /**
     * @return InHouseLlmModelId|null
     */
    public function getInHouseLlmModelId(): ?InHouseLlmModelId {
        return $this->in_house_llm_model_id;
    }

    /**
     * @param  InHouseLlmModelId|null $in_house_llm_model_id
     * @return $this
     */
    public function setInHouseLlmModelId(
        ?InHouseLlmModelId $in_house_llm_model_id
    ): self {
        $this->in_house_llm_model_id = $in_house_llm_model_id;
        return $this;
    }

    /**
     * @return PromptSettingPrompt
     */
    public function getPrompt(): PromptSettingPrompt {
        return $this->prompt;
    }

    /**
     * @param  PromptSettingPrompt $prompt
     * @return PromptSetting
     */
    public function setPrompt(PromptSettingPrompt $prompt): self {
        $this->prompt = $prompt;
        return $this;
    }

    /**
     * @return PromptSettingDetailSetting
     */
    public function getDetailSetting(): PromptSettingDetailSetting {
        return $this->detail_setting;
    }

    /**
     * @param  PromptSettingDetailSetting $detail_setting
     * @return PromptSetting
     */
    public function setDetailSetting(PromptSettingDetailSetting $detail_setting): self {
        $this->detail_setting = $detail_setting;
        return $this;
    }

    /**
     * @return PromptSettingMapPrompt|null
     */
    public function getMapPrompt(): ?PromptSettingMapPrompt {
        return $this->map_prompt;
    }

    /**
     * @param  PromptSettingMapPrompt|null $map_prompt
     * @return $this
     */
    public function setMapPrompt(?PromptSettingMapPrompt $map_prompt): self {
        $this->map_prompt = $map_prompt;
        return $this;
    }

    /**
     * @return OpenAiApiKey|null
     */
    public function getOpenAiApiKey(): ?OpenAiApiKey {
        if ($this->open_ai_api_key_id === null) {
            return null;
        }
        return $this->open_ai_api_key_id->toEntity();
    }

    /**
     * @return AzureOpenAiApiKey|null
     */
    public function getAzureOpenAiApiKey(): ?AzureOpenAiApiKey {
        if ($this->azure_open_ai_api_key_id === null) {
            return null;
        }
        return $this->azure_open_ai_api_key_id->toEntity();
    }

    /**
     * @return InHouseLlmModel|null
     */
    public function getInHouseLlmModel(): ?InHouseLlmModel {
        if ($this->in_house_llm_model_id === null) {
            return null;
        }
        return $this->in_house_llm_model_id->toEntity();
    }

    /**
     * @return null|Memo
     */
    public function getMemo(): ?Memo {
        return $this->memo;
    }

    /**
     * @param  Memo|null     $memo
     * @return PromptSetting
     */
    public function setMemo(?Memo $memo = null): self {
        $this->memo = $memo;
        return $this;
    }

    /**
     * @return void
     */
    public function isSatisfied(): void {
        //必須フィールド存在確認
        $annotations = AnnotationParser::getNotEmptyAnnotations(get_class($this));
        Valid::notEmptyAll($this, $annotations);
        // [APIキー LLM種別] OpenAI の場合
        if ($this->getLlmApiKeyType()->isOpenAi()) {
            Valid::notEmpty(
                $this->open_ai_api_key_id,
                ClassUtils::toSnake($this->open_ai_api_key_id)
            );
        }
        // [APIキー LLM種別] AzureOpenAI の場合
        if ($this->getLlmApiKeyType()->isAzureOpenAi()) {
            Valid::notEmpty(
                $this->azure_open_ai_api_key_id,
                ClassUtils::toSnake($this->azure_open_ai_api_key_id)
            );
        }
        // [APIキー LLM種別] BIZTEL提供モデル の場合
        if ($this->getLlmApiKeyType()->isInHouseLlmModel()) {
            Valid::notEmpty(
                $this->in_house_llm_model_id,
                ClassUtils::toSnake($this->in_house_llm_model_id)
            );
        }
    }

    public function preSave(bool $ignore_log = false): void {
        parent::preSave();
        // 詳細設定: 設定しないの場合、詳細設定配下の設定をクリアする
        if ($this->getDetailSetting()->isEnable() === false) {
            $this->setMapPrompt(null);
        }
        // <APIキー LLM種別毎の処理>
        //  - [APIキー LLM種別] OpenAI の場合
        if ($this->getLlmApiKeyType()->isOpenAi()) {
            // OpenAI以外のAPIキーをnullにする
            $this->setAzureOpenAiApiKeyId(null);
            $this->setInHouseLlmModelId(null);
        }
        //  - [APIキー LLM種別] AzureOpenAI の場合
        if ($this->getLlmApiKeyType()->isAzureOpenAi()) {
            // AzureOpenAI以外のAPIキーをnullにする
            $this->setOpenAiApiKeyId(null);
            $this->setInHouseLlmModelId(null);
        }
        //  - [APIキー LLM種別] BIZTEL提供モデル の場合
        if ($this->getLlmApiKeyType()->isInHouseLlmModel()) {
            // BIZTEL提供モデル以外のAPIキーをnullにする
            $this->setOpenAiApiKeyId(null);
            $this->setAzureOpenAiApiKeyId(null);
        }

        //登録上限数の確認
        License::DEFAULT()->getLlmPromptSettingLimit()->checkLimitation($this);
    }

    /**
     * @return PromptSetting
     * @throws \ReflectionException
     */
    public function save(): self {
        $this->preSave();

        return PromptSettingRepository::save($this);
    }

    /**
     * @return PromptSettingId
     */
    public function delete(): PromptSettingId {
        return PromptSettingRepository::delete($this);
    }

    /**
     * @param  bool|null             $is_eager
     * @return PromptSettingEloquent
     */
    public function toEloquent(?bool $is_eager = null): PromptSettingEloquent {
        return PromptSettingRepository::findEloquent($this->getId(), $is_eager);
    }

    /**
     * @return PromptSetting|null
     * @throws \ReflectionException
     */
    public function reload(): ?self {
        if ($this->isSaved()) {
            return $this->getId()->toEntity();
        }
        return null;
    }

    /**
     * ユーザレコードのみをカウントする
     * @return int
     */
    public static function countUserRecord(): int {
        return PromptSettingRepository::countUserRecord();
    }
}
