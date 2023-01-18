function MapFunctions() {

//     var lon = 0;
//     var lat = 0;
//     var zoom = 0;
//
//     map = new OpenLayers.Map("Map");
//     var mapnik = new OpenLayers.Layer.OSM();
//     map.addLayer(mapnik);
//
//     var fromProjection = new OpenLayers.Projection("EPSG:4326");
//     var toProjection = new OpenLayers.Projection("EPSG:900913");
//     var vectorLayer = new OpenLayers.Layer.Vector("Overlay"); // creates a layer where the new markers will be located
//
//     var position = new OpenLayers.LonLat(lon, lat).transform( fromProjection, toProjection);
//
// //map = new OpenLayers.Map("stalkMap");
// //var mapnik = new OpenLayers.Layer.OSM();
// //map.addLayer(mapnik);
//
//     map.setCenter(position, zoom);
//
// //generate an instance of mapFunctions to interact with the map.
//     var mapFunc = new MapFunctions();
//     mapFunc.construct(); //call the constructor function
//     controls = {selector: new OpenLayers.Control.SelectFeature(vectorLayer, {onSelect: mapFunc.createBox, onUnselect: mapFunc.deleteBox})};
//     map.addControl(controls["selector"]);
//     controls['selector'].activate();


    this.longitude = 0;
    this.latitude = 0;
    this.periodicallLocUpdate = 0; //variable that will periodically run the function to update the coordinates.
    this.periodicalFriendUpdate = 0; //vairable that will periodicall run the function to update the location of the friends.
    //this.vectorLayer = new OpenLayers.Layer.Vector("Overlay"); // creates a layer where the new markers will be located
    //variable that sets the controls of the boxes in the map.

    //the constructor of the class.
    this.construct = function(){
        //This function will run all the methods needed once the object its created.
        //Making all the neccesary function calls, easier for the programmer.
        this.locateFriends();
        this.updateLocation();
        this.periodicallLocUpdate = window.setInterval(this.updateLocation, 360000); // update the location every 6 minutes.
        //therefore 10 times per hour.
        this.periodicalFriendUpdate = window.setInterval(this.locateFriends, 420000);// update the friends locations every 7 minutes.

        // clearFeatures();
    }

    // private function that implements markers into the map
    function getMarker(longi, latit) {
        var marker = new OpenLayers.Layer.Markers("Markers");
        map.addLayer(marker);

        let mposition = new OpenLayers.LonLat(longi, latit).transform(fromProjection, toProjection);
        marker.addMarker(new OpenLayers.Marker(mposition));
    }

    //function that collects the location of the friends that the user has.
    this.locateFriends =  function(){
        var request = new XMLHttpRequest();
        request.open("GET", "map.php?option=getLatLong");

        request.onreadystatechange = function (){
            var DONE = 4;
            var OK = 200;

            if(request.readyState === DONE){
                if(request.status === OK){
                    var results = JSON.parse(request.responseText);
                    console.log(results);
                    for(var i = 0; i < results.length; i++){
                        let data = results[i];
                        //console.log(data._lat + " " + data._long + " " + data._photo + " " + data._username);
                        getMarker(data._long, data._lat);
                        addInfoBox(data._lat, data._long, String(data._photo), String(data._username));
                    }
                    // if we get at least 1 results, add the layer to the map.
                    if(results.length >= 1){
                        //
                        map.addLayer(vectorLayer);
                    }

                } else {
                    console.log("Error" + request.status);
                }
            }
        }

        request.send(null);
    }

    //Function that will periodically update the current location of the user.
    this.updateLocation = function(){
        //this function will use GET, to pass in the longitude and latitude of the user.
        if(!navigator.geolocation){
            console.log("This browser does not support geolocation");

        } else {
            console.log("Wow this thing works");
            //getCurrentPosition takes 3 parameters, but in this case we only used 2.
            //success parameter, which will execute the code given if the location was retrived succesfully
            //error parameter, will execute another code if the location was not retrieved.
            navigator.geolocation.getCurrentPosition(function(position){
                getLatLong(position.coords.latitude, position.coords.longitude);
            }, locationError);


        }
        //End of if

    }

    // private function to get the current location of the user.
    function getLatLong(latit, longit){
        // we create a xmlrequest, to send the data to the database.
        var sendData = new XMLHttpRequest();
        sendData.open("GET", "map.php?option=sendLatLong&latitude=" + latit + "&longitude=" + longit); // we send the longitude and latitude as a substring.

        //we proceed to handle the return message

        sendData.onreadystatechange = function(){
            var DONE = 4;
            var OK = 200;

            if(sendData.readyState === DONE){
                if(sendData.status === OK){
                    //we print out the response to check if it was success or not.
                    console.log(sendData.responseText);

                }
                // End of if
            }
            //End of if
        }
        //End of onreadystateChange.

        //we then proceed to add our own marker on the map.
        getMarker(longit, latit);
        //And  simple pop up box that says "you", clearly to indicate where you are.
        addInfoBox(latit, longit, null, "You");

        sendData.send();
    }

    // private function that will handle the error if the location is not available
    function locationError(){
        console.log("Location was not retreived.");
    }

    //function that will add the pop up boxes for each user.
    //this.addInfoBox = function(latit, longit, img, username){
    function addInfoBox(latit, longit, img, username){
        // if(img == null) {img = "images/blank.png"}
        var epsg4326 = new OpenLayers.Projection("EPSG:4326");
        //var projectTo = map.getProjectionObject();

        var box = new OpenLayers.Feature.Vector(
            new OpenLayers.Geometry.Point(longit, latit).transform(epsg4326, toProjection),
            {description: username + "<img src='../" + img + "' style='width:100px; height:100px; border-radius: 50px'>"},
            //{externalGraphic: '../images/marker.png', graphicHeight: 30, graphicWidth: 30, graphicXOffset:-12, graphicYOffset:-25  }
        );

        vectorLayer.addFeatures(box);

    }

    //function that will display the boxes when the icos are presed.
    this.createBox = function(box){
        box.popup = new OpenLayers.Popup.FramedCloud("pop",
            box.geometry.getBounds().getCenterLonLat(),
            null,
            "<div class='markerContent'>"+ box.attributes.description+ "</div>",
            null,
            true,
            function(){controls["selector"].unselectAll();}
        );

        map.addPopup(box.popup);

    }

    //function that will destroy the boxes when they are unselected.

    this.deleteBox = function(box){
        box.popup.destroy();
        box.popup = null;
    }


    function  destroyPopup(feature){
        feature.popup.destroy();
        feature.popup = null;
    }
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
    //
    // function queue(){
    //     // new MapFunctions();
    //     clearFeatures();
    // }


}



