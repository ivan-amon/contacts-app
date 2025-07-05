<?php
require "database.php";

session_start();

$error = null;

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


//Adding the Address
if($_SERVER["REQUEST_METHOD"] == "POST") {

  //Checking form
  if(empty($_POST["address"])) {
      $error = "Please fill all fields";

  } else { //Updating address
    $address = $_POST["address"];
    $statement = $conn->prepare("UPDATE addresses SER address = :address WHERE id = :id");
    $statement->execute([
      ":address" => $_POST["address"],
      ":id" => $id
    ]);
  }

  $_SESSION["flash"] = ["message" => "Address {$address["address"]} Updated"];

  header("Location: home.php");
  return;
}

?>

<?php require "partials/header.php" ?>

  <!-- Add address to a contact -->
  <div class="container pt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Edit</div>
          <div class="card-body">
            <?php if($error != null): ?>
              <p class="text-danger">
                <?= $error ?>
              </p>
            <?php endif?>
            <form method="POST" action="newAddress.php">
              <div class="mb-3 row">
                <label for="address" class="col-md-4 col-form-label text-md-end">Address</label>
  
                <div class="col-md-6">
                  <input value="<?= $address["address"]?>" id="address" type="text" class="form-control" name="address" autocomplete="address" autofocus>
                </div>
              </div>
  
              <div class="mb-3 row">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div> 
<?php require "partials/footer.php" ?>



