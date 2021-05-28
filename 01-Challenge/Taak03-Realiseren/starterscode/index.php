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
        $sql = "SELECT * FROM homes WHERE bath_present = 1"; // query die zoekt of er een BAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "zwembad") {
        $poolIsChecked = true;
        $sql = "SELECT * FROM homes WHERE pool_present = 1"; // query die zoekt of er een ZWEMBAD aanwezig is.
    }
    if ($_GET['faciliteiten'] == "bbq") {
        $bbqIsChecked = true;
        $sql = "SELECT * FROM homes WHERE bbq_present = 1"; // query die zoekt of er een bbq aanwezig is.
    }
    if ($_GET['faciliteiten'] == "vuurplaats") {
        $fireplaceIsChecked = true;
        $sql = "SELECT * FROM `homes` WHERE `fireplace_present` = 1"; // query die zoekt of er een fireplace aanwezig is.
    }
    if ($_GET['faciliteiten'] == "fietsen te huur") {
        $bikerentalIsChecked = true;
        $sql = "SELECT * FROM homes WHERE bike_rental = 1"; // query die zoekt of er een bike_rental aanwezig is.
    }
}


if (is_object($db_conn->query($sql))) { //deze if-statemeschrnt controleert of een sql-query correct geeven is en dus data ophaalt uit de DB
    $database_gegevens = $db_conn->query($sql)->fetchAll(PDO::FETCH_ASSOC); //deze code laten staan
    // echo "<pre>";var_dump($database_gegevens); exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BnB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="menu p-3 border mb-3">
        <h1 class="naam">Huur hier zo'n huisje</h1>
        <div class="driehoek"></div>        
        </div>
    </header>
    <main>
        <div class="left ">
        <div class="homes-box">
            <?php if (isset($database_gegevens) && $database_gegevens != null) : ?>
                <?php foreach ($database_gegevens as $huisje) : ?>
                    <div class="huizen p-3 border shadow mb-5 bg-body rounded overflow-hidden">
                    <h4>
                        <?php echo $huisje['name']; ?>
                    </h4>
                    <p>
                        <img class="plaatjes p-1" src='images/<?php echo $huisje["image"]; ?> ' heigt=50% width=50% >                      
                        <?php echo $huisje['description']; ?>
                    </p>
                    <div class="schuinelijn"></div> 
                    <div class="kenmerken"> <!-- De kenmerken die onder het huisje staan -->
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
                            <?php 
                                echo "<li> &#8364;".$huisje['price_p_p_p_n']. " per persoon per nacht</li>";
                            ?>
                            <?php 
                                echo "<li> Beddengoed kost hier &#8364;".$huisje['price_bed_sheets']. "</li>";
                            ?>
                        </ul>
                    </div>                    
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>                             
        </div>     
        </div>
        <div class="right">            
            <div class="filter-box"> <!-- Het filter -->
                <form class="filter-form pb-5">
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
                        <input type="radio" id="vuurplaats" name="faciliteiten" value="vuurplaats" <?php if ($fireplaceIsChecked) echo 'checked' ?>>
                    </div>
                    <div class="form-control">
                        <label for="bike_rental">Fiets verhuur</label>
                        <input type="radio" id="fietsen te huur" name="faciliteiten" value="fietsen te huur" <?php if ($bikerentalIsChecked) echo 'checked' ?>>
                    </div>
                    <button type="submit" name="filter_submit">Filter</button>
                </form>
                <br></br>
                <div id="mapid"></div> <!--- de map -->
                <br></br>
            <div class="book">
                <h3>Reservering maken</h3>
                <div class="form-control"> <!-- Het winkelandje -->
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
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
                <button name="bereken">Bereken prijs</button> <!-- bereken knop -->
                </form>
                <button onclick="arm()">Betalen</button> <!-- betaal knop -->
                <script>
                function arm() {
                    alert("Er staat niet genoeg geld op je rekening");
                    }
                </script>
            </div>
            <div class="currentBooking"> <!-- de berekeningen voor het winkelmandje --> 
                <div class="bookedHome"></div>
                <?php 
                $personen = 0;
                $dagen = 0;
                $gekozen_huis = 1;  
                $prijs_huisje = 0; 
                $totaal_prijs = 0;
                $bedden = 0;
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $personen = $_POST["aantal_personen"];
                    if (empty($personen)) {
                        echo "Gelieve iets in te vullen bij personen! <br>"  ;
                        $personen = 0;
                    } 
                }
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $dagen = $_POST["aantal_dagen"];
                    if (empty($dagen)) {
                        echo "Gelieve iets in te vullen bij de dagen! <br>";
                        $dagen = 0;
                    } 
                }     
                if ($_SERVER["REQUEST_METHOD"] == "POST") {// huis kiezen
                    $gekozen_huis = $_POST["gekozen_huis"];
                    $sql = "SELECT price_p_p_p_n FROM homes WHERE id = $gekozen_huis"; // query die zoekt naar de prijs per persoon per nacht                   
                    if (is_object($db_conn->query($sql))) { //deze if-statemeschrnt controleert of een sql-query correct geeven is en dus data ophaalt uit de DB       
                        $prijs_huisje = $db_conn->query($sql)->fetch(PDO::FETCH_ASSOC); //deze code laten staan
                        // echo "<pre>";var_dump($database_gegevens); exit;                                               
                    }             
                }
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $bedden = $_POST["beddengoed"];
                    if ($bedden == "ja"){
                        if ($gekozen_huis == 1){
                            $bedden = 10;
                        } else {
                        $bedden = 0;}    
                    } else
                    if ($bedden == "nee"){
                        $bedden = 0;
                    }
                    if (empty($bedden)) {
                        echo "Gelieve iets in te vullen bij beddengoed!";
                        $bedden = 0;
                    }
                }
                if (isset($_POST['bereken'])) {
                $totaal_prijs = (($dagen*$prijs_huisje["price_p_p_p_n"]*$personen)+$bedden); 
                }
                ?>                 
                <div class="totalPriceBlock">Totale prijs &#8364;<span class="totalPrice"><?php echo $totaal_prijs;?></span></div>
            </div>            
            </div>            
        </div>
    </main>
    
    <footer>
        <div></div>
        <div class="d-flex justify-content-center">copyright Quattro Rentals BV.</div>
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