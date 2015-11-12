<?php

include 'conn-rir.php';


$connect = odbc_connect("EDINAImports", $username  , $password ); 

//$connect = odbc_connect("Amorgos32", "", ""); //local DSN

 $query = "SELECT        TOP (100) PERCENT DateGenerated, nJournalPapers, nISIJournalPapers, nBooks, nBookChapters, StatID
FROM            Results.dbo.ESPAStatistics
WHERE        (StatID <> 48) AND (StatID <> 95)
ORDER BY DateGenerated";

# perform the query
$result = odbc_exec($connect, $query);
 
 $i=1;
 $dois = ' [\'Date\', \'Other Journal Articles\', \'ISI Journal Articles\', \'Books\', \'Chapters\'],' . PHP_EOL;
  
 $date = '';
while(odbc_fetch_row($result)) {
	$datestr = odbc_result($result,'DateGenerated');
	$date = date('M Y', strtotime($datestr));

$journals = odbc_result($result,'nJournalPapers');
$isi = odbc_result($result,'nISIJournalPapers');
$otherjournals = $journals - $isi;
$books = odbc_result($result,'nBooks');
$chapters = odbc_result($result,'nBookChapters');

if($newdate !=$date) {
$dois .= '[\'' . $date .  '\',' .  $otherjournals . ',' .  $isi . ','. $books . ',' . $chapters . '],' . PHP_EOL;
	 //	echo $i . ' - ' . $result['doi'] . "<br>";
}
$newdate= $date;
	 $i++;
        }
$my_file = 'pubstats.csv';
#$my_file = '../../public_docs/charts/pubstats.csv';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file); //implicitly creates file

fwrite($handle, $dois);+

//echo 'pubs written to ' . $my_file . '<br><br>';

$dbh = null;


print 'done!';
?>