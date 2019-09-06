<?php

class PA_Access_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param array $productCategory
     *
     * @return bool
     */
    public function isRestrictionCategory(array $productCategory): bool
    {
        $settingsRestrictionCategory = Mage::getStoreConfig('access/settings/restriction_category');
        $restrictionCategory = explode(',', $settingsRestrictionCategory);

        foreach ($restrictionCategory as $restriction) {
            if (in_array($restriction, $productCategory)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public function getRestriction(): array
    {
        $startRestrictionFieldInfo = Mage::getStoreConfig('access/settings/start_restriction');
        $endRestrictionFieldInfo = Mage::getStoreConfig('access/settings/end_restriction');

        $startRestriction = strtr($startRestrictionFieldInfo, ',', ':');
        $endRestriction = strtr($endRestrictionFieldInfo, ',', ':');

        $dateTime = new DateTime('now');
        $dateNow = $dateTime->format('Y-m-d');
        $dateTimeNow = $dateTime->format('Y-m-d H:i:s');

        $startDateTime = sprintf('%s %s', $dateNow, $startRestriction);

        if ($startRestriction <= $endRestriction) {
            $endDateTime = sprintf('%s %s', $dateNow, $endRestriction);
            $previousDate = $dateTime->modify('-1 day')->format('Y-m-d');
            $previousDayEnd = sprintf('%s %s', $previousDate, $endRestriction);
        } else {
            $endDate = $dateTime->modify('+1 day')->format('Y-m-d');
            $endDateTime = sprintf('%s %s', $endDate, $endRestriction);
            $previousDayEnd = sprintf('%s %s', $dateNow, $endRestriction);
        }

        return $dateArray = [
            'currentDateTime' => $dateTimeNow,
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
            'previousDayEnd' => $previousDayEnd
        ];

    }
}
