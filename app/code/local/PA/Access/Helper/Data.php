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
        $restrictionCategory = explode(",", $settingsRestrictionCategory);

        foreach ($restrictionCategory as $restriction) {
            if (in_array($restriction, $productCategory)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $startRestriction
     * @param string $endRestriction
     *
     * @return array
     *
     * @throws Exception
     */
    public function getRestriction(string $startRestriction, string $endRestriction): array
    {
        $dateTime = new DateTime('now');
        $dateNow = $dateTime->format('Y-m-d');
        $dateTimeNow = $dateTime->format('Y-m-d H:i:s');

        $currentDateTime = $dateTimeNow;
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

        $dateArray = [
            'currentDateTime' => $currentDateTime,
            'startDateTime' => $startDateTime,
            'endDateTime' => $endDateTime,
            'previousDayEnd' => $previousDayEnd
        ];

        return $dateArray;
    }
}
