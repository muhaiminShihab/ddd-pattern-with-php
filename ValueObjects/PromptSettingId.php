<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018 Brisys. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\ValueObjects;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\Exceptions\EntityNotFoundException;
use Project\Biztel\Bases\ValueObjects\AbstractValueObjectId;
use Project\Biztel\Models\PromptSetting\Eloquents\PromptSettingEloquent;
use Project\Biztel\Models\PromptSetting\PromptSetting;
use Project\Biztel\Models\PromptSetting\Repositories\PromptSettingRepository;

/**
 * Class OpenAiPromptSettingId
 */
final class PromptSettingId extends AbstractValueObjectId {
    /**
     * @return PromptSetting
     * @throws \ReflectionException
     */
    public function toEntity(): PromptSetting {
        $entity = PromptSettingRepository::getOne($this);
        if ($entity === null) {
            throw EntityNotFoundException::NOT_FOUND(PromptSetting::class, $this->getValue());
        }
        return $entity;
    }

    /**
     * @param  bool|null             $is_eager
     * @return PromptSettingEloquent
     */
    public function toEloquent(
        ?bool $is_eager = null
    ): PromptSettingEloquent {
        $eloquent = PromptSettingRepository::findEloquent($this, $is_eager);
        if ($eloquent === null) {
            throw EntityNotFoundException::NOT_FOUND(PromptSetting::class, $this->getValue());
        }
        return $eloquent;
    }

    /**
     * idsの中に存在するか確認
     * @param int[] $ids
     */
    public function existInIds(array $ids): void {
        if (!collect($ids)->contains($this->value)) {
            throw EntityNotFoundException::NOT_FOUND(PromptSetting::class, $this->value);
        }
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
        return self::factory(
            self::class,
            $params,
            $is_new,
            $current,
            $key_name,
            $able_null_string
        );
    }
}
