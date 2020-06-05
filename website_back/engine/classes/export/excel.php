<?php

class Excel
{

  public static function downloadExcel($data, $filename = "data.xls")
  {
    // function cleanData(&$str){
    //   $str = preg_replace("/\t/", "\\t", $str);
    //   $str = preg_replace("/\r?\n/", "\\n", $str);
    //   if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    // }

    // header('Content-Description: File Transfer');
    // header('Content-Type: application/octet-stream');
    // header("Content-Disposition: attachment; filename=\"$filename\"");
    // header('Content-Transfer-Encoding: binary');
    // header('Expires: 0');
    // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    // header('Pragma: public');
    // echo "\xEF\xBB\xBF";

    // $flag = false;
    // foreach($data as $row) {
    //   if(!$flag) {
    //     // display field/column names as first row
    //     echo implode(",", array_keys($row)) . "\r\n";
    //     $flag = true;
    //   }
    //   array_walk($row, 'cleanData');
    //   echo implode(",", array_values($row)) . "\r\n";
    // }


    if (PHP_SAPI == 'cli')
      die('This example should only be run from a Web Browser');

    /** Include PHPExcel */
    require_once ROOT . '/' . DIR_BACK . '/engine/classes/excel/PHPExcel.php';


    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();

    $column = 'A';
    $rowCount = 1;

    foreach ($data[(count($data) - 1)] as $key => $value) {
      $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $key);
      $column++;
    }

    $rowCount = 2;
    foreach ($data as $row) {
      $column = 'A';
      foreach ($row as $value) {

        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $value);
        $column++;
      }
      $rowCount++;
    }

    // Add some data
    // $objPHPExcel->setActiveSheetIndex(0)
    //             ->setCellValue('A1', 'Hello')
    //             ->setCellValue('B2', 'world!')
    //             ->setCellValue('C1', 'Hello')
    //             ->setCellValue('D2', 'world!');

    // Miscellaneous glyphs, UTF-8
    // $objPHPExcel->setActiveSheetIndex(0)
    //             ->setCellValue('A4', 'Miscellaneous glyphs')
    //             ->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Simple');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    ob_end_clean();
    header('Content-Type: text/html; charset=UTF-8');
    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;

  }













  /**
   * PHPExcel
   *
   * Copyright (c) 2006 - 2015 PHPExcel
   *
   * This library is free software; you can redistribute it and/or
   * modify it under the terms of the GNU Lesser General Public
   * License as published by the Free Software Foundation; either
   * version 2.1 of the License, or (at your option) any later version.
   *
   * This library is distributed in the hope that it will be useful,
   * but WITHOUT ANY WARRANTY; without even the implied warranty of
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   * Lesser General Public License for more details.
   *
   * You should have received a copy of the GNU Lesser General Public
   * License along with this library; if not, write to the Free Software
   * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
   *
   * @category   PHPExcel
   * @package    PHPExcel
   * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
   * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt  LGPL
   * @version    ##VERSION##, ##DATE##
   */

  /** Error reporting */
// error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
// date_default_timezone_set('Europe/London');

}



