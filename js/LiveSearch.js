/**
 * This function would be responsible for
 * bringing up search result as user types in search input
 * @param term
 */
function liveSearch(term){
    const profile = "Profile";
    console.log("live search");
    //creates instance of WebRequest class
    let w = new WebRequest();
    w.getXMLHttpRequest().onreadystatechange = function (){
        if(w.isValid()){
            let result = [];
            result = JSON.parse(w.getXMLHttpRequest().responseText);

            let liveSearchBody = "";
            let liveSearchD = document.getElementById("searchResult");
            // console.log("work man");
            //for each result show what is in the live search body
            result.forEach(function (obj){
                if(obj._photo == null) {
                    obj._photo = '../images/blank.png';
                }
                liveSearchBody +=
                //what would appear from search
                    "<div class='col col-md-12 pt-2 px-2 my-5'>" +
                    "<div class='card text-center ' style='width: 18rem;'>" +
                    "<div class='card-body' style='background-color: coral'>" +
                    "<h5 class='card-title img-rounded text-center'>" +
                    "<img src=" + "'" + obj._photo + "'" + " alt='' height='90' width='90' style='border-radius: 50px;'>" +
                    "</h5>" +
                    "<p class='card-text text-center'>" +
                    "<strong>" + obj._name + "</strong>" +
                    "</p>" +
                    "<p class='card-text text-center'>" + obj._username + "</p>" +
                    "<button>" +
                        "<a href=" + "'../profile.php?id=" + obj._id + "'" + ">" + profile + "</a>" +
                    "</button>" +
                    "<button>" +
                    "<a href=" + "'../request.php?user_id=" + obj._id + "'" + ">Request</a>" +
                    "</button>" +
                    "</div>" +
                    "</div>" +
                    "</div>";

                // console.log("username: " + liveSearchBody);
            });
            //make div element of livesearchD show livesearch body
            liveSearchD.innerHTML = liveSearchBody
        }
    }

    // let xhr = new XMLHttpRequest();
    // xhr.onreadystatechange = function (){
    //     if(xhr.readyState == 4 & xhr.status == 200){
    //
    //         // liveSearch.innerHTML = "work please";
    //     }
    // }
    w.getXMLHttpRequest().open("GET", "../search.php?liveSearch=" + term);//conects to php
    w.getXMLHttpRequest().send(null);
}