<?php
/*
 * Use APIs
 * https://agify.io/
 * https://genderize.io/
 * https://nationalize.io/
 * Allow user to enter the specified input and return the value using the API.
 * Example: for Genderize ask for
 * "Type your name: " and response would be "Name Janis is 100% male"
 */
require_once 'vendor/autoload.php';

$name = ucfirst((string)readline("Type your name: "));
$surname = ucfirst((string)readline("Type your surname: "));

$genderContent = json_decode(file_get_contents("https://api.genderize.io?name=".urlencode($name)));
$ageContent = json_decode(file_get_contents("https://api.agify.io?name=".urlencode($name)));
$nationalityContent = json_decode(file_get_contents("https://api.nationalize.io?name=".urlencode($surname)));

function getProbability(float $probability): int
{
    return round($probability * 100);
}

if (isset($genderContent->probability, $genderContent->gender)) {
    $probabilityGender = getProbability($genderContent->probability);
    echo "Name $name is $probabilityGender% likely to be $genderContent->gender\n";
} else {
    echo "Gender information for $name could not be determined\n";
}

if (isset($nationalityContent->country[0])){
    $probabilityNationality = getProbability($nationalityContent->country[0]->probability);
    $countryCode = $nationalityContent->country[0]->country_id;
    $country = (new League\ISO3166\ISO3166)->alpha2($countryCode);
    echo "Surname $surname is from {$country['name']} with $probabilityNationality% certainty\n";
} else {
    echo "Nationality information for $surname could not be determined\n";
}

if (isset($ageContent->age)) {
    echo "$name is $ageContent->age years old\n";
} else {
    echo "Age information for $name could not be determined\n";
}
