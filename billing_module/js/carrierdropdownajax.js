$(function() {
    //SEARCH SUBJECT AUTOCOMPLETE AJAX
    $('.ajax_search').bind('click change focus', function() {
        var id = this.id;
        var ptid = null;
        if(id == 'ProbID') ptid = localStorage.getItem("ptid_hidden");
        $(this).autocomplete({
            minLength: 0,
            source: function(request, response) {
                $.ajax({
                    type: "POST",
                    url: "autocomplete_name_dropdown.php",
                    data: { "query": request.term, "id": id, "ptid_hidden": ptid },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {   
                        response($.map(data, function(obj) {
                            return {
                                id: obj.id,
                                name: obj.name
                            };
                        }));
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            },
            select: function(event, ui) {
                //PREVENT AUTOMATIC UPDATE OF TEXTBOX
                event.preventDefault();
                $('#'+this.id).val($("<div>").html(ui.item.id).text());
                if(id == 'ProbID') $('#psearch').trigger('click');
                else $('#search').trigger('click');
            }
        }).focus(function() {
            $(this).trigger('keydown.autocomplete');
        }).data("autocomplete")._renderItem = function(ul, item) {
            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<a>"+item.id+"&emsp;<span style='color:#2c63c2;'>"+item.name+"</span></a>")
                .appendTo(ul);
        };
    });
    //FOR EACH CODES WHICH HAS THE CLASS '.auto_dropdown'
    $('.auto_dropdown').bind('click change focus', function() {
        var id = this.id;
        if ($(this).val() == '') {
            var desc = '#'+this.id+'-desc';
            $(desc).empty();
            desc = '#display_for_'+this.id;
            $(desc).empty();
        }
        var page = '';
        var items = '';  
        var order = '';
        $(this).autocomplete({     
            minLength: 0,
            source: function(request, response) {  
                //console.log('id: '+id);
                if (id.indexOf('Carrier') == 0) {
                    order = id.slice(-1);
                    page = "personal_carrier";  
                    items = 'triple';
                }
                else if(id.indexOf('LC') == 0) {
                    page = "location";
                    items = 'double';
                }
                else if(id.indexOf('PV') == 0) {
                    page = "provider";
                    items = 'double';
                }
                else if(id.indexOf('diag') == 0) {
                    page = "diagnosis";
                    items = 'triple';
                }
                else if(id.indexOf('ProcID') == 0) {
                    page = "procedure";
                    items = 'triple';
                }
                else if(id.indexOf('mod') == 0) {
                    page = "modifier";
                    items = 'double';
                }
                $.ajax({
                    type: "POST",
                    url: "search_dropdown.php",
                    data: "query="+request.term+"&page="+page,
                    dataType: 'json',
                    cache: false,
                    success: function(data) {  
                        response($.map(data, function(obj) {
                            if(items == "triple") {
                                return {
                                    id: obj.id,
                                    label: obj.name,
                                    extension: obj.extension,
                                    page: obj.page,
                                };
                            }
                            else {
                                return {
                                    id: obj.id,
                                    label: obj.name,
                                    page: obj.page,
                                };  
                            }
                        }));
                    },
                    error: function(error) {
                        console.log('Error: ');
                        console.log(error);
                    }
                });
            },
            select: function(event, ui) { 
                //PREVENT AUTOMATIC UPDATE OF TEXTBOX
                event.preventDefault();
                var select_id = '';
                var desc = '';
                page = ui.item.page;
                if($.inArray(page, ["location", "provider"]) > -1) {
                    $('#'+this.id).val($("<div>").html(ui.item.label).text());
                    select_id = '#'+this.id+'ID';
                    $(select_id).val(ui.item.id);  
                }
                else if (page == "personal_carrier") {
                    $('#PolicyForm #ICID').val(ui.item.id);
                    var Cinput = $('#PolicyForm #Carrier');
                    console.log(Cinput.val());
                    if (Cinput.val() !== 'undefined' && Cinput.val() != null) {
                        Cinput.val($("<div>").html(ui.item.label).text());
                        $('#PolicyForm span#ICname').html(ui.item.extension);
                    }
                    else {
                        $('#PolicyForm span#ICname').html(ui.item.label+'<br>'+ui.item.extension);
                        $('#carrierModal').modal('toggle');
                    }
                }
                else if(page == "procedure") {
                    $this_id = this.id;
                    $('#'+$this_id).val($("<div>").html(ui.item.id).text());
                    $('#'+$this_id.substring(0, $this_id.length - 2)+'-desc').text($("<div>").html(ui.item.label).text());
                    $('#Amt').val(ui.item.extension);
                    $('#unitAmt').val(ui.item.extension);
                    //1 FOR THE DEFAULT
                    $('#Units').val(1);                 
                }
                else if(page == "diagnosis") {
                    $('#'+this.id).val($("<div>").html(ui.item.id).text());
                    desc = '#display_for_'+this.id;
                    $(desc).html(ui.item.label+'<br>'+ui.item.extension);
                }
                else {
                    $('#'+this.id).val($("<div>").html(ui.item.id).text());
                    desc = '#display_for_'+this.id;
                    $(desc).html(ui.item.label);
                }
            }
        }).focus(function() { 
            $(this).trigger('keydown.autocomplete'); 
        }).data("autocomplete")._renderItem = function(ul, item) {
            if(items == "triple") {
                if(page == "procedure") {
                    return $("<li></li>")
                        .data("item.autocomplete", item)
                        .append("<a>"+item.id+"<br><span style='color:#2c63c2;'>"+item.label+" "+item.extension+"</span></a>")
                        .appendTo(ul);
                }
                else {
                    if (page == 'diagnosis') {
                        return $("<li></li>")
                            .data("item.autocomplete", item)
                            .append("<a>"+item.id+": "+item.label+"<br><span style='color:#2c63c2;'>"+item.extension+"</span></a>")
                            .appendTo(ul);
                    }
                    else {
                        return $("<li></li>")
                            .data("item.autocomplete", item)
                            .append("<a>"+item.label+"<br><span style='color:#2c63c2;'>"+item.extension+"</span></a>")
                            .appendTo(ul);
                    }
                }
            }
            else {
                if(page == 'modifier') {
                    return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a>"+item.id+": <span style='color:#2c63c2;'>"+item.label+"</span></a>")
                    .appendTo(ul);
                }
                else {
                    return $("<li></li>")
                        .data("item.autocomplete", item)
                        .append("<a>"+item.label+"</a>")
                        .appendTo(ul);
                }
            }
        };
    });
});