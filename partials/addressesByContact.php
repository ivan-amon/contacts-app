<?php 

//In this file, all the addresses of a contact will be printed (<p> </p>)

$statement = $conn->prepare("SELECT * FROM addresses WHERE contact_id = :contact_id");
$statement->execute([
  ":contact_id" => $contact["id"]
]);

$addresses = $statement->fetchAll(PDO::FETCH_ASSOC);

if(empty($addresses)) { 
?>
  <p class="m-2">No addresses added yet</p>

<?php } else {
  foreach($addresses as $address) : ?>
    <p class="m-2"><?= $address["address"] ?></p>
<?php endforeach; } ?>
