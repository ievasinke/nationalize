<?php
/*
 * Use APIs
 * https://agify.io/
 * https://genderize.io/
 * https://nationalize.io/
 * Allow user to enter the specified input and return the value using the API.
 * Example: for Genderize ask for
 * "Type your name: " and response would be "Name Janis is 100% male"
 * git branch -M main
 * git push -u origin main
 */
require_once 'vendor/autoload.php';

use League\ISO3166\ISO3166;

$name = ucfirst((string)readline("Type your name: "));
$surname = ucfirst((string)readline("Type your surname: "));

$genderContent = json_decode(file_get_contents("https://api.genderize.io?name=$name"));
$ageContent = json_decode(file_get_contents("https://api.agify.io?name=$name"));
$nationalityContent = json_decode(file_get_contents("https://api.nationalize.io?name=$surname"));

function getProbability($probability): int
{
    return $probability * 100;
}

$probabilityGender = getProbability($genderContent->probability);
$probabilityNationality = getProbability($nationalityContent->country[0]->probability);

$countryCode = $nationalityContent->country[0]->country_id;
$country = (new League\ISO3166\ISO3166)->alpha2($countryCode);

echo "Name $name is $probabilityGender% $genderContent->gender\n";
echo "Surname $surname is from {$country['name']} with $probabilityNationality% certainty\n";
echo "$name is $ageContent->age years old\n";