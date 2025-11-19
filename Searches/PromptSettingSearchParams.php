<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Searches;

use App\Http\Controllers\Functions\Params;
use Illuminate\Database\Eloquent\Builder;
use Project\Biztel\Bases\Searches\AbstractSearchParams;
use Project\Biztel\Models\PromptSetting\Collections\PromptSettingCollection;
use Project\Biztel\Models\PromptSetting\Eloquents\PromptSettingEloquent;
use PromptSettingMigration390rc000;

/**
 * class PromptSettingSearchParams
 */
final class PromptSettingSearchParams extends AbstractSearchParams {
    /**
     * @param  Params $params
     * @return self
     */
    public static function new(
        Params $params
    ): self {
        return new self($params);
    }

    /**
     * @return Builder
     */
    protected function buildQuery(): Builder {
        $conditions = PromptSettingEloquent::eagerBuilder();
        $table_name = PromptSettingMigration390rc000::TABLE_NAME;

        //ユニークチェック用
        $conditions = self::setSearchConditionInColumn($conditions, $this->getParam('except_id'), $table_name, 'id', self::EXCEPT);
        $conditions = self::setSearchConditionInColumn($conditions, $this->getParam('exact_name'), $table_name, 'name', self::EQUAL);

        //検索用
        //名称を部分検索
        return self::setSearchConditionInColumn($conditions, $this->getParam('name'), $table_name, 'name', self::PARTIAL_MATCH);
    }

    /**
     * @return PromptSettingCollection
     */
    public function execute(): PromptSettingCollection {
        $paginated = $this->executeQuery();
        return new PromptSettingCollection($paginated->items());
    }
}
