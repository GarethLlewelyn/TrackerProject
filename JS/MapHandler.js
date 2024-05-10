// Initialize and add the map
import {PostTrackerInfo} from './TrackerHandler.js';
const { Map } = await google.maps.importLibrary("maps");
const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
let map;
let Locations = [];
let marker = [];
let IconPin;
let PathLine;


async function initMap() {
    // The map, centered at CurrentLocation
    const options = {
        tilt: 35,
        heading: 0,
        zoom: 15,
        center: Locations[0],
        mapId: "a103b572f978edba",
        rotateControl: true,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
            mapTypeIds: ["roadmap", "terrain"], //Find what else you can add
        },
    };
    map = new Map(document.getElementById("map"), options)
    // Initial refresh
    AddMarkers();
    // Set interval for refreshing every 60 seconds
    setInterval(refreshMarkers, 60000);
    Drawline(Locations);
    PathLine.setMap(map);
}


function Drawline(Locations){
    PathLine = [];
    PathLine =  new google.maps.Polyline({
        path: Locations,
        geodesic: true,
        strokeColor: "#f57171",
        strokeOpacity: 1.0,
        strokeWeight: 2,
    });
    console.log("Redrawing line");
}


function AddMarkers(){
    for (let i = 0; i < Locations.length; i++) {
        if(i == 0){
            console.log("last")
            IconPin = new PinElement({
                scale: 2,
                borderColor: "#de7b8a",
                background: "#f38167",
            });
            marker[i] = new AdvancedMarkerElement({
                position: Locations[i],
                map: map,
                content: IconPin.element,
                zIndex: 1000 - i,
            });
        }else{
            marker[i] = new google.maps.Circle({
                strokeColor: "#6c5353",
                fillColor: "#725a5a",
                fillOpacity: 0.1,
                map,
                center: Locations[i],
                radius: 10,
            });
        }
    }

}

function clearMarkers() {
    for (let i = 0; i < marker.length; i++) {
        marker[i].setMap(null); // Removes marker from the map
    }
    marker = [];
    Locations = [];
}

function refreshMarkers() {
    //clear plotted markers
    console.log("Refreshing markers");
    // Re-fetch marker data and add new markers
    fetch('Functions/LocationRetrieve.php')
        .then(response => response.json())
        .then(locations => {
            clearMarkers(); //clears markers on screen
            Locations = locations; // Update global Locations array
            AddMarkers(); //Add markets onto map
            Drawline(Locations);
            PostTrackerInfo();
            // Add the new markers to the map (refer to your existing code)
        }).catch(error => console.error('Error fetching locations:', error));
}


fetch('Functions/LocationRetrieve.php')
    .then(response => response.json())
    .then(locations => {
        // Save the locations
        Locations = locations;
        PostTrackerInfo();
        initMap();
        // Process and add markers based on 'myLocations'
    })
    .catch(error => console.error('Error fetching locations:', error));






