<?php


namespace App\Services\Sync\Validate;


use function dd;

class ProgramValidate
{

    /**
     * 校验必填字段
     *
     */
    public static function handle($data)
    {
        $fields = [
            'name',
            'id_in_aff',
            'homepage',
            'domain',
            'default_track_link',
            'real_track_link',
        ];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }
}