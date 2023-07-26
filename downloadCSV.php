<?php // [COMPLETE 13/7/23]
$listForCSV = array();

require('partials/database.php');

// Create the Array
$event_id = $_GET['eventID'];
$event_name = $_GET['eventName'];

$eventParticipants = getParticipantByEventID($event_id);
$eventParticipants = json_decode($eventParticipants, true);
foreach($eventParticipants as $participant) {
    $participantID = array_values($participant)[0];
    $participantTimestamp = array_keys($participant)[0];
    $participant = new participant($participantID);
    $participantName = $participant->getName();
    $participantPhone = $participant->getPhone();
    $participantEmail = $participant->getEmail();
    // for csv
    $listForCSV[] = array($participantID, $participantName, $participantPhone, $participantEmail, $participantTimestamp);
    // 
}
// End of Create the Array

// Open a file handle for writing using the fopen() function, and pass the file handle and the data array to the fputcsv() function.
$filename = "export.csv";
$filenameClient = "Scandance_" . $event_name . "_" . date("Y-m-d") . ".csv";
$output = fopen($filename, "w");  
fputcsv($output, array('Num.', 'Name', 'Phone Number', 'Email', 'Timestamp')); 

foreach($listForCSV as $row) {  
    fputcsv($output, $row);  
}  
fclose($output);

// Set the Content-Type and Content-Disposition headers to indicate that the file is a CSV file that should be downloaded.
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=$filenameClient");

// Output the contents of the file to the browser.
readfile($filename);
?>