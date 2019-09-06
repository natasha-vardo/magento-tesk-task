<?php

class PA_Access_Model_Adminhtml_System_Config_Source_Shopcategory
{
    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $categories = Mage::getModel('catalog/category')->getCollection()->load();
        $categoriesId = [];

        foreach($categories as $category) {
            if ($category['level'] !== '0' && $category['level'] !== '1') {
                $categoriesId[] = [
                    'value' => $category['entity_id']
                ];
            }
        }

        $options = [];

        foreach ($categoriesId as $categoryId) {
            $categoryInfo = Mage::getModel('catalog/category')->load($categoryId['value']);
            $options[] = [
                'value' => $categoryId['value'],
                'label' => $categoryInfo->getName()
            ];
        }

        return $options;
    }
}
