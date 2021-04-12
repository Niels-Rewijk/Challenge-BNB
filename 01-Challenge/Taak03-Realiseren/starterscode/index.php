<?php
// Je hebt een database nodig om dit bestand te gebruiken....
include 'database.php';
if (!isset($db_conn)) { //deze if-statement checked of er een database-object aanwezig is. Kun je laten staan.
    return;
}

$database_gegevens = null;
$poolIsChecked = false;
$bathIsChecked = false;
$bbqIsChecked = false;
$fireplaceIsChecked = false; 
$bikerentalIsChecked = false;

$sql = "Select * FROM homes"; //Selecteer alle huisjes uit de database

if (isset($_GET['filter_submit'])) {
    if ($_GET['faciliteiten'] == "ligbad") { // Als ligbad is geselecteerd filter dan de zoekresultaten
        $bathIsChecked = true;
        $sql = "SELECT bath_present FROM homes"; // query die zoekt of er een BAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "zwembad") {
        $poolIsChecked = true;
        $sql = "SELECT `pool_present` FROM `homes`"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "Bbq") {
        $bbqIsChecked = true;
        $sql = "SELECT `bbq_present` FROM `homes`"; // query die zoekt of er een bbq aanwezig is.
    }
    if ($_GET['faciliteiten'] == "Vuurplaats") {
        $fireplaceIsChecked = true;
        $sql = "SELECT `fireplace_present` FROM `homes`"; // query die zoekt of er een fireplace aanwezig is.
    }
    if ($_GET['faciliteiten'] == "Fietsen te huur") {
        $bikerentalIsChecked = true;
        $sql = "SELECT `bike_rental` FROM `homes`"; // query die zoekt of er een bike_rental aanwezig is.
    }
}


if (is_object($db_conn->query($sql))) { //deze if-statemeschrnt controleert of een sql-query correct geeven is en dus data ophaalt uit de DB
    $database_gegevens = $db_conn->query($sql)->fetchAll(PDO::FETCH_ASSOC); //deze code laten staan
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="menu p-3 border mb-3">
        <h1>Huur hier zo'n huisje</h1>
        <div class="driehoek"></div>        
        </div>
    </header>
    <main>
        <div class="left">
        <div class="homes-box">
            <?php if (isset($database_gegevens) && $database_gegevens != null) : ?>
                <?php foreach ($database_gegevens as $huisje) : ?>
                    <div class="huizen p-3 border shadow mb-5 bg-body rounded">
                    <h4>
                        <?php echo $huisje['name']; ?>
                    </h4>

                    <p>
                        <img src='images/<?php echo $huisje["image"]; ?> ' heigt=50% width=50%x>                      
                        <?php echo $huisje['description']; ?>
                    </p>
                    <div class="kenmerken">
                        <h6>Kenmerken</h6>
                        <ul>                        
                            <?php
                            if ($huisje['bath_present'] ==  1) {
                                echo "<li>Er is ligbad!</li>";
                            }
                            ?>
                            <?php
                            if ($huisje['pool_present'] ==  1) {
                                echo "<li>Er is zwembad!</li>";
                            }
                            ?>
                            <?php
                            if ($huisje['bbq_present'] == 1) {
                                echo "<li>Er is een BBQ!</li>";
                            }
                            ?>
                            <?php
                            if ($huisje['fireplace_present'] == 1) {
                                echo "<li>Er is een vuurplek!</li>";
                            }
                            ?>
                            <?php
                            if ($huisje['bike_rental'] == 1) {
                                echo "<li>Je kan fietsen huren!</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>                   
        </div>     
        </div>
        <div class="right">            
            <div class="filter-box">
                <form class="filter-form">
                    <div class="form-control">
                        <a href="index.php">Reset Filters</a>
                    </div>
                    <div class="form-control">
                        <label for="ligbad">Ligbad</label>
                        <input type="radio" id="ligbad" name="faciliteiten" value="ligbad" <?php if ($bathIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="zwembad">Zwembad</label>
                        <input type="radio" id="zwembad" name="faciliteiten" value="zwembad" <?php if ($poolIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="bbq">BBQ</label>
                        <input type="radio" id="bbq" name="faciliteiten" value="bbq" <?php if ($bbqIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="fireplace">Vuurplaats</label>
                        <input type="radio" id="fireplace" name="faciliteiten" value="fireplace" <?php if ($fireplaceIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="bike_rental">Fiets verhuur</label>
                        <input type="radio" id="bike_rental" name="faciliteiten" value="bike_rental" <?php if ($bikerentalIsChecked) echo 'checked' ?>>
                    </div>
                    <button type="submit" name="filter_submit">Filter</button>
                </form>
            <div id="mapid"></div>
            <div class="book">
                <h3>Reservering maken</h3>
                <div class="form-control">
                    <label for="aantal_personen">Vakantiehuis</label>
                    <select name="gekozen_huis" id="gekozen_huis">
                        <option value="1">IJmuiden Cottage</option>
                        <option value="2">Assen Bungalow</option>
                        <option value="3">Espelo Entree</option>
                        <option value="4">Weustenrade Woning</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="aantal_personen">Aantal personen</label>
                    <input type="number" name="aantal_personen" id="aantal_personen">
                </div>
                <div class="form-control">
                    <label for="aantal_dagen">Aantal dagen</label>
                    <input type="number" name="aantal_dagen" id="aantal_dagen">
                </div>
                <div class="form-control">
                    <h5>Beddengoed</h5>
                    <label for="beddengoed_ja">Ja</label>
                    <input type="radio" id="beddengoed_ja" name="beddengoed" value="ja">
                    <label for="beddengoed_nee">Nee</label>
                    <input type="radio" id="beddengoed_nee" name="beddengoed" value="nee">
                </div>
                <button>Reserveer huis</button>
            </div>
            <div class="currentBooking">
                <div class="bookedHome"></div>
                <div class="totalPriceBlock">Totale prijs &euro;<span class="totalPrice">0.00</span></div>
            </div>
            </div>
        </div>

    </main>
    
    <footer>
        <div></div>
        <div>copyright Quattro Rentals BV.</div>
        <div></div>

    </footer>
    <script src="js/map_init.js"></script>
    <script>
        // De verschillende markers moeten geplaatst worden. Vul de longitudes en latitudes uit de database hierin
        var coordinates = [
            ["52.44902", "4.61001"],
            ["52.99864", "6.64928"],
            ["52.30340", "6.36800"],
            ["50.89720", "5.90979"]
        ];

        var bubbleTexts = [
            "<h2>IJmuiden Cottage</h2> <img src=images/Ijmuiden.jpg width=100% height=100%>",
            "<h2>Assen Bungalow</h2> <img src=images/Assen.jpg width=100% height=100%>",
            "<h2>Espelo Entree</h2> <img src=images/Espelo.jpg width=100% height=100%>",
            "<h2>Weustenrade Woning</h2> <img src=images/Weustenrade.jpg width=100% height=100%>"
        ];
    </script>
    <script src="js/place_markers.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
</body>

</html>