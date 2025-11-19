<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018 Brisys. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\ValueObjects;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\ValueObjects\AbstractValueObjectType;

/**
 * Class PromptSettingLlmApiKeyType
 */
final class PromptSettingLlmApiKeyType extends AbstractValueObjectType {
    /**
     * @return self
     */
    public static function getDefault(): self {
        return self::OPENAI();
    }

    /**
     * OpenAI
     * @\Project\Biztel\Bases\Annotations\TypeItem(id=1, name="OpenAI")
     * @return self
     */
    public static function OPENAI(): self {
        return new self(1);
    }

    /**
     * Azure OpenAI
     * @\Project\Biztel\Bases\Annotations\TypeItem(id=2, name="Azure OpenAI")
     * @return self
     */
    public static function AZURE_OPENAI(): self {
        return new self(2);
    }

    // TODO:3.13.0では提供しないので、フロント側で表示しないように@の後に半角スペースを入れている
    /**
     * BIZTEL提供モデル
     * @ \Project\Biztel\Bases\Annotations\TypeItem(id=3, name="BIZTEL提供モデル")
     * @return self
     */
    public static function IN_HOUSE_LLM_MODEL(): self {
        return new self(3);
    }

    /**
     * バリデーションルール
     * @return string
     */
    protected function getValidationRule(): string {
        return 'required|numeric|between:1,3';
    }

    /**
     * ファクトリ
     * @param  Params    $params
     * @param  bool      $is_new
     * @param  self|null $current
     * @param  String    $key_name
     * @param  bool      $able_null_string
     * @return null|self
     */
    public static function new(
        Params $params,
        bool $is_new,
        ?self $current,
        String $key_name,
        bool $able_null_string = false
    ): ?self {
        return self::factory(self::class, $params, $is_new, $current, $key_name, $able_null_string);
    }

    /**
     * @return bool
     */
    public function isOpenAi(): bool {
        return $this->isEqual(self::OPENAI());
    }

    /**
     * @return bool
     */
    public function isAzureOpenAi(): bool {
        return $this->isEqual(self::AZURE_OPENAI());
    }

    /**
     * @return bool
     */
    public function isInHouseLlmModel(): bool {
        return $this->isEqual(self::IN_HOUSE_LLM_MODEL());
    }
}
