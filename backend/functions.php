<?php
include "db.php";
session_start();
include"error.php";
function Updateuser(){
    global $mysqli;
    if (isset($_POST['save'])) {
        $query = "UPDATE users SET username = ?, name = ? , email = ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
//        $options = ['cost' => 12,];
//        $wachtwoord = password_hash($_POST['Wachtwoord'], PASSWORD_BCRYPT, $options);
        $stmt->bind_param('sssi', $_POST['username'], $_POST['name'], $_POST['email'], $_SESSION['id']);
        $stmt->execute();
    }
}
function InsertBedrijf()
{
    global $mysqli;
    if (isset($_POST['registreerBedrijf'])) {
        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        $patternZip = '^/[1-9][0-9]{3} ?(?!sa|sd|ss|SA|SD|SS)[A-Za-z]{2}$/';
        $re = '/^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)[1-9]((\s|\s?\-\s?)?[0-9])((\s|\s?-\s?)?[0-9])((\s|\s?-\s?)?[0-9])\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]$/m';
        if (
            empty($_POST['website']) || empty($_POST['bedrijfsnaam']) ||
            empty($_POST['email']) || empty($_POST['straatnaam']) ||
            empty($_POST['huisnummer']) || empty($_POST['postcode'])) {
            $error = 'Please fill all required fields!';
        } else {
            if (!preg_match("/^[a-zA-Z0-9 .]+$/", $_POST['bedrijfsnaam'])) {
                $error = "Invalid Name";
            } elseif (!preg_match($re, $_POST['telefoonnummer'])) {
                $error = "Invalid Phone number";
            } elseif (preg_match($pattern, $_POST['email']) === 0) {
                $error = "Invalid Email";
            }
            else {
                $sql = "INSERT INTO `organisation`(`name`,`street`,
                                 `housenumber`,`housenumberAddition`,`postalcode`,`website`,
                                 `phoneNumber`,`email`)VALUES(?,?,?,?,?,?,?,?)";

                $stmt = $mysqli->prepare($sql);
                $bedrijfsnaam = ucwords($_POST['bedrijfsnaam']);
                $stmt->bind_param(
                    "ssssssss",
                    $_POST['bedrijfsnaam'],
                    $_POST['straatnaam'],
                    $_POST['huisnummer'],
                    $_POST['huisnummertoevoeging'],
                    $_POST['postcode'],
                    $_POST['website'],
                    $_POST['telefoonnummer'],
                    $_POST['email']
                );

                $stmt->execute();
                $stmt->close();
                $mysqli->close();
                header(
                    "Location:bedrijfs_overzicht.php"
                );
            }
        }
    }
}

//Functie voor het ophalen van bedrijfsgegevens
//Functie gebruikt de GET method om uit te vinden van welk bedrijf de gegevens moeten komen
function GetCompanyInfo(){
    global $mysqli;
    $sql = "SELECT * FROM `organisation` WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i",$_GET["membof"]);
    $stmt->execute();
    $result = $stmt->get_result();
    return  $result->fetch_array();
}
function UpdateCompanyInfo(){
    global $mysqli;
    if (isset($_POST['formSettings'])) {
        $query = "UPDATE `organisation` SET `name`=?,`street`=?,`housenumber`=?,`housenumberAddition`=?,
                          `postalcode`=?,`website`=?,`phoneNumber`=?,`email`=? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssisssssi', $_POST["name"], $_POST["street"], $_POST["huisnummer"],
            $_POST["toevoeging"], $_POST["postcode"], $_POST["website"], $_POST["telefoon"], $_POST["email"], $_GET["membof"]);
        $stmt->execute();
    }
}


function Getuser(){
    global $mysqli;
    $sql = "SELECT * FROM users where id= ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_array();
}


function Changepassword()
{
    global $mysqli;
    if (isset($_POST['save_password'])) {
        $sql = 'SELECT password FROM `users` WHERE id = ?';
        if ($stmt = $mysqli->prepare($sql)) {
            $user = $_SESSION['id'];
            $stmt->bind_param('i', $user);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($password);
            $stmt->fetch();
            if ($stmt->num_rows > 0) {
                if ($_POST['old-password'] == $password) {
                    if ($_POST['new-password'] == $_POST['retype-new-password']) {
                        $query = "UPDATE `users` SET password = ? WHERE id = ?";
                        $stmt = $mysqli->prepare($query);
                        $stmt->bind_param('si', $_POST['retype-new-password'], $_SESSION['id']);
                        $stmt->execute();
                    }else{
                        header("Location:../page-account-settings.php?login=fout");
                    }
                } else {
                    header("Location:../page-account-settings.php?login=leeg");

                }
            }
        }
    }
}


function qron(){
    global $mysqli;
    if (isset($_POST['set'])) {
        $sql = "UPDATE `users` SET `googlecode` = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "si",
            $_POST['secret'],
            $_SESSION['id']
        );
        $stmt->execute();
    }
}

function qroff(){
    global $mysqli;
    if (isset($_POST['del'])){
        $string = "";
        $sql_d = "UPDATE `users` SET `googlecode` = ? WHERE id = ?";
        $stmt = $mysqli->prepare($sql_d);
        $stmt->bind_param(
            "si", $string ,$_SESSION['id']
        );
        $stmt->execute();
    }
}

function UploadPic1()
{
    if (isset($_POST['submitpic']) && isset($_FILES['my_image'])) {
        global $mysqli;

        echo "<pre>";
        print_r($_FILES['my_image']);
        echo "</pre>";

        $img_name = $_FILES['my_image']['name'];
        $img_size = $_FILES['my_image']['size'];
        $tmp_name = $_FILES['my_image']['tmp_name'];
        $error = $_FILES['my_image']['error'];

        if ($error === 0) {
            if ($img_size > 1250000) {
                $em = "Sorry, your file is too large.";
                header("Location: index.php?error=$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = 'uploads/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    // Insert into Database
                    $sql_pic = "UPDATE `users` SET `image_url` = ? WHERE id = ?";
                    $stmt = $mysqli->prepare($sql_pic);
                    $stmt->bind_param(
                        "si", $new_img_name, $_SESSION['id']
                    );
                    $stmt->execute();
                    header("Location: page-account-settings.php");
                } else {
                    $em = "You can't upload files of this type";
                    header("Location: index.php?error=$em");
                }
            }
        }
    }
}

function Getpersonnel()
{
    global $mysqli;
    $DataCustomer = "SELECT personnel.id,personnel.first_name,personnel.last_name_prefix,
        personnel.last_name,personnel.street,personnel.housenumber,personnel.email,
        personnel.housenumberAddition,personnel.postalcode,personnel.phoneNumber,personnel.authentication_level
 FROM personnel 
     LEFT JOIN organisation 
         ON personnel.member_of = organisation.id 
 WHERE personnel.member_of = ?";
    $stmt = $mysqli->prepare($DataCustomer);
    $stmt->bind_param("i", $_GET["membof"]);
    $stmt->execute();
    $resultPersonnel = $stmt->get_result();
    while ($rowPersonnel = mysqli_fetch_array($resultPersonnel)) {
        ?>
        <tr>
            <td><?= $rowPersonnel["id"] ?></td>
            <td><?= $rowPersonnel["first_name"]. " ".$rowPersonnel["last_name_prefix"]. " ". $rowPersonnel["last_name"]?></td>
            <td><?= $rowPersonnel["email"] ?></td>
            <td><?= $rowPersonnel["phoneNumber"] ?></td>
            <td>
                <?php
                if ($rowPersonnel["authentication_level"] === "Bedrijfsleider") { ?>
                    <span class="badge gradient-purple-bliss" >Bedrijfsleider</span>
                <?php } elseif ( $rowPersonnel["authentication_level"] === "Werknemer") { ?>
                    <span class="badge">Werknemer</span>
                <?php } ?>
            </td>
            <td>
                <div class="row">
                    <div class="col-md-0">
                    </div>
                    <div class="col-md-5">
                        <a href="#" data-toggle="modal" data-target="#editPersonnel<?= $rowPersonnel["id"]?>">
                            <i class="ft-edit"></i>
                        </a>
                    </div>
                    <div class="col-md-5">
                        <a data-toggle="modal" data-target="#info<?= $rowPersonnel["id"]?>" href="modals.php?<?= $rowCustomerP["id"] ?>">
                            <i class="ft-eye"></i>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <?php
    }
}
function GetCompanyPersonneel(){
    global $mysqli;
    $tableData = "SELECT * FROM `organisation`";
    $stmt = $mysqli->prepare($tableData);
    $stmt->execute();
    $resultData = $stmt->get_result();
    while ($row = $resultData->fetch_array()) {
        ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><a href="gebruikers.php?membof=<?= $row["id"] ?>"><?= $row["name"] ?></a></td>
            <!--            <td>--><?//= $row["logo"]?><!--</td>-->
        </tr>
        <?php
    }
}

function GetCompany(){
    global $mysqli;
    $tableData = "SELECT * FROM `organisation`";
    $stmt = $mysqli->prepare($tableData);
    $stmt->execute();
    $resultData = $stmt->get_result();
    while ($row = $resultData->fetch_array()) {
        ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><a href="bedrijfs_klanten_overzicht.php?custof=<?= $row["id"] ?>&membof=<?= $row["id"] ?>"><?= $row["name"] ?></a></td>
            <td><?= $row["logo"]?></td>
        </tr>
        <?php
    }
}

function password_reset($password,$confirmpassword,$email){
    global $mysqli;
    if (empty($password) or empty($confirmpassword)) {
        exit('Not all fields are filled in.');
    }
    if ($password == $confirmpassword) {
        $sqlUpdate = "UPDATE `users` SET `password`= ? WHERE email  = ?";
        $stmt = $mysqli->prepare($sqlUpdate);
        $stmt->bind_param('ss',$password,$email);
        $stmt->execute();
        echo "Gelukt!";
    } else {
        echo "Passwords do not match.";
    }
}


function Updatepersonnel()
{
    global $mysqli;
    if (isset($_POST['submit'])) {
        $query = "UPDATE `personnel` SET `first_name`=?,`last_name_prefix`=?,`last_name`=?,
                                `street`= ?,`housenumber`=?, `postalcode`=?,`phoneNumber`=?,
                                `email`= ?, `authentication_level`=? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssssssi', $_POST["voornaam"], $_POST["tussenvoegsel"], $_POST["achternaam"]
            , $_POST["straat"], $_POST["huisnummer"], $_POST["postcode"],
            $_POST["telefoonnummer"], $_POST["email"],$_POST["function"], $_POST["id"]);
        $stmt->execute();
    }
}
function InsertPersonnel1()
{
    global $mysqli;
    if (isset($_POST['registreerWerknemer'])) {

        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        $re = "(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)";
        $reStraat = '^[a-zA-Z ]*$';
        if (
            empty($_POST['voornaam']) || empty($_POST['achternaam']) ||
            empty($_POST['email']) || empty($_POST['straat']) ||
            empty($_POST['huisnummer']) || empty($_POST['postcode'])) {
            header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=empty");
            exit();
        } else {
            if (!preg_match("/^[a-zA-Z]+$/", $_POST['voornaam']) && !preg_match("/^[a-zA-Z]+$/", $_POST['achternaam'])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=naamfout");
                exit();
            } elseif (preg_match($re, $_POST['telefoonnummer'])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=telfout");
                exit();
            } elseif (preg_match($pattern, $_POST['email']) === 0) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=mailfout");
                exit();
            }elseif (preg_match($reStraat, $_POST["straat"])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=straatfout");
                exit();
            }elseif (!preg_match("/^[1-9][0-9]{3} ?(?!sa|sd|ss|SA|SD|SS)[A-Za-z]{2}$/", $_POST['postcode'])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=postcodefout");
                exit();
            } else {
                $query = "SELECT * FROM `personnel` WHERE email = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("s", $_POST["email"]);
                $stmt->execute();
                if (!$stmt->execute()) {
                    die('Error: ' . $mysqli->error);
                }
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=emaildupli");
                    exit();
                } elseif ($result->num_rows == 0) {

                    $sql = "INSERT INTO `personnel`(`first_name`, `last_name_prefix`, `last_name`, `street`,
                                   `housenumber`,`postalcode`, `phoneNumber`, `email`, `authentication_level`,  `member_of`) VALUES (?,?,?,?,?,?,?,?,?,?)";

                    $stmt = $mysqli->prepare($sql);
                    $voornaam = ucwords($_POST['voornaam']);
                    $achternaam = 	ucwords($_POST['achternaam']);
                    $stmt->bind_param("ssssisisii",$voornaam , $_POST['tussenvoegsel'],$achternaam, $_POST['straat'],$_POST['huisnummer'], $_POST['postcode'], $_POST['telefoonnummer'], $_POST['email'],$_POST['function'], $_GET["membof"]);

                    $stmt->execute();
                    $stmt->close();
                    $mysqli->close();
                    header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenMemb=succes");
                }
            }
        }
    }
}
InsertPersonnel1();
function Getpersonnel1()
{
    global $mysqli;
    $Datapersonnel = "SELECT * FROM personnel WHERE id = ?";
    $stmt = $mysqli->prepare($Datapersonnel);
    $stmt->bind_param("i",$_GET["id"]);
    $stmt->execute();
    $resultpersonnel = $stmt->get_result();
    return  $resultpersonnel->fetch_array();
}
Getpersonnel1();

function InsertCustomerIndividual()
{
    global $mysqli;
    if (isset($_POST['registreerParticulier'])) {

        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        $re = "(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)";
        $reStraat = '^[a-zA-Z ]*$';

        if (
            empty($_POST['voornaam_p']) || empty($_POST['achternaam_p']) ||
            empty($_POST['email_p']) || empty($_POST['straatnaam_p']) ||
            empty($_POST['huisnummer_p']) || empty($_POST['postcode_p'])) {
            header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=empty");
            exit();
        } else {
            if (!preg_match("/^[a-zA-Z]+$/", $_POST['voornaam_p']) && !preg_match("/^[a-zA-Z]+$/", $_POST['achternaam_p'])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=namefout");
                exit();
            } elseif (preg_match($re, $_POST['telefoonnummer_p'])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=telfout");
                exit();
            } elseif (preg_match($pattern, $_POST['email_p']) === 0) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=mailfout");
                exit();
            }elseif (preg_match($reStraat, $_POST["straatnaam_p"])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=straatfout");
                exit();
            }elseif (!preg_match("/^[1-9][0-9]{3} ?(?!sa|sd|ss|SA|SD|SS)[A-Za-z]{2}$/", $_POST['postcode_p'])) {
                header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=postcodefout");
                exit();
            }else {
                $query = "SELECT * FROM `customers_individual` WHERE email = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("s", $_POST["email_p"]);
                $stmt->execute();
                if (!$stmt->execute()) {
                    die('Error: ' .$mysqli->error);
                }
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=emaildupli");
                    exit();
                } elseif ($result->num_rows == 0) {

                    $sql = "INSERT INTO `customers_individual`(`id`, `first_name`,`last_name_prefix`, `last_name`, `street`,
                                   `housenumber`, `housenumberAddition`,
                                   `postalcode`, `phoneNumber`,email,customer_of) VALUES ('',?,?,?,?,?,?,?,?,?,?)";

                    $stmt = $mysqli->prepare($sql);
                    $voornaam = ucwords($_POST['voornaam_p']);
                    $achternaam = ucwords($_POST['achternaam_p']);
                    $stmt->bind_param("sssssssssi",$voornaam ,
                        $_POST['tussenvoegsel_p'],$achternaam, $_POST['straatnaam_p'],
                        $_POST['huisnummer_p'], $_POST['huisnummertoevoeging_p'],
                        $_POST['postcode_p'], $_POST['telefoonnummer_p'], $_POST['email_p']
                        , $_GET["custof"]);

                    $stmt->execute();
                    $stmt->close();
                    $mysqli->close();
                    header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=succes");
                    exit();
                }
            }
        }
    }
}
InsertCustomerIndividual();

function InsertCustomerBusiness()
{
    global $mysqli;
    if (isset($_POST['registreerZakelijk'])) {

        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        $re = "(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{10}$)";
        $reStraat = '^[a-zA-Z ]*$';
        if (
            empty($_POST['voornaam_z']) || empty($_POST['achternaam_z']) ||
            empty($_POST['email_z']) || empty($_POST['straatnaam_z']) ||
            empty($_POST['huisnummer_z']) || empty($_POST['postcode_z'])) {
//header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenPart=empty");
            exit();
        } else {
            if (!preg_match("/^[a-zA-Z]+$/", $_POST['voornaam_z']) && !preg_match("/^[a-zA-Z]+$/", $_POST['achternaam_z'])) {
                // header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenZak=namefout");
                exit();
            } elseif (preg_match($re, $_POST['telefoonnummer_z'])) {
                // header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenZak=telfout");
                exit();
            } elseif (preg_match($pattern, $_POST['email_z']) === 0) {
                //header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenZak=mailfout");
                exit();
            }elseif (preg_match($reStraat, $_POST["straatnaam_z"])) {
                // header("Location:../bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenZak=straatfout");
                exit();
            }elseif (!preg_match("/^[1-9][0-9]{3} ?(?!sa|sd|ss|SA|SD|SS)[A-Za-z]{2}$/", $_POST['postcode_z'])) {
                //header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenZak=postcodefout");
                exit();
            } else {
                $query = "SELECT * FROM `customers_business` WHERE email = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("s", $_POST["email_z"]);
                $stmt->execute();
                if (!$stmt->execute()) {
                    die('Error: ' . $stmt->error);
                }
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {

                    // header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenZak=emaildupli");
                    exit();
                } elseif ($result->num_rows== 0) {

                    $sql = "INSERT INTO `customers_business`(`id`,`first_name`,`last_name_prefix`,`last_name`,`street`,
                                 `housenumber`,`housenumberAddition`,`postalcode`,`phoneNumber`,
                                 `email`,`business`,`customer_of`)VALUES('',?,?,?,?,?,?,?,?,?,?,?)";

                    $stmt = $mysqli->prepare($sql);
                    $voornaam = ucwords($_POST['voornaam_z']);
                    $achternaam = ucwords($_POST['achternaam_z']);
                    $bedrijfsnaam = ucwords($_POST['bedrijfsnaam']);
                    $stmt->bind_param("ssssssssssi",$voornaam,
                        $_POST['tussenvoegsel_z'],$achternaam, $_POST['straatnaam_z'],
                        $_POST['huisnummer_z'], $_POST['huisnummertoevoeging_z'],
                        $_POST['postcode_z'], $_POST['telefoonnummer_z'],
                        $_POST['email_z'],$bedrijfsnaam , $_GET["custof"]);

                    $stmt->execute();
                    $stmt->close();
                    $mysqli->close();
                    header("Location:bedrijfs_klanten_overzicht.php?custof=". $_GET["custof"] . "&membof=" . $_GET["membof"] . "&toevoegenZak=succes");
                    exit();
                }
            }
        }
    }
}
InsertCustomerBusiness();

function BusinessSelector()
{
    global $mysqli;
    $data = "SELECT * FROM `organisation`";
    $stmt = $mysqli->prepare($data);
    $stmt->execute();
    $resultData = $stmt->get_result();
    while ($row = $resultData->fetch_array()) {
        ?>
        <option value="" selected hidden>Kiezen....</option>
        <option value="<?= $row["id"] ?>"><?= $row["name"] ?></option>
        <?php
    }
}
function StatusSelector() {
    if (!empty($_POST['status'])) {
        global $mysqli;
        $query = "UPDATE `customers_individual` SET `status`=? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $_POST["status"], $_POST["id_p"]);
        $stmt->execute();
    }
}


function UpdateCustomerB()
{
    global $mysqli;
    if (isset($_POST['saveBusiness'])) {
        $query = "UPDATE `customers_business` SET `first_name`=?,`last_name_prefix`=?,`last_name`=?,
                                `street`= ?,`housenumber`=?,`housenumberAddition`=?,`postalcode`=?,`phoneNumber`=?,
                                `email`= ?,`status`= ?,`business`=? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
//        $options = ['cost' => 12,];
//        $wachtwoord = password_hash($_POST['Wachtwoord'], PASSWORD_BCRYPT, $options);
        $stmt->bind_param('sssssssssssi', $_POST["voornaam_z"], $_POST["tussenvoegsel_z"], $_POST["achternaam_z"]
            , $_POST["straatnaam_z"], $_POST["huisnummer_z"], $_POST["huisnummertoevoeging_z"], $_POST["postcode_z"],
            $_POST["telefoonnummer_z"], $_POST["email_z"], $_POST["status"], $_POST["bedrijfsnaam"],$_POST["id_z"]);
        $stmt->execute();
    }
}
UpdateCustomerB();

function UpdateCustomerI()
{
    global $mysqli;
    if (isset($_POST['saveIndividual'])) {
        $query = "UPDATE `customers_individual` SET `first_name`=?,`last_name_prefix`=?,`last_name`=?,
                                `street`= ?,`housenumber`=?,`housenumberAddition`=?,`postalcode`=?,`phoneNumber`=?,
                                `email`= ?, `status`= ? WHERE id = ?";
        $stmt = $mysqli->prepare($query);
//        $options = ['cost' => 12,];
//        $wachtwoord = password_hash($_POST['Wachtwoord'], PASSWORD_BCRYPT, $options);
        $stmt->bind_param('ssssssssssi', $_POST["voornaam_p"], $_POST["tussenvoegsel_p"], $_POST["achternaam_p"]
            , $_POST["straatnaam_p"], $_POST["huisnummer_p"], $_POST["huisnummertoevoeging_p"], $_POST["postcode_p"],
            $_POST["telefoonnummer_p"], $_POST["email_p"], $_POST["status"], $_POST["id_p"]);
        $stmt->execute();
    }
}

UpdateCustomerI();

function GetCustomerZ()
{
    global $mysqli;
    $DataCustomer = "SELECT customers_business.id,customers_business.status,customers_business.first_name,customers_business.last_name_prefix,
        customers_business.last_name,customers_business.street,customers_business.housenumber,customers_business.housenumberAddition,
        customers_business.housenumberAddition,customers_business.postalcode,customers_business.phoneNumber,customers_business.business,
       customers_business.customer_of
 FROM customers_business 
     LEFT JOIN organisation 
         ON customers_business.customer_of = organisation.id 
 WHERE customers_business.customer_of = ?";
    $stmt = $mysqli->prepare($DataCustomer);
    $stmt->bind_param("i", $_GET["custof"]);
    $stmt->execute();
    $resultCustomer = $stmt->get_result();

    while ($rowCustomer = $resultCustomer->fetch_array()) {
        ?>
        <tr>
            <td><?= $rowCustomer["id"] ?></td>
            <td><?= $rowCustomer["first_name"] . " " . $rowCustomer["last_name_prefix"] . " " . $rowCustomer["last_name"]?></td>
            <td><?= $rowCustomer["phoneNumber"] ?></td>
            <td><?= $rowCustomer["business"] ?></td>
            <td><?php
                if ($rowCustomer["status"] === "Inactief") { ?>
                    <span class="badge bg-light-danger">Inactief</span>
                <?php } elseif ($rowCustomer["status"] === "Actief") { ?>
                    <span class="badge bg-light-succes">Actief</span>
                <?php } ?>			</td>
            <td>
                <div class="row">
                    <div class="col-md-0">
                    </div>
                    <?php
                    if ($_SESSION['auth'] == "Bedrijfsleider"||$_SESSION['auth'] == "Admin" || $_SESSION['auth'] == 'Werknemer') {
                        ?>
                        <div class="col-md-5">
                            <a href="#" data-toggle="modal" data-target="#editZ<?= $rowCustomer["id"]?>">
                                <i class="ft-edit"></i>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-md-5">
                        <a data-toggle="modal" data-target="#info<?= $rowCustomer["id"]?>" href="modals.php?<?= $rowCustomerP["id"] ?>">
                            <i class="ft-eye"></i>
                        </a>
                    </div>
                </div>
        </tr>
        <?php
    }
}

function GetCustomerP()
{
    global $mysqli;
    $DataCustomer_P = "SELECT customers_individual.id,customers_individual.status,customers_individual.first_name,customers_individual.last_name_prefix,
        customers_individual.last_name,customers_individual.street,customers_individual.housenumber,customers_individual.housenumberAddition,
        customers_individual.housenumberAddition,customers_individual.postalcode,customers_individual.phoneNumber,customers_individual.email,
       customers_individual.customer_of
 FROM customers_individual 
     LEFT JOIN organisation 
         ON customers_individual.customer_of = organisation.id 
 WHERE customers_individual.customer_of = ?";
    $stmt = $mysqli->prepare($DataCustomer_P);
    $stmt->bind_param("i", $_GET["custof"]);
    $stmt->execute();
    $resultCustomer = $stmt->get_result();

    while ($rowCustomerP = $resultCustomer->fetch_array()) {
        ?>

        <tr>
            <td><?= $rowCustomerP["id"] ?></td>
            <td><?= $rowCustomerP["first_name"] . " " . $rowCustomerP["last_name_prefix"] . " " . $rowCustomerP["last_name"]?></td>
            <td><?= $rowCustomerP["street"] . " " . $rowCustomerP["housenumber"] . " " . $rowCustomerP["housenumberAddition"] ?></td>
            <td><?= $rowCustomerP["phoneNumber"] ?></td>
            <td><?php
                if ($rowCustomerP["status"] === "Inactief") { ?>
                    <span class="badge bg-light-danger">Inactief</span>
                <?php } elseif ($rowCustomerP["status"] === "Actief") { ?>
                    <span class="badge bg-light-succes">Actief</span>
                <?php } ?>			</td>
            <td>
                <div class="row">
                    <div class="col-md-0">
                    </div>
                    <?php
                    if ($_SESSION['auth'] == "Bedrijfsleider" || $_SESSION['auth'] == "Admin" || $_SESSION['auth'] = "Werknemer") {
                        ?>
                        <div class="col-md-5">
                            <a href="#" data-toggle="modal" data-target="#editP<?= $rowCustomerP["id"]?>">
                                <i class="ft-edit"></i>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="col-md-5">
                        <a data-toggle="modal" data-target="#info<?= $rowCustomerP["id"]?>" href="modals.php?<?= $rowCustomerP["id"] ?>">
                            <i class="ft-eye"></i>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>

    <?php
}
function GetCompanyName(){
    if (isset($_GET["custof"])){
        global $mysqli;
        $sql = "SELECT name from `organisation` WHERE id = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $_GET["custof"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()){
            echo $data["name"];
        }
    }
}
function GetCompanyNamePersonnel(){
    if (isset($_GET["membof"])){
        global $mysqli;
        $sql = "SELECT name from `organisation` WHERE id = ? ";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $_GET["membof"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($data = $result->fetch_assoc()){
            echo $data["name"];
        }
    }
}
function ViewUserP()
{
    global $mysqli;
    $DataCustomer_P = "SELECT customers_individual.id,customers_individual.status,customers_individual.first_name,customers_individual.last_name_prefix,
        customers_individual.last_name,customers_individual.street,customers_individual.housenumber,customers_individual.housenumberAddition,
        customers_individual.housenumberAddition,customers_individual.postalcode,customers_individual.phoneNumber,customers_individual.email,
       customers_individual.customer_of
 FROM customers_individual 
     LEFT JOIN organisation 
         ON customers_individual.customer_of = organisation.id 
 WHERE customers_individual.customer_of = ?";
    $stmt = $mysqli->prepare($DataCustomer_P);
    $stmt->bind_param("i", $_GET["custof"]);
    $stmt->execute();
    $resultCustomer = $stmt->get_result();

    while ($rowCustomerP = $resultCustomer->fetch_array()) {
        ?>
        <div class="modal fade text-left" id="info<?= $rowCustomerP["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="ft-bookmark mr-2"></i>Basic Modal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Klantgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-username">Voornaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Voornaam" readonly
                                                   aria-invalid="false"
                                                   name="voornaam_p"
                                                   value="<?= $rowCustomerP["first_name"] ?>">
                                            <input type="hidden" value="<?= $id ?>"
                                                   name="id_p">
                                        </div>
                                        <div class="controls">
                                            <label for="tussenvoegsel">Tussenvoegsel</label>
                                            <input type="text"
                                                   id="tussenvoegsel"
                                                   class="form-control-plaintext text-light round"
                                                   readonly
                                                   placeholder="Tussenvoegsel"
                                                   aria-invalid="false"
                                                   name="tussenvoegsel_p"
                                                   value="<?= $rowCustomerP["last_name_prefix"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="achternaam">Achternaam</label>
                                            <input type="text" id="achternaam"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Achternaam" readonly
                                                   aria-invalid="false"
                                                   name="achternaam_p"
                                                   value="<?= $rowCustomerP["last_name"] ?>">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Adresgegevens</h4>
                                        <div class="controls ">
                                            <label for="users-edit-username">Straatnaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Straatnaam" readonly
                                                   aria-invalid="false"
                                                   name="straatnaam_p"
                                                   value="<?= $rowCustomerP["street"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="users-edit-username">Huisnummer</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Huisnummer" readonly
                                                   aria-invalid="false"
                                                   name="huisnummer_p"
                                                   value="<?= $rowCustomerP["housenumber"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Postcode</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Postcode" readonly
                                                   aria-invalid="false"
                                                   name="postcode_p"
                                                   value="<?= $rowCustomerP["postalcode"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Contactgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-email">E-mail</label>
                                            <input type="email"
                                                   id="users-edit-email"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Typeemail@hier.com"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="email_p"
                                                   value="<?= $rowCustomerP["email"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="telefoonnummer">Telefoonnummer</label>
                                            <input type="text" id="telefoonnummer"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Telefoonnummer"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="telefoonnummer_p"
                                                   value="<?= $rowCustomerP["phoneNumber"] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
function ViewUserZ()
{
    global $mysqli;
    $DataCustomer = "SELECT customers_business.id,customers_business.status,customers_business.first_name,customers_business.last_name_prefix,
        customers_business.last_name,customers_business.street,customers_business.housenumber,customers_business.housenumberAddition,
        customers_business.housenumberAddition,customers_business.postalcode,customers_business.email,customers_business.phoneNumber,customers_business.business,
       customers_business.customer_of
 FROM customers_business 
     LEFT JOIN organisation 
         ON customers_business.customer_of = organisation.id 
 WHERE customers_business.customer_of = ?";
    $stmt = $mysqli->prepare($DataCustomer);
    $stmt->bind_param("i", $_GET["custof"]);
    $stmt->execute();
    $resultCustomer = $stmt->get_result();

    while ($rowCustomer = $resultCustomer->fetch_array()) {
        ?>
        <div class="modal fade text-left" id="info<?= $rowCustomer["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="ft-bookmark mr-2"></i>Klantgegevens</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">

                                        <div class="controls">
                                            <label for="users-edit-username">Voornaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Voornaam" readonly
                                                   aria-invalid="false"
                                                   name="voornaam_p"
                                                   value="<?= $rowCustomer["first_name"] ?>">
                                            <input type="hidden" value="<?= $id ?>"
                                                   name="id">
                                        </div>
                                        <div class="controls">
                                            <label for="tussenvoegsel">Tussenvoegsel</label>
                                            <input type="text"
                                                   id="tussenvoegsel"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Tussenvoegsel"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="tussenvoegsel"
                                                   value="<?= $rowCustomer["last_name_prefix"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="achternaam">Achternaam</label>
                                            <input type="text" id="achternaam"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Achternaam"readonly
                                                   aria-invalid="false"
                                                   name="achternaam"
                                                   value="<?= $rowCustomer["last_name"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="business">Business</label>
                                            <input type="text"
                                                   id="business"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Bedrijf"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="business"
                                                   value="<?= $rowCustomer["business"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Adresgegevens</h4>
                                        <div class="controls ">
                                            <label for="users-edit-username">Straatnaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Straatnaam" readonly
                                                   aria-invalid="false"
                                                   name="straatnaam"
                                                   value="<?= $rowCustomer["street"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="users-edit-username">Huisnummer</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Huisnummer" readonly
                                                   aria-invalid="false"
                                                   name="huisnummer"
                                                   value="<?= $rowCustomer["housenumber"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Postcode</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Postcode" readonly
                                                   aria-invalid="false"
                                                   name="postcode"
                                                   value="<?= $rowCustomer["postalcode"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Contactgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-email">E-mail</label>
                                            <input type="email"
                                                   id="users-edit-email"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Typeemail@hier.com"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="email"
                                                   value="<?= $rowCustomer["email"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="telefoonnummer">Telefoonnummer</label>
                                            <input type="text" id="telefoonnummer"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Telefoonnummer"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="telefoonnummer"
                                                   value="<?= $rowCustomer["phoneNumber"] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
function ViewPersonnel()
{
    global $mysqli;
    $DataCustomer = "SELECT personnel.id,personnel.first_name,personnel.last_name_prefix,
        personnel.last_name,personnel.street,personnel.housenumber,personnel.email,
        personnel.housenumberAddition,personnel.postalcode,personnel.phoneNumber,personnel.authentication_level
 FROM personnel 
     LEFT JOIN organisation 
         ON personnel.member_of = organisation.id 
 WHERE personnel.member_of = ?";
    $stmt = $mysqli->prepare($DataCustomer);
    $stmt->bind_param("i", $_GET["membof"]);
    $stmt->execute();
    $resultPersonnel = $stmt->get_result();

    while ($rowPersonnel = $resultPersonnel->fetch_array()) {
        ?>
        <div class="modal fade text-left" id="info<?= $rowPersonnel["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="ft-bookmark mr-2"></i>Zakelijke klant</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Klantgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-username">Voornaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Voornaam" readonly
                                                   aria-invalid="false"
                                                   name="voornaam"
                                                   value="<?= $rowPersonnel["first_name"] ?>">
                                            <input type="hidden" value="<?= $rowPersonnel["id"]?>"
                                                   name="id">
                                        </div>
                                        <div class="controls">
                                            <label for="tussenvoegsel">Tussenvoegsel</label>
                                            <input type="text"
                                                   id="tussenvoegsel"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Tussenvoegsel"
                                                   aria-invalid="false"
                                                   readonly
                                                   name="tussenvoegsel"
                                                   value="<?= $rowPersonnel["last_name_prefix"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="achternaam">Achternaam</label>
                                            <input type="text" id="achternaam"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Achternaam" readonly
                                                   aria-invalid="false"
                                                   name="achternaam"
                                                   value="<?= $rowPersonnel["last_name"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Adresgegevens</h4>
                                        <div class="controls ">
                                            <label for="users-edit-username">Straatnaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Straatnaam" readonly
                                                   aria-invalid="false"
                                                   name="straatnaam_p"
                                                   value="<?= $rowPersonnel["street"] ?>">
                                        </div>
                                        <div class="controls">

                                            <label for="users-edit-username">Huisnummer</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Huisnummer" readonly
                                                   aria-invalid="false"
                                                   name="huisnummer_p"
                                                   value="<?= $rowPersonnel["housenumber"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Postcode</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Postcode" readonly
                                                   aria-invalid="false"
                                                   name="postcode"
                                                   value="<?= $rowPersonnel["postalcode"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Bedrijf</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Postcode"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="bedrijf"
                                                   value="<?php ViewCompanyPersonnel(); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Contactgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-email">E-mail</label>
                                            <input type="email"
                                                   id="users-edit-email"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Typeemail@hier.com"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="email"
                                                   value="<?= $rowPersonnel["email"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="telefoonnummer">Telefoonnummer</label>
                                            <input type="text" id="telefoonnummer"
                                                   class="form-control-plaintext text-light round"
                                                   placeholder="Telefoonnummer"
                                                   readonly
                                                   aria-invalid="false"
                                                   name="telefoonnummer_p"
                                                   value="<?= $rowPersonnel["phoneNumber"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Bedrijf</label>
                                            <br>
                                            <?php ViewCompanyPersonnel(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
function ViewCompanyPersonnel(){
    global $mysqli;
    $sql = "SELECT organisation.name,organisation.id
 FROM `organisation` 
     LEFT JOIN personnel 
         ON organisation.id = personnel.member_of 
 WHERE organisation.id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_GET["membof"]);
    $stmt->execute();
    $resultCompanyPersonnel = $stmt->get_result();
    if ($row = $resultCompanyPersonnel->fetch_array()){
        echo $row["name"];
    }
}


function InsertUser(){
    if (isset($_POST['gebruiker'])) {
        global $mysqli;
        $token = bin2hex(random_bytes(50));
        $rowPersonnel1 = Getpersonnel1();
        $email =  $rowPersonnel1['email'];
        $sql = "INSERT INTO `users`(`username`,`name`,`email`,`reset_token`,`member_of`)VALUES(?,?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $rowPersonnel1['first_name'],
            $rowPersonnel1['first_name'],
            $rowPersonnel1['email'],
            $token,
            $_GET['memb_of']
        );

        $stmt->execute();
        $stmt->close();
        $mysqli->close();
        if ($email > 0) {
            $to = $email;
            $subject = "Wachtwoord vergeten";
            $msg = "Your password reset link <br>https://relatiebeheer.qccstest.nl/wachtwoord_new.php?token=" . $token . " <br> Reset your password with this link .Click or open in new tab<br>";
            $msg = wordwrap($msg, 70);
            $headers = "From: Admin@qccs.nl";
            mail($to, $subject, $msg, $headers);
            header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"]);
        } else echo "'$email' komt niet voor in de database";
    }
}

function editPersonnel()
{

    global $mysqli;
    $DataCustomer = "SELECT personnel.id,personnel.first_name,personnel.last_name_prefix,
    personnel.last_name,personnel.street,personnel.housenumber,personnel.email,
    personnel.housenumberAddition,personnel.postalcode,personnel.phoneNumber,personnel.authentication_level
    FROM personnel
    LEFT JOIN organisation
    ON personnel.member_of = organisation.id
    WHERE personnel.member_of = ?";
    $stmt = $mysqli->prepare($DataCustomer);
    $stmt->bind_param("i", $_GET["membof"]);
    $stmt->execute();
    $resultPersonnel = $stmt->get_result();

    while ($rowPersonnel = $resultPersonnel->fetch_array()) {
        ?>

        <div class="modal fade text-left" id="editPersonnel<?= $rowPersonnel["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="ft-bookmark mr-2"></i>Personeel</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
                        </button>
                    </div>
                    <form method="post">
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Klantgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-username">Voornaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Voornaam"
                                                   pattern="[a-zA-Z]{1,10}"
                                                   required
                                                   aria-invalid="false"
                                                   name="voornaam_pe"
                                                   value="<?= $rowPersonnel["first_name"] ?>">
                                            <input type="hidden" value="<?= $rowPersonnel["id"]?>"
                                                   name="id">
                                            <input type="hidden" value="<?= $rowPersonnel["first_name"]?>"
                                                   name="username">
                                            <input type="hidden" value="<?= $rowPersonnel["email"]?>"
                                                   name="email">
                                        </div>
                                        <div class="controls">
                                            <label for="tussenvoegsel">Tussenvoegsel</label>
                                            <input type="text"
                                                   id="tussenvoegsel"
                                                   class="form-control text-light round"
                                                   placeholder="Tussenvoegsel"
                                                   pattern="[a-zA-Z]{1,10}"
                                                   aria-invalid="false"
                                                   name="tussenvoegsel_pe"
                                                   value="<?= $rowPersonnel["last_name_prefix"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="achternaam">Achternaam</label>
                                            <input type="text" id="achternaam"
                                                   class="form-control text-light round"
                                                   placeholder="Achternaam"
                                                   pattern="[a-zA-Z]{1,12}"
                                                   aria-invalid="false"
                                                   name="achternaam_pe"
                                                   required
                                                   value="<?= $rowPersonnel["last_name"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Adresgegevens</h4>
                                        <div class="controls ">
                                            <label for="users-edit-username">Straatnaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Straatnaam"
                                                   pattern="[a-zA-Z]{1,15}"
                                                   title="Alleen letters"
                                                   aria-invalid="false"
                                                   name="straatnaam_pe"
                                                   required
                                                   value="<?= $rowPersonnel["street"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="users-edit-username">Huisnummer</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Huisnummer"
                                                   pattern="[0-9]{1,4}"
                                                   title="Alleen cijfers"
                                                   aria-invalid="false"
                                                   name="huisnummer_pe"
                                                   required
                                                   value="<?= $rowPersonnel["housenumber"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Postcode</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Postcode"
                                                   pattern="[0-9]{4}[A-Za-z]{2}"
                                                   title="Bijvoorbeeld: '1234AB'"
                                                   aria-invalid="false"
                                                   name="postcode_pe"
                                                   required
                                                   value="<?= $rowPersonnel["postalcode"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Contactgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-email">E-mail</label>
                                            <input type="email"
                                                   id="users-edit-email"
                                                   class="form-control text-light round"
                                                   placeholder="Typeemail@hier.com"
                                                   aria-invalid="false"
                                                   name="email_pe"
                                                   required
                                                   value="<?= $rowPersonnel["email"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="telefoonnummer">Telefoonnummer</label>
                                            <input type="number" id="telefoonnummer"
                                                   class="form-control text-light round"
                                                   placeholder="Telefoonnummer"
                                                   pattern="[0-9]{1,15}"
                                                   title="Alleen cijfers"
                                                   aria-invalid="false"
                                                   name="telefoonnummer_pe"
                                                   required
                                                   value="<?= $rowPersonnel["phoneNumber"] ?>">
                                        </div>
                                    </div>
                                    <div class="controls ">
                                        <label for="users-edit-username">Bedrijf</label>
                                        <br>
                                        <?php ViewCompanyPersonnel(); ?>
                                    </div>
                                    <div class="form-group">
                                        <div class="controls">
                                            <label for="users-edit-role">Functie</label>
                                            <select id="users-edit-role" name="function" class="form-control" >
                                                <option value="<?=$rowPersonnel["authentication_level"] ?>" hidden selected><?=$rowPersonnel["authentication_level"] ?></option>
                                                <option value="Bedrijfsleider">Bedrijfsleider</option>
                                                <option value="Werknemer">Werknemer</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn bg-light-secondary" data-dismiss="modal">Sluiten</button>
                            <button name ="gebruiker" type="submit" class="btn btn-primary ">Gebruiker maken</button>
                            <button name ="submit" type="submit" class="btn btn-primary">Opslaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        if (isset($_POST['submit'])) {

            $voornaam = ucfirst($_POST['voornaam_pe']);
            $tussenvoegsel = strtolower($_POST['tussenvoegsel_pe']);
            $achternaam = ucfirst($_POST['achternaam_pe']);
            $straatnaam = ucfirst($_POST['straatnaam_pe']);

            $query = "UPDATE `personnel` SET `first_name`=?,`last_name_prefix`=?,`last_name`=?,
                                `street`= ?,`housenumber`=?, `postalcode`=?,`phoneNumber`=?,
                                `email`= ?, `authentication_level`=? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssssssssi', $voornaam, $tussenvoegsel, $achternaam
                , $straatnaam, $_POST["huisnummer_pe"], $_POST["postcode_pe"],
                $_POST["telefoonnummer_pe"], $_POST["email_pe"],$_POST["function"], $_POST["id"]);
            $stmt->execute();
        }
    }
    if (isset($_POST["gebruiker"])) {
        global $mysqli;
        $token = bin2hex(random_bytes(50));
        $sql = "INSERT INTO `users`(`username`,`name`,`email`,`reset_token`)VALUES(?,?,?,?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "ssss",
            $_POST["username"],
            $_POST["username"],
            $_POST["email"],
            $token
        );

        $stmt->execute();
        $stmt->close();
        $email = $_POST["email"];
        if ($email > 0) {
            $to = $email;
            $subject = "Wachtwoord vergeten";
            $msg = "Your password reset link <br>https://relatiebeheer.qccstest.nl/wachtwoord_new.php?token=" . $token . " <br> Reset your password with this link .Click or open in new tab<br>";
            $msg = wordwrap($msg, 70);
            $headers = "From: Admin@qccs.nl";
            mail($to, $subject, $msg, $headers);
           // header("Location:bedrijfs_klanten_overzicht.php?custof=" . $_GET["custof"] . "&membof=" . $_GET["membof"]);
        } else echo "'$email' komt niet voor in de database";
    }
}


function editUserP()
{
    global $mysqli;
    $DataCustomer_P = "SELECT customers_individual.id,customers_individual.status,customers_individual.first_name,customers_individual.last_name_prefix,
        customers_individual.last_name,customers_individual.street,customers_individual.housenumber,customers_individual.housenumberAddition,
        customers_individual.housenumberAddition,customers_individual.postalcode,customers_individual.phoneNumber,customers_individual.email,
       customers_individual.customer_of
 FROM customers_individual 
     LEFT JOIN organisation 
         ON customers_individual.customer_of = organisation.id 
 WHERE customers_individual.customer_of = ?";
    $stmt = $mysqli->prepare($DataCustomer_P);
    $stmt->bind_param("i", $_GET["custof"]);
    $stmt->execute();
    $resultCustomer = $stmt->get_result();

    while ($rowCustomerP = $resultCustomer->fetch_array()) {
        ?>
        <div class="modal fade text-left" id="editP<?= $rowCustomerP["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="ft-bookmark mr-2"></i>Particuliere klant</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Klantgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-username">Voornaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Voornaam"
                                                   pattern="[a-zA-Z]{1,10}"
                                                   title="Alleen letters"
                                                   aria-invalid="false"
                                                   name="voornaam_p"
                                                   required
                                                   value="<?= $rowCustomerP["first_name"] ?>">
                                            <input type="hidden" value="<?= $rowCustomerP['id'] ?>"
                                                   name="id_p">
                                        </div>
                                        <div class="controls">
                                            <label for="tussenvoegsel">Tussenvoegsel</label>
                                            <input type="text"
                                                   id="tussenvoegsel"
                                                   class="form-control text-light round"
                                                   placeholder="Tussenvoegsel"
                                                   pattern="[a-zA-Z]{1,10}"
                                                   title="Alleen letters"
                                                   aria-invalid="false"
                                                   name="tussenvoegsel_p"
                                                   value="<?= $rowCustomerP["last_name_prefix"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="achternaam">Achternaam</label>
                                            <input type="text" id="achternaam"
                                                   class="form-control text-light round"
                                                   placeholder="Achternaam"
                                                   pattern="[a-zA-Z]{1,10}"
                                                   title="Álleen letters"
                                                   aria-invalid="false"
                                                   name="achternaam_p"
                                                   required
                                                   value="<?= $rowCustomerP["last_name"] ?>">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Adresgegevens</h4>
                                        <div class="controls ">
                                            <label for="users-edit-username">Straatnaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Straatnaam"
                                                   pattern="[a-zA-Z]{1,15}"
                                                   title="Alleen letters"
                                                   aria-invalid="false"
                                                   name="straatnaam_p"
                                                   required
                                                   value="<?= $rowCustomerP["street"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="users-edit-username">Huisnummer</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   pattern="[0-9]{1,4}"
                                                   title="Aleen cijfers"
                                                   placeholder="Huisnummer"
                                                   aria-invalid="false"
                                                   name="huisnummer_p"
                                                   required
                                                   value="<?= $rowCustomerP["housenumber"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Postcode</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Postcode"
                                                   pattern="[0-9]{4}[A-Za-z]{2}"
                                                   title="Bijvoorbeeld: '1234AB'"
                                                   aria-invalid="false"
                                                   name="postcode_p"
                                                   required
                                                   value="<?= $rowCustomerP["postalcode"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Contactgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-email">E-mail</label>
                                            <input type="email"
                                                   id="users-edit-email"
                                                   class="form-control text-light round"
                                                   placeholder="Typeemail@hier.com"
                                                   aria-invalid="false"
                                                   name="email_p"
                                                   required
                                                   value="<?= $rowCustomerP["email"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="telefoonnummer">Telefoonnummer</label>
                                            <input type="text" id="telefoonnummer"
                                                   class="form-control text-light round"
                                                   placeholder="Telefoonnummer"
                                                   pattern="[0-9]{1,15}"
                                                   title="Alleen cijfers"
                                                   aria-invalid="false"
                                                   name="telefoonnummer_p"
                                                   required
                                                   value="<?= $rowCustomerP["phoneNumber"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="users-edit-status">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="<?=$rowCustomerP['status']?>" hidden selected><?=$rowCustomerP['status']?></option>
                                                <option value="Actief">Actief</option>
                                                <option value="Inactief">Inactief</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-dismiss="modal">Sluiten</button>
                        <button name="saveIndividual" type="submit" class="btn btn-primary">Opslaan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        if (isset($_POST['saveIndividual'])) {
            $voornaam = ucfirst($_POST['voornaam_p']);
            $tussenvoegsel = strtolower($_POST['tussenvoegsel_p']);
            $achternaam = ucfirst($_POST['achternaam_p']);
            $straatnaam = ucfirst($_POST['straatnaam_p']);

            $query = "UPDATE `customers_individual` SET `first_name`=?,`last_name_prefix`=?,`last_name`=?,
                                `street`= ?,`housenumber`=?,`housenumberAddition`=?,`postalcode`=?,`phoneNumber`=?,
                                `email`= ?, `status`= ? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
//        $options = ['cost' => 12,];
//        $wachtwoord = password_hash($_POST['Wachtwoord'], PASSWORD_BCRYPT, $options);
            $stmt->bind_param('ssssssssssi', $voornaam, $tussenvoegsel, $achternaam
                , $straatnaam, $_POST["huisnummer_p"], $_POST["huisnummertoevoeging_p"], $_POST["postcode_p"],
                $_POST["telefoonnummer_p"], $_POST["email_p"], $_POST["status"], $_POST["id_p"]);
            $stmt->execute();
        }
    }
}
function editUserZ()
{
    global $mysqli;
    $DataCustomer = "SELECT customers_business.id,customers_business.status,customers_business.first_name,customers_business.last_name_prefix,
        customers_business.last_name,customers_business.street,customers_business.housenumber,customers_business.housenumberAddition,
        customers_business.housenumberAddition,customers_business.postalcode,customers_business.email,customers_business.phoneNumber,customers_business.business,
       customers_business.customer_of
 FROM customers_business 
     LEFT JOIN organisation 
         ON customers_business.customer_of = organisation.id 
 WHERE customers_business.customer_of = ?";
    $stmt = $mysqli->prepare($DataCustomer);
    $stmt->bind_param("i", $_GET["custof"]);
    $stmt->execute();
    $resultCustomer = $stmt->get_result();

    while ($rowCustomer = $resultCustomer->fetch_array()) {
        ?>
        <div class="modal fade text-left" id="editZ<?= $rowCustomer["id"]?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel2"><i class="ft-bookmark mr-2"></i>Zakelijke klant</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Klantgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-username">Voornaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Voornaam"
                                                   aria-invalid="false"
                                                   name="voornaam_z"
                                                   required
                                                   pattern="[a-zA-Z]{1,10}"
                                                   title="Alleen letters"
                                                   value="<?= $rowCustomer["first_name"] ?>">
                                            <input type="hidden" value="<?= $rowCustomer['id']?>"
                                                   name="id_z">
                                        </div>
                                        <div class="controls">
                                            <label for="tussenvoegsel">Tussenvoegsel</label>
                                            <input type="text"
                                                   id="tussenvoegsel"
                                                   class="form-control text-light round"
                                                   placeholder="Tussenvoegsel"
                                                   aria-invalid="false"
                                                   pattern="[a-zA-Z]{1,10}"
                                                   title="Alleen letters"
                                                   name="tussenvoegsel_z"
                                                   value="<?= $rowCustomer["last_name_prefix"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="achternaam">Achternaam</label>
                                            <input type="text" id="achternaam"
                                                   class="form-control text-light round"
                                                   placeholder="Achternaam"
                                                   pattern="[a-zA-Z]{1,10}"
                                                   title="Alleen letters"
                                                   aria-invalid="false"
                                                   name="achternaam_z"
                                                   required
                                                   value="<?= $rowCustomer["last_name"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="business">Bedrijf</label>
                                            <input type="text"
                                                   id="business"
                                                   class="form-control text-light round"
                                                   placeholder="Bedrijf"
                                                   pattern="[a-zA-Z\s\.0-9]{1,15}"
                                                   aria-invalid="false"
                                                   name="business_z"
                                                   value="<?= $rowCustomer["business"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Adresgegevens</h4>
                                        <div class="controls ">
                                            <label for="users-edit-username">Straatnaam</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Straatnaam"
                                                   pattern="[a-zA-Z]{1,15}"
                                                   title="Alleen letters"
                                                   aria-invalid="false"
                                                   name="straatnaam_z"
                                                   required
                                                   value="<?= $rowCustomer["street"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="users-edit-username">Huisnummer</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Huisnummer"
                                                   pattern="[0-9]{1,4}"
                                                   title = "Alleen cijfers"
                                                   aria-invalid="false"
                                                   name="huisnummer_z"
                                                   required
                                                   value="<?= $rowCustomer["housenumber"] ?>">
                                        </div>
                                        <div class="controls ">
                                            <label for="users-edit-username">Postcode</label>
                                            <input type="text"
                                                   id="users-edit-username"
                                                   class="form-control text-light round"
                                                   placeholder="Postcode"
                                                   pattern="[0-9]{4}[A-Za-z]{2}"
                                                   title="Bijvoorbeeld: '1234AB'"
                                                   aria-invalid="false"
                                                   name="postcode_z"
                                                   required
                                                   value="<?= $rowCustomer["postalcode"] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <h4>Contactgegevens</h4>
                                        <div class="controls">
                                            <label for="users-edit-email">E-mail</label>
                                            <input type="email"
                                                   id="users-edit-email"
                                                   class="form-control text-light round"
                                                   placeholder="Typeemail@hier.com"
                                                   aria-invalid="false"
                                                   name="email_z"
                                                   required
                                                   value="<?= $rowCustomer["email"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="telefoonnummer">Telefoonnummer</label>
                                            <input type="text" id="telefoonnummer"
                                                   class="form-control text-light round"
                                                   placeholder="Telefoonnummer"
                                                   pattern="[0-9]{1,15}"
                                                   title="Alleen cijfers"
                                                   aria-invalid="false"
                                                   name="telefoonnummer_z"
                                                   required
                                                   value="<?= $rowCustomer["phoneNumber"] ?>">
                                        </div>
                                        <div class="controls">
                                            <label for="users-edit-status">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="<?=$rowCustomer["status"]?>" hidden selected><?=$rowCustomer['status']?></option>
                                                <option value="Actief">Actief</option>
                                                <option value="Inactief">Inactief</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-light-secondary" data-dismiss="modal">Sluiten</button>
                        <button name="saveBusiness" type="submit" class="btn btn-primary">Opslaan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        if (isset($_POST['saveBusiness'])) {
            $voornaam = ucfirst($_POST['voornaam_z']);
            $tussenvoegsel = strtolower($_POST['tussenvoegsel_z']);
            $achternaam = ucfirst($_POST['achternaam_z']);
            $straatnaam = ucfirst($_POST['straatnaam_z']);
            $bedrijf = ucfirst($_POST['business_z']);

            $query = "UPDATE `customers_business` SET `first_name`=?,`last_name_prefix`=?,`last_name`=?,
                                `street`= ?,`housenumber`=?,`housenumberAddition`=?,`postalcode`=?,`phoneNumber`=?,
                                `email`= ?,`status`= ?,`business`=? WHERE id = ?";
            $stmt = $mysqli->prepare($query);
//        $options = ['cost' => 12,];
//        $wachtwoord = password_hash($_POST['Wachtwoord'], PASSWORD_BCRYPT, $options);
            $stmt->bind_param('sssssssssssi', $voornaam, $tussenvoegsel, $achternaam
                , $straatnaam, $_POST["huisnummer_z"], $_POST["huisnummertoevoeging_z"], $_POST["postcode_z"],
                $_POST["telefoonnummer_z"], $_POST["email_z"], $_POST["status"], $bedrijf,$_POST["id_z"]);
            $stmt->execute();
        }
    }
}