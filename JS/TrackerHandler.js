
let TrackerDetailsUse;
export function PostTrackerInfo(){

    fetch('Functions/TrackerDetailsHandler.php')
        .then(response => response.json())
        .then(TrackerDetails => {
            TrackerDetailsUse = TrackerDetails; // Update global Locations array
            console.log("Tracker details");
            ChangeDisplay();
            // Add the new markers to the map (refer to your existing code)
        }).catch(error => console.error('Error fetching Tracker Session Info:', error));

    }

    function ChangeDisplay(){
        console.log("display changed");
        let LastUpdatedDisplay = document.getElementById("LastUpdatedDisplay");
        let BatteryDisplay = document.getElementById("BatteryDisplay");
        let GPSDisplay = document.getElementById("GPSQualityDisplay");

        LastUpdatedDisplay.textContent = TrackerDetailsUse["TimeStamp"];
        BatteryDisplay.textContent = TrackerDetailsUse["Battery"];
        GPSDisplay.textContent = TrackerDetailsUse["SatQuality"];



    }
