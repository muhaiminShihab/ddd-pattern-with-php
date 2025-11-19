<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\ValueObjects;

use App\Http\Controllers\Functions\Params;
use Project\Biztel\Bases\Exceptions\ValidateException;
use Project\Biztel\Bases\Validates\ExceptionsBag;
use Project\Biztel\Bases\ValueObjects\AbstractValueObjectResourceIds;
use Project\Biztel\Models\PromptSetting\Eloquents\PromptSettingEloquent;
use Project\Biztel\Models\PromptSetting\Repositories\PromptSettingRepository;

class PromptSettingIds extends AbstractValueObjectResourceIds {
    /**
     * jsonの各項目の値をVOの配列に変換
     * @param  String $ids
     * @return array
     */
    protected function createVOArray(String $ids): array {
        $json_array = $this->jsonToArray($ids);

        //インスタンス生成時のエラーを拾うExceptionsBagを用意
        $each_exceptions = new ExceptionsBag();
        $i               = 0; //エラー箇所の特定用

        $prompt_setting_ids = [];

        // 存在するidの配列を取得 ※存在確認用
        $target_ids = array_column($json_array, 'id');
        $exist_ids  = PromptSettingRepository::findByIds($target_ids)->toIntIds();

        foreach ($json_array as $value) {
            $id = $each_exceptions->tryFunction(
                function () use ($value, $exist_ids) {
                    $id = (\array_key_exists('id', $value) && $value['id']) ? $value['id'] : null;
                    $prompt_setting_id = new PromptSettingId($id);
                    $prompt_setting_id->existInIds($exist_ids);
                    return $prompt_setting_id;
                }, ++$i . '_id'
            );

            $prompt_setting_ids[] = ['id' => $id];
        }

        //パラメーター作成時のエラーを大元のExceptionsBagに入れる
        if ($each_exceptions->hasExceptions()) {
            $message = $each_exceptions->getMessages();
            throw new ValidateException(['prompt_setting_ids' => $message]);
        }

        return $prompt_setting_ids;
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
    public static function new(Params $params, bool $is_new, ?self $current, String $key_name, bool $able_null_string = false): ?self {
        return self::factory(self::class, $params, $is_new, $current, $key_name, $able_null_string);
    }

    /**
     * 表示順序を更新
     * プロンプト設定IDの指定の過不足があっても更新します
     */
    public function updatePromptSettingsOrder(): void {
        // 指定が無いステータスのOrderNoの起点
        $last_order_no = count($this->value);

        // 検索用に表示順のIDのフラットな配列を作成
        $prompt_setting_ids = collect($this->getValue())->map(function ($prompt_setting) {
            return $prompt_setting['id']->getValue();
        })->toArray();

        PromptSettingRepository::getAll()->collection
            ->each(function (PromptSettingEloquent $eloquent) use ($prompt_setting_ids, &$last_order_no) {
                $prompt_setting = $eloquent->toEntity();

                // ステータス順序の配列から検索
                $order_no = array_search($prompt_setting->getId()->getValue(), $prompt_setting_ids, true);
                // ステータス順序の配列に無かったら独自に採番する
                if ($order_no === false) {
                    $order_no = $last_order_no++;
                }

                // 順序を反映する
                $prompt_setting->setOrderNo(new PromptSettingOrderNo($order_no + 1))->save();
            });
    }
}
