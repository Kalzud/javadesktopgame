/**
 * receive friend request in real time
 */
function liveRequest(){
    const profile = "Profile";
    console.log("live request");
    //creates instance of WebRequest class
    let w = new WebRequest();
    w.getXMLHttpRequest().onreadystatechange = function (){
        if(w.isValid()){
            let result = [];
            result = JSON.parse(w.getXMLHttpRequest().responseText);

            let liveRequestBody = "";
            let liveRequestD = document.getElementById("requests");
            // console.log("work man");
            //for each result show what is in the live search body
            result.forEach(function (obj){
                if(obj._photo == null) {
                    obj._photo = '../images/blank.png';
                }
                liveRequestBody +=
                    //what would appear at request
                    "<div class='col col-md-12 pt-2 px-2 my-5'>" +
                    "<div class='card text-center ' style='width: 24rem;'>" +
                    "<div class='card-body' style='background-color: coral'>" +
                    "<h5 class='card-title img-rounded text-center'>" +
                        "<img src=" + "'" + obj._photo + "'" + " alt='' height='90' width='90'>" +
                    "</h5>" +
                    "<p class='card-text text-center'>" +
                    "<strong>" + obj._name + "</strong>" +
                    "</p>" +
                    "<p class='card-text text-center'>" + obj._username + "</p>" +
                    "<button>" +
                    "<a href=" + "'../profile.php?id=" + obj._id + "'" + ">" + profile + "</a>" +
                    "</button>" +
                    "<button>" +
                    "<a href=" + "'../request.php?acc_user_id=" + obj._id + "'" + ">Accept</a>" +
                    "</button>" +
                    "<button>" +
                    "<a href=" + "'../request.php?del_user_id=" + obj._id + "'" + ">Remove</a>" +
                    "</button>" +
                    "</div>" +
                    "</div>" +
                    "</div>";

                // console.log("username: " + liveSearchBody);
            });
            //make div element of livesearchD show livesearch body
            liveRequestD.innerHTML = liveRequestBody
        }
    }

    // let xhr = new XMLHttpRequest();
    // xhr.onreadystatechange = function (){
    //     if(xhr.readyState == 4 & xhr.status == 200){
    //
    //         // liveSearch.innerHTML = "work please";
    //     }
    // }
    w.getXMLHttpRequest().open("GET", "../request.php?liveRequest=true");//conects to php
    w.getXMLHttpRequest().send(null);
}

/**
 * method to constantly update requests
 */
function queue(){
    liveRequest();
}

/**
 * To refresh every second
 */
document.addEventListener('DOMContentLoaded', function (){
    const interval = setInterval(queue, 2000);
});