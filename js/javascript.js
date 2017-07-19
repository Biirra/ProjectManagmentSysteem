$(document).ready(function(){
    $("#functie-registratie").change(function(){
        if(this.value == 2){
            $("#input-geboortedatum").hide();
            $("#adres-gegevens-input").hide();
        }else{
            $("#input-geboortedatum").show();
            $("#adres-gegevens-input").show();
        }
    });
});