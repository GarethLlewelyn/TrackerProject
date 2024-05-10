<!DOCTYPE html>
<html>
<head>
    <title>Bike Tracker Dashboard</title>
    <link rel="stylesheet" href="Style/Test.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" />
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAz_08BWiKHutCJIK9am3tT6Y4myQBtNAk&callback=initMap&v=weekly&loading=async">
    </script>
    <script src="JS/TrackerHandler.js" type="module"></script>
    <script src="JS/MapHandler.js" type="module" ></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
    <link
            rel="stylesheet"
            href="https://unpkg.com/@teleporthq/teleport-custom-scripts/dist/style.css"
    />
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ChangeSettingsForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                $.ajax({
                    type: "POST",
                    url: "Functions/SettingHandler.php",
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response === "1") {
                            $('#ErrorMsgSettings').text("Update Succesfull!");
                        } else {
                            $('#ErrorMsgSettings').text("An Error has occurred");
                            resetErrorMessage();
                        }
                    }
                });
            });
        });

    </script>



        <style data-tag="reset-style-sheet">
        html {  line-height: 1.15;}body {  margin: 0;}* {  box-sizing: border-box;  border-width: 0;  border-style: solid;}p,li,ul,pre,div,h1,h2,h3,h4,h5,h6,figure,blockquote,figcaption {  margin: 0;  padding: 0;}button {  background-color: transparent;}button,input,optgroup,select,textarea {  font-family: inherit;  font-size: 100%;  line-height: 1.15;  margin: 0;}button,select {  text-transform: none;}button,[type="button"],[type="reset"],[type="submit"] {  -webkit-appearance: button;}button::-moz-focus-inner,[type="button"]::-moz-focus-inner,[type="reset"]::-moz-focus-inner,[type="submit"]::-moz-focus-inner {  border-style: none;  padding: 0;}button:-moz-focus,[type="button"]:-moz-focus,[type="reset"]:-moz-focus,[type="submit"]:-moz-focus {  outline: 1px dotted ButtonText;}a {  color: inherit;  text-decoration: inherit;}input {  padding: 2px 4px;}img {  display: block;}html { scroll-behavior: smooth  }
    </style>
    <style data-tag="default-style-sheet">
        html {
            font-family: Inter;
            font-size: 16px;
        }

        body {
            font-weight: 400;
            font-style:normal;
            text-decoration: none;
            text-transform: none;
            letter-spacing: normal;
            line-height: 1.15;
            color: var(--dl-color-gray-black);
            background-color: var(--dl-color-gray-white);

        }
    </style>
</head>
<body>

<div class="home-container">

    <?php
    require_once("Header.php");

    ?>


<main>

    <div class="map" id="map" style="background-color: red;">
    </div>
    <div class="InfoContainer">
        <div class="info-cards">
            <div class="Infocard"> <!--Tracker card --!>
                <div class="card-icon">
                    <img src="IMG/Bike.png" class="Icon">
                </div>
                <div class="Card-Info-Container Card-Info-Shift">
                    <span class="Card-Title"> Tracker Status</span>
                    <span class="Card-SubTitle">  <span id="LastUpdatedDisplay"></span> Ago</span>
                    <span class="Card-SubTitle"> Address: </span>
                </div>
            </div>

            <div class="Small-Card-Container">
                <div class="Infocard"> <!--Battery card --!>
                    <div class="card-icon">
                        <img src="IMG/Battery.png" class="Icon">
                    </div>
                    <div class="Card-Info-Container">
                        <span class="Card-Title-Min"> Battery</span>
                        <span class="Card-SubTitle"> <span id="BatteryDisplay">gewg</span></span>
                    </div>
                </div>
                <div class="Infocard"> <!--GPS card --!>
                    <div class="card-icon">
                        <img src="IMG/Satelite.png" class="Icon" >
                    </div>
                    <div class="Card-Info-Container">
                        <span class="Card-Title-Min"> GPS Status</span>
                        <span class="Card-SubTitle"> <span id="GPSQualityDisplay">gewg</span></span>
                    </div>
                </div>
            </div>
            <div class="Small-Card-Container Card-Buttons"> <!--Bottom Buttons --!>
                <div id="TrackerSettings"class="Infocard Button-Content" style="width: 50%;"> <!--Battery card --!>
                    <span class="Card-Title-Min"> Settings</span>
                </div>
                <div id="StolenBikeButton" class="Infocard Button-Content" style="width: 50%;"> <!--Battery card --!>
                    <span class="Card-Title-Min"> Bike stolen!</span>
                </div>
            </div>
        </div>
    </div>


</main>
    <?php
    require_once("Footer.php");

    ?>
</div>
<div id='ChangeSettingsModal' class='modal'>
    <div class='modal-content'>
        <div>
            <span class='close-buttonTracker close-button'>&times;</span>
            <h2 class='Modal-Title'>Change tracker Settings</h2>
        </div>
        <div class='DescriptionContainer'>
            <span class='Description'> Change your trackers settings here</span>
        </div>
        <form id='ChangeSettingsForm' method='post' class='Standard-Form'>
            <input class='Standard-Form'type='text' placeholder='Enter Enter Sleep Ammount' value='<?php echo $_SESSION["TrackerID"] ?>' style="text-align: center; " disabled>
            <div class="FormSelection"  name='SleepAmm' id='SleepAmm' >
                <select name="SleepTimeValue" id="SleepTimeValue" >
                    <option disabled><?php echo $_SESSION["SleepTime"] ?> Currently selected </option>
                    <option value="20">20 Seconds</option>
                    <option value="30">30 Seconds</option>
                    <option value="40">40 Seconds</option>
                    <option value="50">50 Seconds</option>
                    <option value="60">1 minute</option>
                    <option value="120">2 minutes</option>
                    <option value="180">3 minutes</option>
                </select>
            </div>


            <input name="ContactNumber" id="ContactNumber" type="text"  value="<?php echo $_SESSION["ContactNumber"] ?>">
            <p style="text-align: center; margin-bottom: 10px;">Enter Alert Time for when bike alert is active and Alert Radius</p>

            <div class="FormSelection"  name='AlertRadiusValue' id='AlertRadiusValue' >
                <select name="AlertRadiusValue" id="AlertRadiusValue" >
                    <option disabled><?php echo $_SESSION["AlertRadius"] ?> Currently selected </option>
                    <option value="50">50 Meters</option>
                    <option value="100">100 Meters</option>
                    <option value="200">200 Meters</option>
                    <option value="500">500 Meters</option>

                </select>
            </div>

            <input class="TimeSelector" type="time" name="AlertActivationTimeStart" id="AlertActivationTimeStart" value="<?php echo $_SESSION["AlertActivationTimeStart"] ?>">
            <input class="TimeSelector" type="time" name="AlertActivationTimeEnd" id="AlertActivationTimeEnd" value="<?php echo $_SESSION["AlertActivationTimeEnd"] ?>">
            <button class='Standard-Form' id='ErrorMsgSettings' type='submit'>Change Settings</button>

        </form>
    </div>
</div>



<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"></script>


<script
        data-section-id="navbar"
        src="https://unpkg.com/@teleporthq/teleport-custom-scripts"
></script>

<script>


    //const TrackerSettings = document.getElementById("TrackerSettings"); // Get your div by its ID


    var ChangeSettingsModal = document.getElementById("ChangeSettingsModal");
    var openBtn = document.getElementById("TrackerSettings");
    var closeBtn = document.getElementsByClassName("close-buttonTracker")[0];




    openBtn.onclick = function() {
        ChangeSettingsModal.style.display = "flex";
    }

    // Close modal function
    closeBtn.onclick = function() {
        ChangeSettingsModal.style.display = "none";
    }

    // Close when clicking outside the modal
    window.onclick = function(event) {
        if (event.target == ChangeSettingsModal) {
            ChangeSettingsModal.style.display = "none";
        }
    }

    var BikeStolenModal = document.getElementById("BikeStolenModal");
    var TheftOpnBtn = document.getElementById("StolenBikeButton");
    var TheftcloseBtn = document.getElementsByClassName("close-buttonTracker")[0];

</script>




</body>
</html>