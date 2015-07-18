<?php

	$begin_date = $_POST["begin_date"];
	$end_date = $_POST["end_date"];

	function search($begin_date, $end_date) {


		$dir = new DirectoryIterator("archive/");

		$files = array();

		foreach ($dir as $fileinfo) {

			if (!$fileinfo->isDot()) {

				$filename = $fileinfo->getFilename();

				$files[] = $filename;
			}
		}

		$return_obj = array();

		foreach ($files as $file) {

				$filedate = substr($file, 0, 8);
				
				$dates_array = dateRange($begin_date, $end_date);

				if (in_array($filedate, $dates_array)) {
					
					$json_file = json_decode(file_get_contents("archive/" . $file));
			
					foreach ($json_file as $json_obj) {
						$file_obj = array(
							"category" => $json_obj->category,
							"location" => $json_obj->location
						);
						$return_obj[] = $file_obj;
					}
			}
			
		}

		header("Content-Type: application/json");
		return json_encode($return_obj);

	}

		
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

		return $range;
	}

	echo search($begin_date, $end_date);