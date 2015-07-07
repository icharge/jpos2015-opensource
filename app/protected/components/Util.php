<?php

class Util {

    public static function thaiToMySQLDate($date) {
        if (!empty($date)) {
            $arr = explode("/", $date);

            if (count($arr) > 0) {
                if (!empty($arr[2])) {
                $y = $arr[2];
                $m = $arr[1];
                $d = $arr[0];

                return "{$y}-{$m}-{$d}";
              }
            }
        }
    }

    public static function mysqlToThaiDate($date) {
        if ($date == '0000-00-00') {
            return '-';
        }
        if ($date == '0000-00-00 00:00:00') {
            return '-';
        }

        if (!empty($date)) {
            $arr = explode("/", $date);
            $d = '';
            $m = '';
            $y = '';

            if (count($arr) == 3) {
                if (!empty($arr[2])) {
                    $y = $arr[2];
                }
                if (!empty($arr[1])) {
                    $m = $arr[1];
                }
                if (!empty($arr[0])) {
                    $d = $arr[0];
                }

                return "{$d}/{$m}/{$y}";
            }

            $arr = explode(' ', $date);

            if (count($arr) == 2) {
                $arr_date = explode('-', $arr[0]);
                $_time = $arr[1];

                $y = $arr_date[0];
                $m = $arr_date[1];
                $d = $arr_date[2];

                $arr_time = explode(':', $_time);
                $h = $arr_time[0];
                $mi = $arr_time[1];

                return "{$d}/{$m}/{$y} {$h}:{$mi}";
            }
        }
    }

    public static function nowThai() {
        return date("d/m/Y");
    }

    public static function DateThai($strDate) {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strDay = date("j", strtotime($strDate));
        $strMonthCut = Util::monthRange();
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strDay $strMonthThai $strYear";
    }

    public static function monthRange() {
        $monthRange = array(
            '1' => 'มกราคม',
            '2' => 'กุมภาพันธ์',
            '3' => 'มีนาคม',
            '4' => 'เมษายน',
            '5' => 'พฤษภาคม',
            '6' => 'มิถุนายน',
            '7' => 'กรกฏาคม',
            '8' => 'สิงหาคม',
            '9' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม',
        );

        return $monthRange;
    }

    public static function yearRange() {
        $yStart = date('Y') - 5;
        $yEnd = date('Y') + 10;

        for($i = $yStart; $i <= $yEnd; $i++) {
            $years[$i] = $i + 543;
        }

        return $years;
    }

    public static function MonthThai($month) {
        $monthYear = Util::monthRange();

        foreach($monthYear as $keys => $value) {
            if($month == $keys) {
                return $value;
            }
        }
    }

    public static function YearThai($year) {
        return $year + 543;
    }

    public static function MonthYearThai($strDate) {
        $strYear = date("Y", strtotime($strDate)) + 543;
        $strMonth = date("n", strtotime($strDate));
        $strMonthCut = Util::monthRange();
        $strMonthThai = $strMonthCut[$strMonth];

        return "$strMonthThai $strYear";
    }

    public static function convertNumberToText($number) {
      $number_arr = explode(".", $number);
      $number = $number_arr[0];

      $number = str_replace(",", "", $number);
      $arr = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
      $arr_point = array("", "", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน");
      $output = "";
      $count_point = strlen($number);

      for ($i = 0; $i < strlen($number); $i++) {
        $n = $number[$i];
        $text_number = $arr[$n];

        $position_name = $arr_point[$count_point];

        if ($n == 0) {
          $position_name = "";
        }

        // หลักสิบ
        if ($i == strlen($number) - 2) {
          if ($n == 2) {
            $position_name = "ยี่สิบ";
            $text_number = "";
          } else if ($n == 1) {
            $position_name = "สิบ";
            $text_number = "";
          }
        }

        // หลักสุดท้าย
        if ($i == strlen($number) - 1) {
          if ($n == 0) {
            $position_name = "";
            $text_number = "";
          } else if ($n == 1) {
            $position_name = "เอ็ด";
            $text_number = "";
          }
        }

        $output .= "{$text_number}{$position_name}";
        $count_point--;
      }

      $output .= " บาท";

      // สตางค์
      $satang = "";

      if (count($number_arr) > 1) {
        $satang_number = $number_arr[1]; 

        $satang1 = substr($satang_number, 0, 1);
        $satang2 = substr($satang_number, 1, 1);

        $satang1_text = $arr[$satang1];
        $satang2_text = $arr[$satang2];

        // หน่วยเรียก
        $satang1_unit = "";

        if ($satang1 == 1) {
          $satang1_text = "สิบ";
        } else if ($satang1 == 2) {
          $satang1_text = "ยี่สิบ";
        } else {
          if ($satang1 > 0) {
            $satang1_unit = "สิบ";
          }
        }

        // หน่วยท้าย
        if ($satang2 == 1) {
          $satang2_text = "เอ็ด";
        }

        if ($satang_number > 0) {
          $satang .= " {$satang1_text}{$satang1_unit}{$satang2_text} สตางค์";
        }
      }

      $output .= "{$satang}";

      return $output;
    }

    public static function input($data) {
        if (!empty($data)) {
            try {
                if (gettype($data) == 'string') {
                    $data = htmlspecialchars($data);
                    $data = stripslashes($data);
                    $data = str_replace(strtoupper('delete'), '', $data);
                    $data = str_replace(strtoupper('update'), '', $data);
                    $data = trim($data);
                }
            } catch (Exception $e) {

            }
        }
        
        return $data;
    }
}

?>
