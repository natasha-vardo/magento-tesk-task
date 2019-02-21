<?php

class PA_Access_Model_Adminhtml_System_Config_Source_Shopcategory
{

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $categories = Mage::getModel('catalog/category')->getCollection()->load();
        $categoriesIdArray = [];

        foreach($categories as $category) {
            if ($category['level'] !== '0' && $category['level'] !== '1') {
                $categoriesIdArray[] = [
                    'value' => $category['entity_id']
                ];
            }
        }

        $options = [];

        foreach ($categoriesIdArray as $id) {
            $categoryInfo = Mage::getModel('catalog/category')->load($id['value']);
            $options[] = [
                'value' => $id['value'],
                'label' => $categoryInfo->getName()
            ];
        }

        return $options;
    }
}
