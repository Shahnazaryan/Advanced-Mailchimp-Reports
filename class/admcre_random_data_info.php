<?php
/**
 * Created by PhpStorm.
 * User: Gevorg
 * Date: 15.10.2019
 * Time: 0:09
 */

class admcre_random_data_info
{
    function __construct()
    {
    }
    /**
     * get Random Name for Campaign
     */
    function getNameCampaign($n) {
        $characters = 'ABC';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
    function getName($n) {
        $characters = 'asdfghjklopABCDEFGHIJKQWASZXC';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     * Random get date Time
     */
    function randomDate($startDate, $endDate, $count = 1 ,$dateFormat = 'Y-m-d H:i:s')
    {

        // Convert the supplied date to timestamp
        $minDateString = strtotime($startDate);
        $maxDateString = strtotime($endDate);

        if ($minDateString > $maxDateString)
        {
            throw new Exception("From Date must be lesser than to date", 1);

        }

        for ($ctrlVarb = 1; $ctrlVarb <= $count; $ctrlVarb++)
        {
            $randomDate[] = mt_rand($minDateString, $maxDateString);
        }
        if (sizeof($randomDate) == 1)
        {
            $randomDate = date($dateFormat, $randomDate[0]);
            return $randomDate;
        }elseif (sizeof($randomDate) > 1)
        {
            foreach ($randomDate as $randomDateKey => $randomDateValue)
            {
                $randomDatearray[] =  date($dateFormat, $randomDateValue);
            }
            //return $randomDatearray;
            return array_values(array_unique($randomDatearray));
        }
    }
}