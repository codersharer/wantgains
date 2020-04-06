<?php


namespace App\Services\Validate;


class ProductValidate
{
    /**
     * 校验必填字段
     *
     */
    public static function handle($data)
    {
        $fields = [
            'name',
            'affiliate_id',
            'domain',
            'domain_id',
        ];
        foreach ($fields as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }
}