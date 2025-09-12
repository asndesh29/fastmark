<?php
namespace App\Generic\GenericDateConverter;
//wrapper around some library
class GenericDateConvertHelper
{

    //pass english date, it will convert to nepali date
    //maybe not suietable for large function etc
    public static function convertEnglishDateToNepaliYMDWithSep($argEngDate, $argSep)
    {
        $cal = new Nepali_Calendar();

        // Split the English date into year, month, and day components
        $englishDateComponents = explode($argSep, $argEngDate);

        if (count($englishDateComponents) === 3) {
            $englishYear = intval($englishDateComponents[0]);
            $englishMonth = intval($englishDateComponents[1]);
            $englishDay = intval($englishDateComponents[2]);

            // Convert the English date to Nepali date
            $nepaliDate = $cal->eng_to_nep($englishYear, $englishMonth, $englishDay);

            // Format the Nepali date as YMD with the specified separator
            return $nepaliDate["year"] . $argSep . $nepaliDate["month"] . $argSep . $nepaliDate["date"];
        } else {
            // Handle invalid input here
            return "Invalid English date format";
        }
    }

    public static function convertNepaliDateToEnglishYMDWithSep($argNepaliDate, $argSep)
    {
        $cal = new Nepali_Calendar();

        // Split the Nepali date into year, month, and day components
        $nepaliDateComponents = explode($argSep, $argNepaliDate);

        if (count($nepaliDateComponents) === 3) {
            $nepaliYear = intval($nepaliDateComponents[0]);
            $nepaliMonth = intval($nepaliDateComponents[1]);
            $nepaliDay = intval($nepaliDateComponents[2]);

            // Convert the Nepali date to English date
            $englishDate = $cal->nep_to_eng($nepaliYear, $nepaliMonth, $nepaliDay);

            // Format the English date as YMD with the specified separator
            return $englishDate["year"] . $argSep . $englishDate["month"] . $argSep . $englishDate["date"];
        } else {
            // Handle invalid input here
            return "Invalid Nepali date format";
        }
    }

    // Example usage:
    // $nepaliDate = convertNepaliDateToEnglishYMDWithSep("2080-02-15", "-");
    // echo "Nepali Date to English: {$nepaliDate}<br>";

    // $englishDate = convertEnglishDateToNepaliYMDWithSep("2023-05-29", "-");
    // echo "English Date to Nepali: {$englishDate}";
}