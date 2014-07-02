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

$rootPath = "c:/dev/workspace/trunkmaven/PCCWeb/WebRoot/";//e.g. "c:/projects/blue_phoenix"
$editorPath = "C:\dev\software\HippoEDIT\HippoEdit.exe";//e.g. "c:\apps\sublime\sublime.exe"
$timeLimitPerIteration = 30;//in seconds to push the default execution time limit to (PHP's default is 30)
$iterationBreakCount = 500;//number of files to scan (regardless of type) before forcing a break out
$searchFileExtensions = array('php', 'inc', 'asp', 'html');//e.g. ('php', 'inc', 'html', 'jsp', 'asp')

$expressionOne = "/(<style)/";//this should be found BEFORE expressionTwo
$expressionTwo = "/(<\/head)/";//this should be found AFTER expressionOne


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
echo('<br/>Expression One: <kbd>'.htmlentities($expressionOne).'</kbd><br/>');
echo('<br/>Expression Two: <kbd>'.htmlentities($expressionTwo).'</kbd><br/>');



//get all the files
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::SELF_FIRST);

$processedFileCount = 0;
$matchingFileCount = 0;

$includesMap = array();
$includeFileName = '';
foreach($objects as $object){
	if($object->isFile()){
		$processedFileCount++;
		$fileName = $object->getFilename();
		$pathName = $object->getPathname();
		set_time_limit($timeLimitPerIteration);
		if(isAllowedFileType($fileName, $searchFileExtensions)){
			$matchingFileCount++;
			$fileContents = file_get_contents($pathName);
			$expressionOneFoundCount = preg_match_all($expressionOne, $fileContents, $expressionOneMatches, PREG_OFFSET_CAPTURE);
			$expressionTwoFoundCount = preg_match_all($expressionTwo, $fileContents, $expressionTwoMatches, PREG_OFFSET_CAPTURE);
			//if both are found...
			if($expressionOneFoundCount > 0 && $expressionTwoFoundCount > 0){
				$matchingFileCount++;
				$expressionOneFoundPos = $expressionOneMatches[0][0][1];
				$expressionTwoFoundPos = $expressionTwoMatches[0][0][1];
				//were they found in the correct order?
				if($expressionOneFoundPos < $expressionTwoFoundPos){
					//List all EXPECTED matching files
					echo('<br/>GOOD: Match '.$matchingFileCount.' of '.$processedFileCount.' included the expressions in the correct order ['.$expressionOneFoundPos.', '.$expressionTwoFoundPos.']: '.$pathName);
				} else {
					//List all UNEXPECTED matching files
					echo('<br/><b>FAIL</b>: Match '.$matchingFileCount.' of '.$processedFileCount.' included the expressions in the WRONG order: ['.$expressionOneFoundPos.', '.$expressionTwoFoundPos.']: '.$pathName);
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
