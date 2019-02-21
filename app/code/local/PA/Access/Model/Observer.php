<?php

class PA_Access_Model_Observer
{

    /**
     * @param int $productId
     *
     * @return array|null
     */
    public function getCategoryIds(int $productId): array
    {
        if (isset($productId)) {
            $productCategory = Mage::getModel('catalog/product')->load($productId)->getCategoryIds();
            return $productCategory;
        } else {
            return null;
        }
    }

    /**
     * @param $observer Varien_Event_Observer
     */
    public function checkout_cart_save_before(Varien_Event_Observer $observer)
    {
        $startRestrictionFieldInfo = Mage::getStoreConfig('access/settings/start_restriction');
        $endRestrictionFieldInfo = Mage::getStoreConfig('access/settings/end_restriction');

        $settingsRestrictionCategory = Mage::getStoreConfig('access/settings/restriction_category');
        $restrictionCategory = explode(",", $settingsRestrictionCategory);

        $startRestriction = strtr($startRestrictionFieldInfo, ",", ":");
        $endRestriction = strtr($endRestrictionFieldInfo, ",", ":");

        $helper = Mage::helper('access');
        $restriction = $helper->getRestriction($startRestriction, $endRestriction);

        $items = $observer->getEvent()->getData('cart')->getItems();

            foreach ($items as $item) {

                $categoryIds = $this->getCategoryIds($item['product_id']);

                foreach ($restrictionCategory as $category) {

                    if (in_array($category, $categoryIds)) {

                        if ($restriction['currentDateTime'] >= $restriction['startDateTime']
                            && $restriction['currentDateTime'] <= $restriction['endDateTime']) {

                            $removeItem = $observer->getEvent()->getData('cart')->getQuote();
                            $removeItem->removeItem($item['item_id'])->save();
                        } else if ($restriction['currentDateTime'] < $restriction['startDateTime']
                            && $restriction['currentDateTime'] < $restriction['previousDayEnd']) {

                            $removeItem = $observer->getEvent()->getData('cart')->getQuote();
                            $removeItem->removeItem($item['item_id'])->save();
                        }
                    }
                }
            }
    }
}
