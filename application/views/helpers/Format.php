<?php

class Zend_View_Helper_Format
{

    function Format() {
        return $this;
    }

	function date($date, $ext = 'compact') {

		if ($ext == 'full') {
			$arrDaysOfWeek = array('Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado');
			$arrMonthsOfYear = array(1 => 'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
			$intDayOfWeek = date('w',strtotime($date));
			$intDayOfMonth = date('d',strtotime($date));
			$intMonthOfYear = date('n',strtotime($date));
			$intYear = date('Y',strtotime($date));

			$fdate = $arrDaysOfWeek[$intDayOfWeek] . ', ' . $intDayOfMonth . ' de ' . $arrMonthsOfYear[$intMonthOfYear] . ' de ' . $intYear;
		}
        else if ($ext == 'medium') {
            if (strlen($date) > 10) $fdate = date('H:i d/m/Y', strtotime($date));
            else $fdate = date('d/m/Y', strtotime($date));
        }
		else {
			$now = time();
			$diff = $now - strtotime($date);


			$timeexploded = explode(' ', date('Y-m-d H:i:s', $now));
			$nowexploded = explode(' ', $date);
			$timeday = $timeexploded[0];
			$nowday = $nowexploded[0];

			if ($diff < -2*24*60*60 && $diff > -7*24*60*60) {
				$fdate = (int)(-$diff/(24*60*60)) .' dias';
			}
			else if ($diff < -24*60*6 && $diff > -7*24*60*60) {
				if ($nowday != $timeday) $fdate = 'amanhã';
				else $fdate = 'hoje';
			}
			else if ($diff < 0 && $diff >= -24*60*6) {
				$fdate = 'hoje';
			}
			else if ($diff < 60 && $diff >= 0) {
				if ($diff < 50) $fdate = 'agora há pouco';
				else $fdate = '1 min atrás';
			}
			else if ($diff < 60*60 && $diff > 0) {
				$fdate = (int)($diff/60)+1 .' min atrás';
			}
			else if ($diff < 24*60*60 && $diff > 0) {
				if ($nowday != $timeday) $fdate = 'ontem';
				else $fdate = (int)($diff/(3600)) .'h atrás';
			}
			else if ($diff < 2*24*60*60 && $diff > 0) {	// yesterday
				$fdate = 'ontem';
			}
			else if ($diff < 3*24*60*60 && $diff > 0) {	// 2 days ago
				$fdate = '2 dias atrás';
			}
			else if ($diff < 4*24*60*60 && $diff > 0) {	// 3 days ago
				$fdate = '3 dias atrás';
			}
			else {
				$arrDaysOfWeek = array('dom','seg','ter','qua','qui','sex','sab');
				$arrMonthsOfYear = array(1 => 'jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez');
				$intDayOfWeek = date('w',strtotime($date));
				$intDayOfMonth = date('d',strtotime($date));
				$intMonthOfYear = date('n',strtotime($date));
				$intYear = date('Y',strtotime($date));

				if ($diff < 8*24*60*60) {	// 7 days ago
					$fdate = $arrDaysOfWeek[$intDayOfWeek] . ', ' . $intDayOfMonth . '/' . $arrMonthsOfYear[$intMonthOfYear];
				}
				else if ($intYear == date('Y', time())) { // this year
					$fdate = $intDayOfMonth . '/' . $arrMonthsOfYear[$intMonthOfYear];
				}
				else  {
					$fdate = $intDayOfMonth . '/' . $arrMonthsOfYear[$intMonthOfYear] . '/' . $intYear;
				}

			}

		}

		return $fdate;
	}


	function phone($phone, $ext = 'region') {
            $explode = explode(' ', $phone);
            $country = $explode[0];
            $region = $explode[1];
            if (strlen($explode[2]) <= 8) {
                $number[0] = substr($explode[2], 0, 4);
                $number[1] = substr($explode[2], 4);
            } 
            else {
                $number[0] = substr($explode[2], 0, 5);
                $number[1] = substr($explode[2], 5);
            }
            if ($ext == 'country') {
                return '+'.$country.' '.$region.' '.$number[0].'-'.$number[1];
            }
            else if ($ext == 'region') {
                if (strlen($number[0]) <= 4) {
                    return $region.' '.$number[0].'-'.$number[1];
                } else {
                    return $region.' '.substr($number[0], 0, 1).' '.substr($number[0], 1, 4).'-'.$number[1];
                }
            }
            else if ($ext == 'phone') {
                return $number[0].'-'.$number[1];
            }
            else {
                return $number[0].$number[1];
            }
	}
}