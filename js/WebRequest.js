/**
 * Controls new XMLHttpRequests
 */
class WebRequest{
     #x = new XMLHttpRequest();
     constructor() {}

    print(){
         //if connected would show connected on console
         console.log("Connected");
    }

    //checks readystate and status
    isValid(){
         return this.#x.readyState == 4 && this.#x.status == 200;
    }

    //get state change
    getOnReadyStateChange(){
         return this.#x.onreadystatechange;
    }

    //getXMLHttpRequest
    getXMLHttpRequest(){
         return this.#x;
    }
}