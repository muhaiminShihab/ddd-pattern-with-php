<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\ValueObjects;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\ValueObjects\AbstractValueObjectInt;
use Project\Biztel\Models\PromptSetting\Repositories\PromptSettingRepository;

/**
 * Class OpenAiPromptSettingSortPriority
 */
final class PromptSettingOrderNo extends AbstractValueObjectInt {
    /**
     * 最新のOrderNoの取得
     * @return PromptSettingOrderNo
     */
    public static function getLatestOrderNo(): self {
        // 表示順が最後になる値をデフォルト値とします
        $latest_order_no = PromptSettingRepository::findLatestOrderNo();
        return new self($latest_order_no);
    }

    /**
     * 次のOrderNoを返却
     * @return int
     */
    public function getNextOrderNo(): int {
        return $this->getValue() + 1;
    }

    /**
     * @return string
     */
    protected function getValidationRule(): string {
        return 'required|integer';
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
            $able_null_string
        );
    }
}
