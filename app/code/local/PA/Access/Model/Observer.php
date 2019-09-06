<?php

class PA_Access_Model_Observer
{
    /**
     * @param $observer Varien_Event_Observer
     */
    public function checkout_cart_save_before(Varien_Event_Observer $observer)
    {
        $startRestrictionFieldInfo = Mage::getStoreConfig('access/settings/start_restriction');
        $endRestrictionFieldInfo = Mage::getStoreConfig('access/settings/end_restriction');

        $settingsRestrictionCategory = Mage::getStoreConfig('access/settings/restriction_category');
        $restrictionCategory = explode(',', $settingsRestrictionCategory);

        $startRestriction = strtr($startRestrictionFieldInfo, ',', ':');
        $endRestriction = strtr($endRestrictionFieldInfo, ',', ':');

        $helper = Mage::helper('access');
        $restriction = $helper->getRestriction($startRestriction, $endRestriction);

        $currentDateTime = $restriction['currentDateTime'];
        $startDateTime = $restriction['startDateTime'];
        $endDateTime = $restriction['endDateTime'];
        $previousDayEnd = $restriction['previousDayEnd'];

        $items = $observer->getEvent()->getData('cart')->getItems();

        foreach ($items as $item) {
            $categoryIds = Mage::getModel('catalog/product')->load($item['product_id'])->getCategoryIds();

            foreach ($restrictionCategory as $category) {
                if (in_array($category, $categoryIds)) {
                    if ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
                        $removeItem = $observer->getEvent()->getData('cart')->getQuote();
                        $removeItem->removeItem($item['item_id'])->save();
                    } else if ($currentDateTime < $startDateTime && $currentDateTime < $previousDayEnd) {
                        $removeItem = $observer->getEvent()->getData('cart')->getQuote();
                        $removeItem->removeItem($item['item_id'])->save();
                    }
                }
            }
        }
    }
}
