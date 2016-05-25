<?php
// create curl resource 
$ch = curl_init(); 

// set url 
curl_setopt($ch, CURLOPT_URL, 'https://github.com/'); 
curl_setopt($ch, CURLOPT_POST, false);

//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

// $output contains the output string 
$response = curl_exec($ch);

echo '<pre>';

echo '<p>CURL Get Info</p>';
print_r(curl_getinfo($ch));

echo '<p>CURL Error</p>';
print_r(curl_error($ch));

echo '<p>CURL Response</p>';
print_r($response);

die;