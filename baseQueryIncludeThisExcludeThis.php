<!doctype html>
<html>
<head>
	<style>
		html, body{
			font-family: Arial, Helvetica, sans-serif;
			font-size:9pt;
		}
	</style>
</head>
<body>
<?php
//CONFIGURATION SECTION

$rootPath = "c:/CHANGE_THIS_VALUE/";//e.g. "c:/projects/blue_phoenix"
$editorPath = "c:\CHANGE_THIS_VALUE";//e.g. "c:\apps\sublime\sublime.exe"
$timeLimitPerIteration = 30;//in seconds to push the default execution time limit to (PHP's default is 30)
$iterationBreakCount = 500;//number of files to scan (regardless of type) before forcing a break out
$searchFileExtensions = array('php', 'inc', 'asp');//e.g. ('php', 'inc', 'html', 'jsp', 'asp')

$includesExpression = "/(<input|<select|<button|<textarea)/";//include files that match this RegEx pattern
$excludesExpression = "/(<form)/";//exclude files that match this RegEx pattern


//CORE UTILITIES
function isAllowedFileType($fileName, $searchFileExtensions){
	$fileInfo = pathinfo($fileName);
	if(isset($fileInfo['extension'])){
		$fileExtenstion = $fileInfo['extension'];
		if($fileExtenstion != null && in_array($fileExtenstion, $searchFileExtensions)){
			return true;
		}
	}
	return false;
}

//QUERY SECTION
echo('<br/>Scanning (<b>'.implode($searchFileExtensions, ', ').'</b>) files in: <b>'.$rootPath.'</b><br/>');
echo('<br/>Including files containing: <kbd>'.htmlentities($includesExpression).'</kbd><br/>');
echo('<br/>Excluding files containing: <kbd>'.htmlentities($excludesExpression).'</kbd><br/>');



//get all the files
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::SELF_FIRST);

$processedFileCount = 0;
$matchingFileCount = 0;

foreach($objects as $object){
	if($object->isFile()){
		$processedFileCount++;
		$fileName = $object->getFilename();
		$pathName = $object->getPathname();
		set_time_limit($timeLimitPerIteration);
		if(isAllowedFileType($fileName, $searchFileExtensions)){
			$matchingFileCount++;
			$fileContents = file_get_contents($pathName);
			$includesExpressionFoundCount = preg_match_all($includesExpression, $fileContents, $includeMatches, PREG_SET_ORDER);
			//if found...
			if($includesExpressionFoundCount > 0){
				$excludesExpressionFoundCount = preg_match_all($excludesExpression, $fileContents, $excludeMatches, PREG_SET_ORDER);
				//if *NOT* found...
				if($excludesExpressionFoundCount == 0){
					$matchingFileCount++;
					//List all matching files
					echo('<br/>Match '.$matchingFileCount.' of '.$processedFileCount.' files scanned is: '.$pathName);
				}
			}
		}
	}
	if($processedFileCount >= $iterationBreakCount){
		echo('<br/><br/><span style="color:#e00;">***BREAK - Max file iterations reached ('.$iterationBreakCount.')</span>');
		break;
	}
}
echo('<br/><br/>Complete - <b>'.$matchingFileCount.'</b> files matched out of <b>'.$processedFileCount.'</b> files processed.');
?>
</body>
</html>
