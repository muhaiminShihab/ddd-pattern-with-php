<?php
declare(strict_types=1);

/*
 * Copyright (c) 2017-2018. All rights reserved.
 */

namespace Project\Biztel\Models\PromptSetting\Permissions;

use App\Http\Abilities\Bases\AbstractAbilities;
use Project\Biztel\Bases\Meta\Types\EntityType;
use Project\Biztel\Models\Account\Eloquents\AccountEloquent;
use Project\Biztel\Models\MenuAssign\ValueObjects\Permission;
use Project\Biztel\Models\PromptSetting\PromptSetting;

/**
 * Class OpenAiPromptSettingAbilities
 */
final class PromptSettingAbilities extends AbstractAbilities {
    /**
     * @return EntityType
     */
    public function entity(): EntityType {
        return EntityType::PromptSetting();
    }

    /**
     * @param  AccountEloquent $account_eloquent
     * @return bool
     */
    public function index(AccountEloquent $account_eloquent): bool {
        return $this->hasMenuPermission(Permission::READ());
    }

    /**
     * @param  AccountEloquent $account_eloquent
     * @return bool
     */
    public function show(AccountEloquent $account_eloquent): bool {
        return $this->hasMenuPermission(Permission::READ());
    }

    /**
     * @param  AccountEloquent $account_eloquent
     * @return bool
     */
    public function store(AccountEloquent $account_eloquent): bool {
        return $this->hasMenuPermission(Permission::CREATE());
    }

    /**
     * @param  AccountEloquent $account_eloquent
     * @param  PromptSetting   $entity
     * @return bool
     */
    public function update(AccountEloquent $account_eloquent, PromptSetting $entity): bool {
        //メニュー権限・編集可能レコードの確認
        return $this->hasMenuPermission(Permission::MODIFY())
            && $entity->isEditingRecord();
    }

    /**
     * @param  AccountEloquent $account_eloquent
     * @param  PromptSetting   $entity
     * @return bool
     */
    public function destroy(AccountEloquent $account_eloquent, PromptSetting $entity): bool {
        //メニュー権限・編集可能レコードの確認
        return $this->hasMenuPermission(Permission::CREATE())
            && $entity->isEditingRecord();
    }

    /**
     * @param  AccountEloquent $account_eloquent
     * @return bool
     */
    public function order(AccountEloquent $account_eloquent): bool {
        //メニュー権限・編集可能レコードの確認
        return $this->hasMenuPermission(Permission::MODIFY());
    }

    /**
     * @param  AccountEloquent $account_eloquent
     * @return bool
     */
    public function count(AccountEloquent $account_eloquent): bool {
        return true;
    }
}
