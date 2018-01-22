<?php

namespace app\models;

use Yii;

class Helper
{

    static public function toGeorgian($jdt, $str)
    {

        list($j_y, $j_m, $j_d) = explode('/', $jdt);
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);


        $jy = (int)($j_y) - 979;
        $jm = (int)($j_m) - 1;
        $jd = (int)($j_d) - 1;

        $j_day_no = 365 * $jy + Helper::div($jy, 33) * 8 + Helper::div($jy % 33 + 3, 4);

        for ($i = 0; $i < $jm; ++$i)
            $j_day_no += $j_days_in_month[$i];

        $j_day_no += $jd;

        $g_day_no = $j_day_no + 79;

        $gy = 1600 + 400 * Helper::div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
        $g_day_no = $g_day_no % 146097;

        $leap = true;
        if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ {
            $g_day_no--;
            $gy += 100 * Helper::div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
            $g_day_no = $g_day_no % 36524;

            if ($g_day_no >= 365)
                $g_day_no++;
            else
                $leap = false;
        }

        $gy += 4 * Helper::div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = false;

            $g_day_no--;
            $gy += Helper::div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }

        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
            $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i + 1;
        $gd = $g_day_no + 1;
        if ($str)
            return $gy . '/' . $gm . '/' . $gd;
        return array($gy, $gm, $gd);
    }

    static public function jalaliToUTS($jdt)
    {
        if (!empty($jdt)) {
            $georgian = Helper::toGeorgian($jdt, 0);
            return mktime(0, 0, 0, $georgian[1], $georgian[2], $georgian[0]);
        } else {
            return null;
        }
    }

    static public function div($a, $b)
    {
        return (int)($a / $b);
    }

    static public function getCurrentTime()
    {
        return time();
    }

    static public function ShowInBox($message, $data)
    {
        echo "</br><pre style=\"text-align:center;\">" .
            $message . ":" . $data . "</pre>";
    }

    public static function getUnixTimeFromShamsiDate($shamsiDate, $hasTime = true)
    {
        if ($shamsiDate) {
            $time = '';
            if ($hasTime) {
                $time = ' ' . explode(' ', $shamsiDate)[0];
                $date = explode('-', explode(' ', $shamsiDate)[1]);
            } else {
                $date = explode('-', $shamsiDate);
            }

            $gDate = Yii::$app->jdate->toGregorianDate($date[0], $date[1], $date[2]);
            $gDate = $gDate[0] . '-' . $gDate[1] . '-' . $gDate[2] . $time;
            return strtotime($gDate);
        }
    }
}
