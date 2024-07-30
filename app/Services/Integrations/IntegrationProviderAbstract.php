<?php

namespace App\Services\Integrations;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Brand;
use App\Models\ProductAttribute;
use App\Models\ProductGroup;
use App\Models\ProductType;
use App\Models\Season;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Unit;

class IntegrationProviderAbstract
{


    protected function getProductTypeId($code, $name)
    {
        $productType = ProductType::firstOrCreate([
            'code' => $code
        ], [
            'name' => $name
        ]);

        return $productType->id;
    }

    protected function getProductGroupId($code, $name)
    {
        $productGroup = ProductGroup::firstOrCreate([
            'code' => $code
        ], [
            'name' => $name
        ]);

        return $productGroup->id;
    }

    protected function getSeasonId($code, $name)
    {
        $season = Season::firstOrCreate([
            'code' => $code
        ], [
            'name' => $name
        ]);

        return $season->id;
    }

    protected function getBrandId($code, $name)
    {
        $brand = Brand::firstOrCreate([
            'code' => $code
        ], [
            'name' => $name
        ]);

        return $brand->id;
    }

    protected function getSupplierId($code, $name)
    {

        // TODO company ekle
        $supplier = Supplier::firstOrCreate([
            'code' => $code
        ], [
            'name' => $name,
            'surname' => 'Integration',
            'email' => $code . '@bee.com',
            'phone' => '0212 123 45 67',
            'password' => bcrypt(uniqid())

        ]);

        return $supplier->id;
    }

    protected function getUnitId($name)
    {
        $unit = Unit::firstOrCreate([
            'name' => $name
        ], [
            'name' => $name,
            'unit_quantity' => 1,
        ]);

        return $unit->id;
    }


    protected function setProductAttribute($productModel, $attrCode, $attrName, $optionCode, $optionName) {

        $attribute = Attribute::firstOrCreate([
            'code' => $attrCode
        ], [
            'name' => $attrName
        ]);

        $option = AttributeOption::firstOrCreate([
            'attribute_id' => $attribute->id,
            'code' => $optionCode
        ], [
            'name' => $optionName
        ]);

        ProductAttribute::updateOrCreate([
            'product_id' => $productModel->id,
            'attribute_id' => $attribute->id
        ], [
            'attribute_option_id' => $option->id
        ]);

    }


    protected function getStoreId($code, $name)
    {
        $store = Store::firstOrCreate([
            'code' => $code
        ], [
            'name' => $name
        ]);

        return $store->id;
    }

}
