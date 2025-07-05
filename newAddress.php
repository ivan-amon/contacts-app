<?php
require "database.php";

session_start();

$error = null;

if(!isset($_SESSION["user"])) {
  header("Location: login.php");
  return;
}

//Check if the user has contacts to add addresses
$statement = $conn->prepare("SELECT * FROM contacts WHERE user_id = :user_id");
$statement->bindParam(":user_id", $_SESSION["user"]["id"]);
$statement->execute();

$contacts = $statement->fetchAll(PDO::FETCH_ASSOC);

//Adding the Address
if($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = $_POST["name"];
  $phone_number = $_POST["phone_number"];

  $statement = $conn->prepare("SELECT * FROM contacts WHERE name = :name AND phone_number = :phone_number LIMIT 1");
  $statement->execute([
    ":name" => $_POST["name"],
    ":phone_number" => $_POST["phone_number"]
  ]);

  $contact = $statement->fetch(PDO::FETCH_ASSOC);

  //Checking form & if contact exists
  if(empty($_POST["address"]) || empty($_POST["name"]) || empty($_POST["phone_number"])) {
      $error = "Please fill all fields";
  } else if($statement->rowCount() == 0) {
      $error = "Contact no found";

  } else { //Adding Address to the contact
    $statement = $conn->prepare("INSERT INTO addresses (contact_id, address) VALUES (:contact_id, :address)");
    $statement->execute([
      ":contact_id" => $contact["id"],
      ":address" => $_POST["address"]
    ]);
  }
}

?>

<?php require "partials/header.php" ?>

  <!-- No contacts created yet -->
  <?php if(empty($contacts)): ?>
    <div class="col-md-4 mx-auto pt-4">
      <div class="card card-body text-center">
        <p>You need to add a contact before adding an address</p>
        <a href="add.php">Add One!</a>
      </div>
    </div> 
  <?php endif ?>

  <!-- Add address to a contact -->
  <?php if(!empty($contacts)) : ?>
    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Add New Address to a Contact</div>
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
                    <input id="address" type="text" class="form-control" name="address" autocomplete="address" autofocus>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Contact Name</label>
    
                  <div class="col-md-6">
                    <input id="name" type="tel" class="form-control" name="name" autocomplete="name" autofocus>
                  </div>
                </div>
    
                <div class="mb-3 row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-end">Contact Phone Number</label>
    
                  <div class="col-md-6">
                    <input id="phone_number" type="tel" class="form-control" name="phone_number" autocomplete="phone_number" autofocus>
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
  <?php endif ?>
<?php require "partials/footer.php" ?>
