var itemValues = [];

$(document).ready(function() {
    $.ajax({
        dataType: "json",
        method: 'GET',
        url: "calendar/mijn-opdrachten-calendar.php",
        data: {

        }
    }).done(function (data){

        for(var i = 0; i < data.length; i++){
            $('.selectie-list').append(data, listItem(data[i]['opdracht-naam'], data[i]['opdracht-id']));
        }
        getItemValues(data);
        $('.itemCheckbox').change(function () {
            if (this.checked) {
                getItemValues(data);
            }else{
                getItemValues(data);
            }
        });

    }).fail(function (data) {
        //console.log(data);
    })

});
function getItemValues(data){
    itemValues = $('input:checkbox:checked.itemCheckbox').map(function () {
        return this.value;
    }).get(); // ["18", "55", "10"]
    fillCalendar(data, itemValues);
}




function listItem(itemName, itemID){
    var tr = document.createElement("tr");
    if(itemID) {
        var tdcheckboxHolder = document.createElement("td");
        var tditemNameHolder = document.createElement("td");
        var tditemLinkHolder = document.createElement("td");

        $(tr).append(tdcheckboxHolder, tditemNameHolder, tditemLinkHolder);

        var checkboxHolder = document.createElement("div");
        var itemNameHolder = document.createElement("div");
        var itemLinkHolder = document.createElement("div");
        $(tdcheckboxHolder).append(checkboxHolder);
        $(tditemNameHolder).append(itemNameHolder);
        $(tditemLinkHolder).append(itemLinkHolder);

        var checkbox = document.createElement("input");

        $(checkbox).attr({
            class: 'itemCheckbox'
            , name: 'itemCheckbox'
            , checked: 'true'
            , value: itemID
            , type: 'checkbox'
        });

        $(checkboxHolder).attr({id: itemID}).append(checkbox);

        var itemNameLabel = document.createElement("label");
        $(itemNameLabel).attr({for: itemID}).text(itemName);
        $(itemNameHolder).attr({}).append(itemNameLabel);


        var itemLink = document.createElement("a");
        $(itemLink).attr({
            href: "opdrachten.php?state=opdracht-info&id=" + itemID
        });
        $(itemLinkHolder).append(itemLink);
        var itemLinkIcon = document.createElement("i");

        $(itemLinkIcon).attr({
            class: 'fa fa-arrow-right',
            aria: 'hidden'
        })
        $(itemLink).append(itemLinkIcon);

    }else{

        var tdMessage = document.createElement("td");
        $(tr).append(tdMessage);
        var messageLabel = document.createElement("label");
        $(tdMessage).append(messageLabel);
        $(messageLabel).text(itemName);
    }
    return tr;
}