<?php

	header("Content-Type: text/plain");

	function dateRange($b, $e) {

		$begin = substr($b, 0, 4) . "-" . substr($b, 4, 2) . "-" . substr($b, 6, 2);
		$end = substr($e, 0, 4) . "-" . substr($e, 4, 2) . "-" . substr($e, 6, 2);

		$range = array();

		#change below to make top redundant, i.e. replace substr to pull from original

		$iDateFrom=mktime(1,0,0,substr($begin,5,2), substr($begin,8,2),substr($begin,0,4));
		$iDateTo=mktime(1,0,0,substr($end,5,2), substr($end,8,2),substr($end,0,4));

		if ($iDateTo>=$iDateFrom)
		{
			array_push($range,date('Ymd',$iDateFrom)); // first entry
			while ($iDateFrom<$iDateTo)
			{
				$iDateFrom+=86400; // add 24 hours
				array_push($range,date('Ymd',$iDateFrom));
			}
		}

		return array_reverse($range);
	}

	$dates = dateRange('20140101', '20141029');

	foreach($dates as $date) {

		
		echo $date . "\n";
	}

	fclose($file);