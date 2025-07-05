<?php
require "database.php";

session_start();

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM addresses WHERE id = :id LIMIT 1");
$statement->execute([":id" =>  $id]);
$address = $statement->fetch(PDO::FETCH_ASSOC); 

if(!$address) {
  http_response_code(404);
  echo("HTTP 404: NOT FOUND");
  return;   
}

//Checking if user is authorized, if the address has not linked any contact_id -> "403 Unauhorized"
$statement = $conn->prepare("SELECT * FROM contacts WHERE user_id = :user_id");
$statement->execute([":user_id" => $_SESSION["user"]["id"]]);
$userContacts = $statement->fetchAll(PDO::FETCH_ASSOC);

$authorized = false;

foreach($userContacts as $contact) {
  if($contact["id"] == $address["contact_id"]) {
    $authorized = true;
  }
}

if(!$authorized) {
  http_response_code(403);
  echo("HTTP 403: UNAUTHORIZED");
  return;   
}

//Deleting the address
$statement = $conn->prepare("DELETE FROM addresses WHERE id = :id");
$statement->execute([
  ":id" => $id
]);

$_SESSION["flash"] = ["message" => "Address {$address["address"]} Deleted"];

header("Location: addresses.php");
