<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\ValueObjects;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\ValueObjects\AbstractValueObjectString;

/**
 * class PromptSettingMapPrompt
 */
final class PromptSettingMapPrompt extends AbstractValueObjectString {
    public const LENGTH = 1000;

    /**
     * @return string|null
     */
    protected function getValidationRule(): ?string {
        return 'string|between:1,' . self::LENGTH;
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
        string $key_name,
        bool $able_null_string = false
    ): ?self {
        return self::factory(
            self::class,
            $params,
            $is_new,
            $current,
            $key_name,
            $able_null_string,
            null,
            true
        );
    }
}
