<?php
declare(strict_types=1);

/**
 * This script writes in a target file (parameter 1) a
 * phpunit xml file with all the tests as defined in
 * phpunit.xml split in a number of suites provided as parameter 2
 */

$dirname = dirname(__DIR__);
require $dirname . '/vendor/autoload.php';

$targetFile = $argv[1];
$numberOfSuites = $argv[2];
if ($numberOfSuites == 0) {
    echo 'The number of tests per suite cannot be null' . PHP_EOL;
    die;
}

$testClasses = [];
$list = shell_exec('vendor/bin/phpunit --bootstrap ./config/bootstrap.php --list-tests');
$list = explode(PHP_EOL, $list);
foreach ($list as $k => $test) {
    $pos = strpos($test, '::');
    if ($pos > 0) {
        $testClasses[] = trim(substr($test, 0, $pos), ' -');
    }
}
$testClasses = array_unique($testClasses);
$testFiles = [];
foreach ($testClasses as $testClass) {
    $testFiles[] = (new ReflectionClass($testClass))->getFileName();
}

$numberOfTestFilesPerSuite = ceil(count($testFiles) / $numberOfSuites);

$numberOfSuites = ceil(count($testFiles) / $numberOfTestFilesPerSuite);
$testSuites = ['<testsuites>'];
$count = 0;
for ($i = 0; $i < $numberOfSuites; $i++) {
    $testSuites[] = '<testsuite name="' . ($i + 1) . '">';
    $testSuites[] = '<file>../../tests/Lib/ParatestHelperTest.php</file>'; // Add a test not using static fixtures
    for ($j = 0; $j < $numberOfTestFilesPerSuite; $j++) {
        $testFile = $testFiles[$i * $numberOfTestFilesPerSuite + $j] ?? null;
        if (!isset($testFile)) {
            break;
        }
        $testFile = str_replace($dirname, '../..', $testFile);
        $testSuites[] = '<file>' . $testFile . '</file>';
        $count++;
    }
    $testSuites[] = '</testsuite>';
}
$testSuites[] = '</testsuites>';
if ($count != count($testFiles)) {
    echo 'There was an error in the test grouping' . PHP_EOL;
    die;
}

$phpunitFileContent = file_get_contents('phpunit.xml.dist');

$testSuites = implode(PHP_EOL, $testSuites);
$rep = preg_replace('/<testsuites>[\s\S]+?<\/testsuites>/', $testSuites, $phpunitFileContent);
$rep = str_replace('bootstrap="./tests/bootstrap.php"', 'bootstrap="../../tests/bootstrap.php"', $rep);
$rep = str_replace('testCaseClass"./tests/bootstrap.php"', 'bootstrap="../../tests/bootstrap.php"', $rep);

file_put_contents($targetFile, $rep);
echo "$numberOfSuites test suites were written in $targetFile" . PHP_EOL;

return $numberOfSuites;
