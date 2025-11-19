<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\ValueObjects;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\ValueObjects\AbstractValueObjectType;

final class PromptSettingDetailSetting extends AbstractValueObjectType {
    /**
     * @return self
     */
    public static function getDefault(): self {
        return self::DISABLE();
    }

    /**
     * @\Project\Biztel\Bases\Annotations\TypeItem(id=1, name="設定する")
     * @return self
     */
    public static function ENABLE(): self {
        return new self(1);
    }

    /**
     * @\Project\Biztel\Bases\Annotations\TypeItem(id=0, name="設定しない")
     * @return self
     */
    public static function DISABLE(): self {
        return new self(0);
    }

    /**
     * バリデーションルール
     * @return string
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function getValidationRule(): string {
        return 'required|numeric|between:0,1';
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

    /**
     * 有効かどうか
     * @return bool
     */
    public function isEnable(): bool {
        return $this->isEqual(self::ENABLE());
    }
}
