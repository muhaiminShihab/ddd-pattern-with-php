<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Repositories;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\Exceptions\EntityNotFoundException;
use Project\Biztel\Models\PromptSetting\Collections\PromptSettingCollection;
use Project\Biztel\Models\PromptSetting\Eloquents\PromptSettingEloquent;
use Project\Biztel\Models\PromptSetting\PromptSetting;
use Project\Biztel\Models\PromptSetting\Searches\PromptSettingSearchParams;
use Project\Biztel\Models\PromptSetting\ValueObjects\PromptSettingId;

/**
 * Class OpenAiPromptSettingRepository
 */
final class PromptSettingRepository {
    //個別クエリ
    use PromptSettingRepositoryQuery;

    /**
     * PromptSettingのカウント
     * @return int
     */
    public static function count(): int {
        $collection = self::getAll();
        return $collection->count();
    }

    /**
     * ユーザレコードのみをカウントする
     * @return int
     */
    public static function countUserRecord(): int {
        $collection = self::getAll();
        return $collection->filterByUserRecord()->count();
    }

    /**
     * @param  Params                  $params
     * @return PromptSettingCollection
     */
    public static function search(Params $params): PromptSettingCollection {
        return PromptSettingSearchParams::new($params)->execute();
    }

    /**
     * @param  PromptSettingId      $id
     * @return PromptSetting|null
     * @throws \ReflectionException
     */
    public static function getOne(PromptSettingId $id): ?PromptSetting {
        $eloquent = self::findEloquent($id);
        if ($eloquent === null) {
            return null;
        }
        return $eloquent->toEntity();
    }

    /**
     * @param  bool                    $is_eager
     * @return PromptSettingCollection
     */
    public static function getAll(
        bool $is_eager = false
    ): PromptSettingCollection {
        $eloquents = PromptSettingEloquent::builder($is_eager)->get();
        return new PromptSettingCollection($eloquents);
    }

    /**
     * @param  PromptSettingId            $id
     * @param  bool|null                  $is_eager
     * @return PromptSettingEloquent|null
     */
    public static function findEloquent(
        PromptSettingId $id,
        ?bool $is_eager = null
    ): ?PromptSettingEloquent {
        return PromptSettingEloquent::findEloquent($id, $is_eager);
    }

    /**
     * @param  PromptSetting        $entity
     * @return PromptSetting
     * @throws \ReflectionException
     */
    public static function save(PromptSetting $entity): PromptSetting {
        return PromptSettingEloquent::saveEntity($entity);
    }

    /**
     * @param  PromptSetting   $entity
     * @return PromptSettingId
     */
    public static function delete(PromptSetting $entity): PromptSettingId {
        $id       = $entity->getId();
        $eloquent = self::findEloquent($id);
        if ($eloquent === null) {
            throw EntityNotFoundException::NOT_FOUND(PromptSetting::class, $id->getValue());
        }

        $eloquent->forceDelete();
        return $id;
    }

    /**
     * @param  int[]                   $ids
     * @return PromptSettingCollection
     */
    public static function findByIds(array $ids): PromptSettingCollection {
        $eloquents = PromptSettingEloquent::builder()->findMany($ids);
        return new PromptSettingCollection($eloquents);
    }
}
