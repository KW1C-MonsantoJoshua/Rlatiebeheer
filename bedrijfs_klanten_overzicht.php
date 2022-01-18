<!--Op deze pagina komt een overzicht met alle bedrijven die de applicatie gebruiken-->
<?php
include "backend/functions.php";
UpdateCompanyInfo();
$rowC = GetCompanyInfo();
ViewUserP();
ViewUserZ();
ViewPersonnel();
editPersonnel();
editUserZ();
editUserP();

// Controleer of iemand ingelogd is
if (!isset($_SESSION["loggedin"])) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html class="loading" lang="en">
<!-- BEGIN : Head-->

<?php
include "partials/header.php";
?>
<!-- END : Head-->
<!-- BEGIN : Body-->
<body class="vertical-layout vertical-menu 2-columns  navbar-sticky layout-dark layout-transparent bg-glass-1"
      data-bg-img="bg-glass-1" data-menu="vertical-menu" data-col="2-columns">

<!-- Navbar (Header) Starts-->
<?php
include "partials/navbar.php";
?>
<!-- ////////////////////////////////////////////////////////////////////////////-->
<!-- / main menu-->

<div class="main-panel">
    <!-- BEGIN : Main Content-->
    <div class="main-content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><?php GetCompanyName();?></h4>

                            <ul class="breadcrumb bg-transparent">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="bedrijfs_overzicht.php">CRM Relaties</a></li>
                                <li class="breadcrumb-item"><?php
                                    $crumbs = explode("/",$_SERVER["REQUEST_URI"]);
                                    foreach($crumbs as $crumb){
                                        echo ucfirst(str_replace(array(".php","_"),array(""," "),$crumb) . ' ');
                                    }
                                    ?></li>
                            </ul>
                        </div>

                        <div class="card-content">
                            <div class="card-body">
                                <section id="file-export">
                                    <ul class="nav nav-tabs" role="tablist" id="tabs">
                                        <li class="nav-item active">
                                            <a href="#Particulier" role="tab" id="account-tab"
                                               class="nav-link d-flex align-items-center active" data-toggle="tab"
                                               aria-controls="account" aria-selected="true">
                                                <i class="ft-user mr-1"></i>
                                                <span class="d-none d-sm-block">Particulier</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#Zakelijk" role="tab" id="information-tab"
                                               class="nav-link d-flex align-items-center " data-toggle="tab"
                                               aria-controls="information" aria-selected="false">
                                                <i class="ft-info mr-1"></i>
                                                <span class="d-none d-sm-block">Zakelijk</span>
                                            </a>
                                        </li>
                                        <li class="nav-item active">
                                            <a href="#Werknemers" role="tab" id="account-tab"
                                               class="nav-link d-flex align-items-center " data-toggle="tab"
                                               aria-controls="account" aria-selected="true">
                                                <i class="ft-user mr-1"></i>
                                                <span class="d-none d-sm-block">Werknemers</span>
                                            </a>
                                        </li>
                                        <li class="nav-tabs">
                                            <a type="button"
                                               class="nav-link d-flex align-items-end"
                                               data-toggle="modal" data-target="#large">
                                                <i class="ft-plus mr-1"></i>
                                                <span class="d-none d-sm-block">Toevoegen</span>
                                            </a>
                                        </li>
                                        <li class="nav-item active">
                                            <a href="#Settings" role="tab" id="account-tab"
                                               class="nav-link d-flex align-items-center " data-toggle="tab"
                                               aria-controls="account" aria-selected="true">
                                                <i class="ft-settings mr-1"></i>
                                                <span class="d-none d-sm-block">Instellingen</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade mt-2 show active" id="Particulier" role="tabpanel"
                                             aria-labelledby="account-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <div>
                                                            <?php
                                                            if (isset($_GET["toevoegenPart"])) {
                                                                if ($_GET["toevoegenPart"] == "empty") {
                                                                    echo "<p class='text-danger'>Vul alle velden in aub</p>";
                                                                } elseif ($_GET["toevoegenPart"] == "namefout") {
                                                                    echo "<p class='text-danger'>Voornaam heeft foute tekens</p>";
                                                                } elseif ($_GET["toevoegenPart"] == "telfout") {
                                                                    echo "<p class='text-danger'>Telefoonnummer klopt niet</p>";
                                                                } elseif ($_GET["toevoegenPart"] == "mailfout") {
                                                                    echo "<p class='text-danger'>Email klopt niet</p>";
                                                                } elseif ($_GET["toevoegenPart"] == "emaildupli") {
                                                                    echo "<p class='text-danger'>Email bestaat al</p>";
                                                                }elseif ($_GET["toevoegenPart"] == "straatfout") {
                                                                    echo "<p class='text-danger'>Straatnaam mag geen nummers bevatten!</p>";
                                                                }elseif ($_GET["toevoegenPart"] == "postcodefout") {
                                                                    echo "<p class='text-danger'>Ongeldige postcode ! </p>";
                                                                }
                                                                if ($_GET["toevoegenPart"] == "succes") {
                                                                    echo "<p class='text-success'>Relatie succesvol toegevoegd !</p>";
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <table class="table table-striped table-bordered file-export">
                                                            <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Volledige Naam</th>
                                                                <th>Telefoonnummer</th>
                                                                <th>Email</th>
                                                                <th>Status</th>
                                                                <th>Handelingen</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            GetCustomerP();
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade mt-2 show " id="Zakelijk" role="tabpanel"
                                             aria-labelledby="account-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <div>
                                                            <?php
                                                            if (isset($_GET["toevoegenZak"])) {
                                                                if ($_GET["toevoegenZak"] == "empty") {
                                                                    echo "<p class='text-danger'>Vul alle velden in aub</p>";
                                                                } elseif ($_GET["toevoegenZak"] == "namefout") {
                                                                    echo "<p class='text-danger'>Voornaam heeft foute tekens</p>";
                                                                } elseif ($_GET["toevoegenZak"] == "telfout") {
                                                                    echo "<p class='text-danger'>Telefoonnummer klopt niet</p>";
                                                                } elseif ($_GET["toevoegenZak"] == "mailfout") {
                                                                    echo "<p class='text-danger'>Email klopt niet</p>";
                                                                } elseif ($_GET["toevoegenZak"] == "emaildupli") {
                                                                    echo "<p class='text-danger'>Email bestaat al</p>";
                                                                }elseif ($_GET["toevoegenZak"] == "straatfout") {
                                                                    echo "<p class='text-danger'>Straatnaam mag geen nummers bevatten!</p>";
                                                                }elseif ($_GET["toevoegenZak"] == "postcodefout") {
                                                                    echo "<p class='text-danger'>Ongeldige postcode !</p>";
                                                                }
                                                                if ($_GET["toevoegenZak"] == "succes") {
                                                                    echo "<p class='text-success'>Relatie succesvol toegevoegd !</p>";
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <table class="table table-striped table-bordered file-export">
                                                            <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>VolledigeNaam</th>
                                                                <th>Telefoonnummer</th>
                                                                <th>Bedrijfsnaam</th>
                                                                <th>Status</th>
                                                                <th>Handelingen</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            GetCustomerZ();
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade mt-2 show " id="Werknemers" role="tabpanel"
                                             aria-labelledby="account-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive">
                                                        <div>
                                                            <?php
                                                            if (isset($_GET["toevoegenMemb"])) {
                                                                if ($_GET["toevoegenMemb"] == "empty") {
                                                                    echo "<p class='text-danger'>Vul alle velden in aub</p>";
                                                                } elseif ($_GET["toevoegenMemb"] == "namefout") {
                                                                    echo "<p class='text-danger'>Voornaam heeft foute tekens</p>";
                                                                } elseif ($_GET["toevoegenMemb"] == "telfout") {
                                                                    echo "<p class='text-danger'>Telefoonnummer klopt niet</p>";
                                                                } elseif ($_GET["toevoegenMemb"] == "mailfout") {
                                                                    echo "<p class='text-danger'>Email klopt niet</p>";
                                                                } elseif ($_GET["toevoegenMemb"] == "emaildupli") {
                                                                    echo "<p class='text-danger'>Email bestaat al</p>";
                                                                }elseif ($_GET["toevoegenMemb"] == "straatfout") {
                                                                    echo "<p class='text-danger'>Straatnaam mag geen nummers bevatten!</p>";
                                                                }elseif ($_GET["toevoegenMemb"] == "postcodefout") {
                                                                    echo "<p class='text-danger'>Ongeldige postcode !</p>";
                                                                }
                                                                if ($_GET["toevoegenMemb"] == "succes") {
                                                                    echo "<p class='text-success'>Relatie succesvol toegevoegd !</p>";
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <table class="table table-striped table-bordered file-export">
                                                            <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Voornaam</th>
                                                                <th>Email</th>
                                                                <th>Telefoonnummer</th>
                                                                <th>Functie</th>
                                                                <th>Handelingen</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            GetPersonnel();
                                                            //GetCompanyPersonneel();

                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade mt-2 show " id="Settings" role="tabpanel"
                                             aria-labelledby="account-tab">
                                            <div class="row justify-content-center">
                                                <div class="col-lg-8 col-md-12">
                                                    <div class="card text-center">
                                                        <div class="card-header">
                                                            <h4 class="card-title success">
                                                                Bedrijfsgegevens <?= $rowC["name"]; ?></h4>
															<?php 
															if(isset($_POST['bijwerken'])){
																echo "<p class='text-succes'>Update Succesvol!</p>";
															}
															?>
                                                        </div>
                                                        <div class="card-content">
                                                            <div class="card-body">
                                                                <form class=" form " id="formSettings" method="post">
                                                                    <div class="form-body"><h6 class="mt-3"><i
                                                                                    class="ft-eye mr-2"></i>Over <?= $rowC["name"]; ?>
                                                                        </h6>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="bedrijfsnaam"
                                                                                           class="sr-only">Bedrijfsnaam</label>
                                                                                    <input type="text" id="bedrijfsnaam"
                                                                                           class="form-control"
                                                                                           placeholder="Bedrijfsnaam"
                                                                                           name="name"
                                                                                           value="<?= $rowC["name"]; ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="huisnummer"
                                                                                           class="sr-only">Huisnummer</label>
                                                                                    <input type="text" id="huisnummer"
                                                                                           class="form-control"
                                                                                           placeholder="Huisnummer"
                                                                                           name="huisnummer"
                                                                                           value="<?=$rowC['housenumber']?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="toevoeging"
                                                                                           class="sr-only">Toevoeging</label>
                                                                                    <input type="text" id="toevoeging"
                                                                                           class="form-control"
                                                                                           placeholder="Toevoeging"
                                                                                           name="toevoeging"
                                                                                           value="<?=$rowC['housenumberAddition']?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="adres"
                                                                                           class="sr-only">Adres</label>
                                                                                    <input type="text" id="adres"
                                                                                           class="form-control"
                                                                                           placeholder="Adres"
                                                                                           name="street"
                                                                                           value="<?=$rowC['street']?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="postcode"
                                                                                           class="sr-only">Postcode</label>
                                                                                    <input type="text" id="postcode"
                                                                                           class="form-control"
                                                                                           placeholder="Postcode"
                                                                                           name="postcode"
                                                                                           value="<?=$rowC['postalcode']?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="plaats" class="sr-only">Plaats</label>
                                                                                    <input class="form-control"
                                                                                           type="text"
                                                                                           placeholder="Plaats"
                                                                                           id="plaats" name="plaats">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="mobiel"
                                                                                           class="sr-only">Mobiel</label>
                                                                                    <input type="text" id="mobiel"
                                                                                           class="form-control"
                                                                                           placeholder="Mobiel"
                                                                                           name="mobiel"
                                                                                           value="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="telefoon"
                                                                                           class="sr-only">Telefoon</label>
                                                                                    <input type="text" id="telefoon"
                                                                                           class="form-control"
                                                                                           placeholder="Telefoon"
                                                                                           name="telefoon"
                                                                                           value="<?=$rowC['phoneNumber']?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="website"
                                                                                           class="sr-only">Website</label>
                                                                                    <input type="text" id="website"
                                                                                           class="form-control"
                                                                                           placeholder="Website"
                                                                                           name="website"
                                                                                           value="<?=$rowC['website']?>">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="email"
                                                                                           class="sr-only">Email</label>
                                                                                    <input type="text" id="email"
                                                                                           class="form-control"
                                                                                           placeholder="Email"
                                                                                           name="email"
                                                                                           value="<?=$rowC['email']?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="btw"
                                                                                           class="sr-only">BTW
                                                                                        Nummer</label>
                                                                                    <input type="text" id="btw"
                                                                                           class="form-control"
                                                                                           placeholder="BTW Nummer"
                                                                                           name="btw">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="kvk" class="sr-only">KVK
                                                                                        Nummer</label>
                                                                                    <input class="form-control"
                                                                                           type="text"
                                                                                           placeholder="KVK Nummer"
                                                                                           id="kvk">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="bic"
                                                                                           class="sr-only">BIC</label>
                                                                                    <input class="form-control"
                                                                                           type="text"
                                                                                           placeholder="BIC"
                                                                                           id="bic">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="iban"
                                                                                           class="sr-only">IBAN
                                                                                        Nummer</label>
                                                                                    <input class="form-control"
                                                                                           type="text"
                                                                                           placeholder="IBAN Nummer"
                                                                                           id="iban">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="float-right">
                                                                            <button type="button" name="bijwerken"
                                                                                    class="btn btn-primary confirm-text ">Opslaan <i
                                                                                        class="ft-check"></i>
                                                                            </button>
                                                                        </div>
																	</div>
                                                                </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Card</h4>
                                                    <p>Gedeelte op de instellingen pagina voor het toevoegen van briefpapier en factuur layout</p>
                                                </div>
                                                <div class="card-content">
                                                    <div id="carousel-example-generic2" class="carousel slide" data-ride="carousel">
                                                        <ol class="carousel-indicators">
                                                            <li data-target="#carousel-example-generic2" data-slide-to="0" class="active"></li>
                                                            <li data-target="#carousel-example-generic2" data-slide-to="1"></li>
                                                            <li data-target="#carousel-example-generic2" data-slide-to="2"></li>
                                                        </ol>
                                                        <div class="carousel-inner" role="listbox">
                                                            <div class="carousel-item active">
                                                                <img src="assets/img/photos/01.jpg" class="d-block w-100" alt="First slide">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img src="assets/img/photos/05.jpg" class="d-block w-100" alt="Second slide">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img src="assets/img/photos/10.jpg" class="d-block w-100" alt="Third slide">
                                                            </div>
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carousel-example-generic2" role="button" data-slide="prev">
                                                            <span class="fa fa-angle-left icon-prev" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel-example-generic2" role="button" data-slide="next">
                                                            <span class="fa fa-angle-right icon-next" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                                                            content.</p>
                                                        <a href="javascript:void(0);" class="card-link">Card link</a>
                                                        <a href="javascript:void(0);" class="card-link">Another link</a>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-muted pt-0">
                                                    <span class="float-left">2 days ago</span>
                                                    <span class="tags float-right">
                                            <span class="badge bg-primary">Branding</span>
                                            <span class="badge bg-danger">Design</span>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Verbindingen</h4>
                                                    <p>Gedeelte op de instellingen pagina met verbindingen naar verschillende betaalmethodes</p>
                                                </div>
                                                <div class="card-content">
                                                    <div id="carousel-example-generic2" class="carousel slide" data-ride="carousel">
                                                        <ol class="carousel-indicators">
                                                            <li data-target="#carousel-example-generic2" data-slide-to="0" class="active"></li>
                                                            <li data-target="#carousel-example-generic2" data-slide-to="1"></li>
                                                            <li data-target="#carousel-example-generic2" data-slide-to="2"></li>
                                                        </ol>
                                                        <div class="carousel-inner" role="listbox">
                                                            <div class="carousel-item active">
                                                                <img src="assets/img/photos/01.jpg" class="d-block w-100" alt="First slide">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img src="assets/img/photos/05.jpg" class="d-block w-100" alt="Second slide">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img src="assets/img/photos/10.jpg" class="d-block w-100" alt="Third slide">
                                                            </div>
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carousel-example-generic2" role="button" data-slide="prev">
                                                            <span class="fa fa-angle-left icon-prev" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel-example-generic2" role="button" data-slide="next">
                                                            <span class="fa fa-angle-right icon-next" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                                                            content.</p>
                                                        <a href="javascript:void(0);" class="card-link">Card link</a>
                                                        <a href="javascript:void(0);" class="card-link">Another link</a>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-muted pt-0">
                                                    <span class="float-left">2 days ago</span>
                                                    <span class="tags float-right">
                                            <span class="badge bg-primary">Branding</span>
                                            <span class="badge bg-danger">Design</span>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Backups & Social Media</h4>
                                                    <p>Gedeelte op de instellingen pagina voor het maken van backups en social media</p>
                                                </div>
                                                <div class="card-content">
                                                    <div id="carousel-example-generic2" class="carousel slide" data-ride="carousel">
                                                        <ol class="carousel-indicators">
                                                            <li data-target="#carousel-example-generic2" data-slide-to="0" class="active"></li>
                                                            <li data-target="#carousel-example-generic2" data-slide-to="1"></li>
                                                            <li data-target="#carousel-example-generic2" data-slide-to="2"></li>
                                                        </ol>
                                                        <div class="carousel-inner" role="listbox">
                                                            <div class="carousel-item active">
                                                                <img src="assets/img/photos/01.jpg" class="d-block w-100" alt="First slide">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img src="assets/img/photos/05.jpg" class="d-block w-100" alt="Second slide">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img src="assets/img/photos/10.jpg" class="d-block w-100" alt="Third slide">
                                                            </div>
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carousel-example-generic2" role="button" data-slide="prev">
                                                            <span class="fa fa-angle-left icon-prev" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carousel-example-generic2" role="button" data-slide="next">
                                                            <span class="fa fa-angle-right icon-next" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
                                                    <div class="card-body">
                                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                                                            content.</p>
                                                        <a href="javascript:void(0);" class="card-link">Card link</a>
                                                        <a href="javascript:void(0);" class="card-link">Another link</a>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-muted pt-0">
                                                    <span class="float-left">2 days ago</span>
                                                    <span class="tags float-right">
                                            <span class="badge bg-primary">Branding</span>
                                            <span class="badge bg-danger">Design</span>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END : End Main Content-->

<!-- Scroll to top button -->
<button class="btn btn-primary scroll-top" type="button"><i class="ft-arrow-up"></i></button>

</div>
<div class="col-lg-3 col-md-6 col-12">
    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade text-left" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
         aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel17">Relatie toevoegen</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="ft-x font-medium-2 text-bold-700"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <section class="users-edit">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <!-- Nav-tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item">
                                                    <a href="#relatie_particulier" role="tab" id="account-tab"
                                                       class="nav-link d-flex align-items-center active"
                                                       data-toggle="tab"
                                                       aria-controls="account" aria-selected="true">
                                                        <i class="ft-user mr-1"></i>
                                                        <span class="d-none d-sm-block">Particulier</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#relatie_zakelijk" role="tab" id="information-tab"
                                                       class="nav-link d-flex align-items-center" data-toggle="tab"
                                                       aria-controls="information" aria-selected="false">
                                                        <i class="ft-info mr-1"></i>
                                                        <span class="d-none d-sm-block">Zakelijk</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#relatie_werknemers" role="tab" id="information-tab"
                                                       class="nav-link d-flex align-items-center" data-toggle="tab"
                                                       aria-controls="information" aria-selected="false">
                                                        <i class="ft-info mr-1"></i>
                                                        <span class="d-none d-sm-block">Werknemers</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <!-- Account content starts -->
                                                <div class="tab-pane fade mt-2 show active" id="relatie_particulier"
                                                     role="tabpanel"
                                                     aria-labelledby="account-tab">
                                                    <!-- Account form starts -->
                                                    <form method="post" class='needs-validation'>
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Klantgegevens</h4>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Voornaam</label>
                                                                        <input type="text" id="users-edit-username"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Alleen letters"
                                                                               placeholder="Voornaam" required
                                                                               aria-invalid="false" name="voornaam_p">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Tussenvoegsel</label>
                                                                        <input type="text" id="users-edit-username"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Alleen letters"
                                                                               placeholder="Tussenvoegsel"
                                                                               aria-invalid="false" name="tussenvoegsel_p">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="achternaam">Achternaam</label>
                                                                        <input type="text" id="achternaam"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Álleen letters"
                                                                               placeholder="Achternaam"
                                                                               aria-invalid="false" name="achternaam_p"
                                                                               required
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Adresgegevens</h4>
                                                                    <div class="controls ">
                                                                        <label for="users-edit-username">Straatnaam</label>
                                                                        <input type="text" id="straat_p"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,15}"
                                                                               title="Alleen letters"
                                                                               placeholder="Straatnaam" required
                                                                               aria-invalid="false" name="straatnaam_p">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Huisnummer</label>
                                                                        <input type="text" id="huisnr_p"
                                                                               class="form-control round"
                                                                               pattern="[0-9]{1,4}"
                                                                               title="Aleen cijfers"
                                                                               placeholder="Huisnummer" required
                                                                               aria-invalid="false" name="huisnummer_p"
                                                                               onkeyup="check_pc(&quot;huisnr&quot;,this.value)">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Huisnummertoevoeging</label>
                                                                        <input type="text" id="toevoeging_p"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,4}"
                                                                               title="Alleen letters"
                                                                               placeholder="Huisnummertoevoeging" 
                                                                               aria-invalid="false" name="huisnummertoevoeging_p"
                                                                               onkeyup="check_pc(&quot;toevoeging&quot;,this.value)">
                                                                    </div>
                                                                    <div class="controls ">
                                                                        <label for="users-edit-username">Postcode</label>
                                                                        <input type="text" id="postcode_p"
                                                                               class="form-control round"
                                                                               pattern="[0-9]{4}[A-Za-z]{2}"
                                                                               title="Bijvoorbeeld: '1234AB'"
                                                                               placeholder="Postcode" required
                                                                               aria-invalid="false" name="postcode_p"
                                                                               onkeyup="check_pc(&quot;postcode&quot;,this.value)" autofocus="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Contactgegevens</h4>
                                                                    <div class="controls">
                                                                        <label for="users-edit-email">E-mail</label>
                                                                        <input type="email" id="users-edit-email"
                                                                               class="form-control round"
                                                                               placeholder="Typeemail@hier.com" required
                                                                               aria-invalid="false" name="email_p">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="telefoonnummer">Telefoonnummer</label>
                                                                        <input type="text" id="telefoonnummer"
                                                                               class="form-control round"
                                                                               pattern="[0-9]{1,15}"
                                                                               title="Alleen cijfers"
                                                                               placeholder="Telefoonnummer" required
                                                                               aria-invalid="false"
                                                                               name="telefoonnummer_p">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-3 mt-sm-2">
                                                                <input type="submit"
                                                                       class="btn btn-primary mb-2 mb-sm-0 mr-sm-2"
                                                                       name="registreerParticulier"
                                                                       value="Relatie Toevoegen">

                                                                <button type="reset"
                                                                        data-dismiss="modal"
                                                                        class="btn btn-secondary">Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- Account form ends -->
                                                </div>
                                                <!-- Account content ends -->

                                                <!-- Information content starts -->
                                                <div class="tab-pane fade mt-2 show" id="relatie_zakelijk"
                                                     role="tabpanel"
                                                     aria-labelledby="Zakelijk-tab">
                                                    <!-- Media object starts -->
                                                    <!-- Media object ends -->
                                                    <form method="post">
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Klantgegevens</h4>
                                                                    <div class="controls">
                                                                        <div class="controls ">
                                                                            <label for="bedrijfsnaam">Bedrijfsnaam</label>
                                                                            <input type="text" id="bedrijfsnaam"
                                                                                   class="form-control round"
                                                                                   pattern="[a-zA-Z\s\.0-9]{1,15}"
                                                                                   placeholder="Bedrijfsnaam" required
                                                                                   aria-invalid="false" name="bedrijfsnaam">
                                                                            <input type="hidden" name="custof" value="<?= $_GET["custof"] ?>">
                                                                        </div>
                                                                        <label for="users-edit-username">Voornaam</label>
                                                                        <input type="text" id="users-edit-username"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Alleen letters"
                                                                               placeholder="Voornaam" required
                                                                               aria-invalid="false" name="voornaam_z">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Tussenvoegsel</label>
                                                                        <input type="text" id="users-edit-username"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Alleen letters"
                                                                               placeholder="Tussenvoegsel"
                                                                               aria-invalid="false" name="tussenvoegsel_z">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="achternaam">Achternaam</label>
                                                                        <input type="text" id="achternaam"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Alleen letters"
                                                                               placeholder="Achternaam" required
                                                                               aria-invalid="false" name="achternaam_z">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Adresgegevens</h4>
                                                                    <div class="controls ">
                                                                        <label for="straatnaam">Straatnaam</label>
                                                                        <input type="text" id="straat_z"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,15}"
                                                                               title="Alleen letters"
                                                                               placeholder="Straatnaam" required
                                                                               aria-invalid="false" name="straatnaam_z">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="huisnummer">Huisnummer</label>
                                                                        <input type="text" id="huisnr_z"
                                                                               class="form-control round"
                                                                               pattern="[0-9]{1,4}"
                                                                               title = "Alleen cijfers"
                                                                               placeholder="Huisnummer" required
                                                                               aria-invalid="false" name="huisnummer_z"
                                                                               onkeyup="check_pc(&quot;huisnr_z&quot;,this.value)">

                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Huisnummertoevoeging</label>
                                                                        <input type="text" id="toevoeging_z"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,4}"
                                                                               placeholder="Huisnummertoevoeging"
                                                                               aria-invalid="false" name="huisnummertoevoeging_z"
                                                                               onkeyup="check_pc(&quot;toevoeging_z&quot;,this.value)">

                                                                    </div>
                                                                    <div class="controls ">
                                                                        <label for="postcode">Postcode</label>
                                                                        <input type="text" id="postcode_z"
                                                                               class="form-control round"
                                                                               pattern="[0-9]{4}[A-Za-z]{2}"
                                                                               title="Bijvoorbeeld: '1234AB'"
                                                                               placeholder="Postcode" required
                                                                               aria-invalid="false" name="postcode_z"
                                                                               onkeyup="check_pc(&quot;postcode_z&quot;,this.value)" autofocus="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Contactgegevens</h4>
                                                                </div>
                                                                <div class="controls">
                                                                    <label for="email">E-mail</label>
                                                                    <input type="email" id="email"
                                                                           class="form-control round"
                                                                           placeholder="Typeemail@hier.com" required
                                                                           aria-invalid="false" name="email_z">
                                                                </div>
                                                                <div class="controls">
                                                                    <label for="telefoonnummer">Telefoonnummer</label>
                                                                    <input type="text" id="telefoonnummer"
                                                                           class="form-control round"
                                                                           pattern="[0-9]{1,15}"
                                                                           title="Alleen cijfers"
                                                                           placeholder="Telefoonnummer" required
                                                                           aria-invalid="false"
                                                                           name="telefoonnummer_z">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-3 mt-sm-2">
                                                                <input type="submit"
                                                                       class="btn btn-primary mb-2 mb-sm-0 mr-sm-2"
                                                                       name="registreerZakelijk"
                                                                       value="Relatie Toevoegen">

                                                                <button type="reset"
                                                                        data-dismiss="modal"
                                                                        class="btn btn-secondary">Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Account form starts -->
                                                    <!-- Account form ends -->
                                                </div>
                                                <div class="tab-pane fade mt-2 show" id="relatie_werknemers"
                                                     role="tabpanel"
                                                     aria-labelledby="Zakelijk-tab">
                                                    <!-- Media object starts -->
                                                    <!-- Media object ends -->
                                                    <form method="post">
                                                        <div class="row">
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Klantgegevens</h4>
                                                                    <div class="controls">
                                                                        <div class="controls ">
                                                                            <label for="bedrijfsnaam">Bedrijfsnaam</label>
                                                                            <input type="text" id="bedrijfsnaam"
                                                                                   class="form-control round"
                                                                                   pattern="[a-zA-Z\s\.0-9]{1,15}"
                                                                                   readonly
                                                                                   aria-invalid="false" name="bedrijfsnaam" value="<?php GetCompanyNamePersonnel() ?>">
                                                                            <input type="hidden" name="membof" value="<?= $_GET["membof"] ?>">
                                                                        </div>
                                                                        <label for="users-edit-username">Voornaam</label>
                                                                        <input type="text" id="users-edit-username"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               placeholder="Voornaam" required
                                                                               aria-invalid="false" name="voornaam">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Tussenvoegsel</label>
                                                                        <input type="text" id="users-edit-username"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Alleen letters"
                                                                               placeholder="Tussenvoegsel"
                                                                               aria-invalid="false" name="tussenvoegsel">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="achternaam">Achternaam</label>
                                                                        <input type="text" id="achternaam"
                                                                               pattern="[a-zA-Z]{1,10}"
                                                                               title="Alleen letters"
                                                                               class="form-control round"
                                                                               placeholder="Achternaam" required
                                                                               aria-invalid="false" name="achternaam">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Adresgegevens</h4>
                                                                    <div class="controls ">
                                                                        <label for="straatnaam">Straatnaam</label>
                                                                        <input type="text" id="straat"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,15}"
                                                                               title="Alleen letters"
                                                                               placeholder="Straatnaam" required
                                                                               aria-invalid="false" name="straat">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="huisnummer">Huisnummer</label>
                                                                        <input type="text" id="huisnr"
                                                                               class="form-control round"
                                                                               pattern="[0-9]{1,4}"
                                                                               title = "Alleen cijfers"
                                                                               placeholder="Huisnummer" required
                                                                               aria-invalid="false" name="huisnummer"
                                                                               onkeyup="check_pc(&quot;huisnr&quot;,this.value)">

                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="users-edit-username">Huisnummertoevoeging</label>
                                                                        <input type="text" id="toevoeging"
                                                                               class="form-control round"
                                                                               pattern="[a-zA-Z]{1,4}"
                                                                               title="Alleen letters"
                                                                               placeholder="Huisnummertoevoeging"
                                                                               aria-invalid="false" name="huisnummertoevoeging"
                                                                               onkeyup="check_pc(&quot;toevoeging&quot;,this.value)">

                                                                    </div>
                                                                    <div class="controls ">
                                                                        <label for="postcode">Postcode</label>
                                                                        <input type="text" id="postcode"
                                                                               class="form-control round"
                                                                               pattern="[0-9]{4}[A-Za-z]{2}"
                                                                               title="Bijvoorbeeld: '1234AB'"
                                                                               placeholder="Postcode" required
                                                                               aria-invalid="false" name="postcode"
                                                                               onkeyup="check_pc(&quot;postcode&quot;,this.value)" autofocus="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-4">
                                                                <div class="form-group">
                                                                    <h4>Contactgegevens</h4>
                                                                    <div class="controls">
                                                                        <label for="email">E-mail</label>
                                                                        <input type="email" id="email"
                                                                               class="form-control round"
                                                                               placeholder="Typeemail@hier.com" required
                                                                               aria-invalid="false" name="email">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="telefoonnummer">Telefoonnummer</label>
                                                                        <input type="text" id="telefoonnummer"
                                                                               class="form-control round"
                                                                               placeholder="Telefoonnummer" required
                                                                               pattern="[0-9]{1,15}"
                                                                               title="Alleen cijfers"
                                                                               aria-invalid="false"
                                                                               name="telefoonnummer">
                                                                    </div>
                                                                    <div class="controls">
                                                                        <label for="auth_level">Functie</label>
                                                                        <select id="auth_level" name="function" class="form-control" >
                                                                            <option value="" hidden>Select Role</option>
                                                                            <option value="Bedrijfsleider">Bedrijfsleider</option>
                                                                            <option value="Werknemer">Werknemer</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-3 mt-sm-2">
                                                                <input type="submit"
                                                                       class="btn btn-primary mb-2 mb-sm-0 mr-sm-2"
                                                                       name="registreerWerknemer"
                                                                       value="Werknemer Toevoegen">

                                                                <button type="reset"
                                                                        data-dismiss="modal"
                                                                        class="btn btn-secondary">Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                    <!-- Account form starts -->
                                                    <!-- Account form ends -->
                                                </div>
                                                <!-- Information content ends -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<div class="sidenav-overlay"></div>
<div class="drag-target"></div>
<?php
include "partials/footer.php";
?>
<script>
    /*
        Notice aan 'slimme programmeurs' die denken de hier gebruikte apikey te kunnen achterhalen en te gebruiken:
        Spaar je de moeite. Je kunt inderdaad die apikey achterhalen. Maar het gaat toch niet werken. Het werkt alleen met een zelf aangevraagde apikey.
        Dus vraag die apikey gewoon aan, gebruik de gratis calls om te testen. Kleine moeite, groot gemak.
        Bijzondere situatie? Neem gewoon contact op.
    */
    var e		= "FbW29C_969cyVfAKrj";
    var postcode	= "";
    var huisnr		= "";
    var toevoeging	= "";

    function check_pc(wat,waarde) {
        if (wat=="postcode") {
            var pc = waarde.trim();
            if (pc.length <6) {maak_leeg();return;}					// POSTCODE MOET MINIMAAL 6 CHARACTERS BEVATTEN
            var num_deel = pc.substr(0,4);
            if (parseFloat(num_deel) < 1000) {maak_leeg();return;}	// HET NUMERIEKE DEEL MOET MINIMAAL 1000 ZIJN
            var alpha_deel = pc.substr(-2);
            if (alpha_deel.charCodeAt(0) < 65 || alpha_deel.charCodeAt(0) > 122 || alpha_deel.charCodeAt(1) < 65 || alpha_deel.charCodeAt(1) > 122 ) {maak_leeg();return;} 	// DE LAATSTE 2 POSITIES MOETEN LETTERS ZIJN
            alpha_deel = alpha_deel.toUpperCase();

            // NU WETEN WE ZEKER EEN POSTCODE TE HEBBEN DIE BEGINT MET 4 LETTERS EN EINDIGT OP 2 CIJFERS

            postcode = num_deel+alpha_deel;
            document.getElementById("postcode_p").value = postcode;
        }

        if (wat=="huisnr") {
            huisnr = parseFloat(waarde);
            if (!huisnr) {maak_leeg();return;}
            document.getElementById("huisnr_p").value = huisnr;
        }

        if (wat=="toevoeging") {
            toevoeging = waarde.trim();
            document.getElementById("toevoeging_p").value = toevoeging;
        }

        if (huisnr==0) {return;}

        var getadrlnk	= 'https://bwnr.nl/postcode.php?pc='+postcode+'&hn='+huisnr+'&tv='+toevoeging+'&tg=data&ak='+'FbW29C_969cyVfAKrj';	// e moet uw apikey bevattten.

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                rString = this.responseText;
                if (rString=="Geen resultaat.") {maak_leeg();return;}
                if (rString=="Input onvolledig.") {maak_leeg();return;}
                if (rString=="Onbekende API Key.") {maak_leeg();return;}
                if (rString=="Over quota") {maak_leeg();return;}
                if (rString=="Onjuiste API Key.") {maak_leeg();alert('Alleen functioneel indien geopend vanuit de API pagina. Ga terug naar de API pagina en probeer opnieuw.');return;}

                aResponse = rString.split(";");
                document.getElementById("straat_p").value=aResponse[0];
                document.getElementById("plaats_p").value=aResponse[1];
            }
        };

        xmlhttp.open("GET", getadrlnk , true);
        xmlhttp.send();
    }

    function maak_leeg() {
        document.getElementById("straat_p").value="";
        document.getElementById("plaats_p").value="";
    }

</script>
<script>
    /*
        Notice aan 'slimme programmeurs' die denken de hier gebruikte apikey te kunnen achterhalen en te gebruiken:
        Spaar je de moeite. Je kunt inderdaad die apikey achterhalen. Maar het gaat toch niet werken. Het werkt alleen met een zelf aangevraagde apikey.
        Dus vraag die apikey gewoon aan, gebruik de gratis calls om te testen. Kleine moeite, groot gemak.
        Bijzondere situatie? Neem gewoon contact op.
    */
    var e		= "FbW29C_969cyVfAKrj";
    var postcode	= "";
    var huisnr		= "";
    var toevoeging	= "";

    function check_pc(wat,waarde) {
        if (wat=="postcode") {
            var pc = waarde.trim();
            if (pc.length <6) {maak_leeg();return;}					// POSTCODE MOET MINIMAAL 6 CHARACTERS BEVATTEN
            var num_deel = pc.substr(0,4);
            if (parseFloat(num_deel) < 1000) {maak_leeg();return;}	// HET NUMERIEKE DEEL MOET MINIMAAL 1000 ZIJN
            var alpha_deel = pc.substr(-2);
            if (alpha_deel.charCodeAt(0) < 65 || alpha_deel.charCodeAt(0) > 122 || alpha_deel.charCodeAt(1) < 65 || alpha_deel.charCodeAt(1) > 122 ) {maak_leeg();return;} 	// DE LAATSTE 2 POSITIES MOETEN LETTERS ZIJN
            alpha_deel = alpha_deel.toUpperCase();

            // NU WETEN WE ZEKER EEN POSTCODE TE HEBBEN DIE BEGINT MET 4 LETTERS EN EINDIGT OP 2 CIJFERS

            postcode_z = num_deel+alpha_deel;
            document.getElementById("postcode_z").value = postcode_z;
        }

        if (wat=="huisnr") {
            huisnr_z = parseFloat(waarde);
            if (!huisnr) {maak_leeg();return;}
            document.getElementById("huisnr_z").value = huisnr_z;
        }

        if (wat=="toevoeging") {
            toevoeging_z = waarde.trim();
            document.getElementById("toevoeging_z").value = toevoeging_z;
        }

        if (huisnr==0) {return;}

        var getadrlnk	= 'https://bwnr.nl/postcode.php?pc='+postcode_z+'&hn='+huisnr_z+'&tv='+toevoeging_z+'&tg=data&ak='+'FbW29C_969cyVfAKrj';	// e moet uw apikey bevattten.

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                rString = this.responseText;
                if (rString=="Geen resultaat.") {maak_leeg();return;}
                if (rString=="Input onvolledig.") {maak_leeg();return;}
                if (rString=="Onbekende API Key.") {maak_leeg();return;}
                if (rString=="Over quota") {maak_leeg();return;}
                if (rString=="Onjuiste API Key.") {maak_leeg();alert('Alleen functioneel indien geopend vanuit de API pagina. Ga terug naar de API pagina en probeer opnieuw.');return;}

                aResponse = rString.split(";");
                document.getElementById("straat_z").value=aResponse[0];
                document.getElementById("plaats_z").value=aResponse[1];
            }
        };

        xmlhttp.open("GET", getadrlnk , true);
        xmlhttp.send();
    }

    function maak_leeg() {
        document.getElementById("straat_z").value="";
        document.getElementById("plaats_z").value="";
    }

</script>

</body>
<!-- END : Body-->
