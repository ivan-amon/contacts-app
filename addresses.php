<?php
require "database.php";

session_start();

$user_id = $_SESSION["user"]["id"];

//Get all user addresses
$statement = $conn->prepare("SELECT * FROM contacts WHERE user_id = :user_id LIMIT 1");
$statement->execute([":user_id" =>  $_SESSION["user"]["id"]]);
$userContacts = $statement->fetchAll(PDO::FETCH_ASSOC);

$addresses = [];

//Iterating all user contacts, to get all user addresses
foreach($userContacts as $contact) {
  $statement = $conn->prepare("SELECT * FROM addresses WHERE contact_id = :contact_id");
  $statement->execute([":contact_id" => $contact["id"]]);
  $contactAddresses = $statement->fetchAll(PDO::FETCH_ASSOC);
  
  foreach($contactAddresses as $address) {
    array_push($addresses, $address);
  }
}



?>

<?php require "partials/header.php" ?>
  <div class="container pt-4 p-3">
    <div class="row">

      <?php if(empty($addresses)) : ?>
        <div class="col-md-4 mx-auto">
          <div class="card card-body text-center">
            <p>No addresses saved yet</p>
            <a href="newAddress.php">Add One!</a>
          </div>
        </div>
      <?php endif ?>

      <?php foreach($addresses as $address): ?>
        <div class="col-md-4 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h3 class="card tittle text-capitalize border-0"><?= $address["address"] ?></h3>
              <a href="editAddress.php?id=<?= $address["id"] ?>" class="btn btn-secondary mb-2">Edit Address</a>
              <a href="deleteAddress.php?id<?= $address["id"]?>" class="btn btn-danger mb-2">Delete Address</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php require "partials/footer.php" ?>
