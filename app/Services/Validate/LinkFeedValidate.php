<?php


namespace App\Services\Validate;


class LinkFeedValidate
{
    /**
     * 校验必填字段
     *
     */
    public static function handle($data)
    {
        $fields = [
            'name',
            'link_id',
            'affiliate_id',
            'domain',
            'domain_id',
            'type',
            'url',
            'status',
            'id_in_aff',
        ];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }
}