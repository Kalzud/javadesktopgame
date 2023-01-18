/**
 * The following document is responsible for the creation of the map,
 * addition and update of friends locations
 * and creation of markers on the locations
 */

/**
 * Creation of the Map
 */
//creates variable map
var map = new OpenLayers.Map("Map");
//adds a layer to the map
map.addLayer(new OpenLayers.Layer.OSM());

//constructs the map and creates the positioning
epsg4326 = new OpenLayers.Projection("EPSG:4326");
projectTo = map.getProjectionObject();

//Setting the Lon , Lat and Zoom
var lonLat = new OpenLayers.LonLat(-0.1279688, 51.5077286).transform(epsg4326, projectTo);

var zoom = 3;
//positions the map at the center
map.setCenter(lonLat, zoom);
//creates vector layer
var vectorLayer = new OpenLayers.Layer.Vector("Overlay");


/**
 * This function would determine the live location of the friends
 * and add markers to those locations
 * it would also add other features like the popups and layers
 */
function liveLocation(){
    // console.log("live map");
    // //creates instance of WebRequest class
    // let w = new WebRequest();
    // if(w.isValid()){
    //
    // }
    let xhr = new XMLHttpRequest();
    //create empty array to store the friend locations
    let locations = [];

    xhr.onreadystatechange = function (){
        if (xhr.readyState == 4 && xhr.status == 200)//success and failure coding
        {
            locations = JSON.parse(xhr.responseText);
            //create markers for each location
            locations.forEach(createMarkers);
        }
    }
    // add the vector layer to the map
    map.addLayer(vectorLayer);
    //controls to show popup
    let controls = {
        selector: new OpenLayers.Control.SelectFeature(vectorLayer, {
            onSelect: createPopup, onUnselect: destroyPopup})
    }

    map.addControl(controls['selector']);
    controls['selector'].activate();

    xhr.open('GET', '../friend.php?liveMap=true');
    xhr.send();
}

// function getMarker(longi, latit) {
//     var marker = new OpenLayers.Layer.Markers("Markers");
//     map.addLayer(marker);
//
//     let mposition = new OpenLayers.LonLat(longi, latit).transform(fromProjection, toProjection);
//     marker.addMarker(new OpenLayers.Marker(mposition));
// }
/**
 * Making the markers
 */
function createMarkers(item){
    console.log("l: " + item[2] + ", l1: " + item[1]);
    if(item[3] == null) {
        item[3] = '../images/blank.png';
    }
    var feature = new OpenLayers.Feature.Vector(
        //places the marker at identified user position
        new OpenLayers.Geometry.Point(item[2], item[1]).transform(epsg4326, projectTo),
        {description: item[0] + "<img src=" + "'" + item[3] + "'" + " alt='' height='90' width='90' style='border-radius: 50px;'>"},
        {
            //change the look of the marker
            externalGraphic: '../images/marker.png', graphicHeight: 30, graphicWidth: 30,
            graphicXOffset: -12, graphicYOffset: -25
        }
    );
    //add the new features to the layer
    vectorLayer.addFeatures(feature);
}

/**
 *
 * @param feature
 *
 * create the pop up when the marker is clicked
 */
function createPopup(feature){
    feature.popup = new OpenLayers.Popup.FramedCloud(
        "pop",
        feature.geometry.getBounds().getCenterLonLat(),
        null,
        '<div class="markerContent">' + feature.attributes.description +'</div>',
        null,
        true,
        function(){controls['selector'].unselectAll();}
    );
    //add the pop up to the map
    map.addPopup(feature.popup);

}

/**
 * Closes the pop up
 * @param feature
 */
function destroyPopup(feature){
    feature.popup.destroy();
    feature.popup = null;
}

/**
 * clear map features
 */
function clearFeatures(){
    var features = vectorLayer.features;

    //destroy
    vectorLayer.features.forEach(function (feature){
        if(feature.popup != null){
            destroyPopup(feature);
        }
    });
    vectorLayer.removeFeatures(features);
}

/**
 * method to constantly update live location
 */
function queue(){
    liveLocation();
    clearFeatures();
}

/**
 * To refresh the layer every second
 */
document.addEventListener('DOMContentLoaded', function (){
    const interval = setInterval(queue, 2000);
});













// //Function that will periodically update the current location of the user.
// this.updateLocation = function(){
//     //this function will use GET, to pass in the longitude and latitude of the user.
//     if(!navigator.geolocation){
//         console.log("This browser does not support geolocation");
//
//     } else {
//         console.log("Wow this thing works");
//         //getCurrentPosition takes 3 parameters, but in this case we only used 2.
//         //success parameter, which will execute the code given if the location was retrived succesfully
//         //error parameter, will execute another code if the location was not retrieved.
//         navigator.geolocation.getCurrentPosition(function(position){
//             getLatLong(position.coords.latitude, position.coords.longitude);
//         }, locationError);
//
//
//     }
//     //End of if
//
// }
//
// // private function to get the current location of the user.
// function getLatLong(latit, longit){
//     // we create a xmlrequest, to send the data to the database.
//     var sendData = new XMLHttpRequest();
//     sendData.open("GET", "map.php?option=sendLatLong&latitude=" + latit + "&longitude=" + longit); // we send the longitude and latitude as a substring.
//
//     //we proceed to handle the return message
//
//     sendData.onreadystatechange = function(){
//         var DONE = 4;
//         var OK = 200;
//
//         if(sendData.readyState === DONE){
//             if(sendData.status === OK){
//                 //we print out the response to check if it was success or not.
//                 console.log(sendData.responseText);
//
//             }
//             // End of if
//         }
//         //End of if
//     }
//     //End of onreadystateChange.
//
//     //we then proceed to add our own marker on the map.
//     createMarkers(item);
//     //And  simple pop up box that says "you", clearly to indicate where you are.
//     // addInfoBox(latit, longit, null, "You");
//
//     sendData.send();
// }