/********UPLOAD SECTION***********/
var DZdialog = $('#DZdialog').dialog({
    autoOpen: false,
    modal: true, 
    width: '630px', 
    minHeight: '630px', 
    open: function() {
    },
    close: function() {
        //$('#thedialog').empty();
    }
});
$('#theDZdialog').load('dropzone.html');

var SHdialog = $('#SHdialog').dialog({
    autoOpen: false,
    modal: true, 
    width: '860px', 
    minHeight: '490px', 
    open: function() {        
        console.log(patient);
    },
    close: function() {
        //$('#thedialog').empty();
    }
});
setTimeout(function() {
$('#theSHdialog').load('share_rep_referral.php?Usuario='+patient, function() {
    $('#SH_spin').hide();
});
}, 1000);

function hasClass(element, cls)
{
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

function allowDrop(ev)
{
    ev.preventDefault();
}   

 function onDrop(ev) 
{
    console.log(ev);
    ev.preventDefault();
    
    var target = null;
    var file_length = 0;
    
    if(typeof ev.new_id == 'undefined') {
        file_length = ev.dataTransfer.files.length;
        if(hasClass(ev.target, 'dropzone'))
        {
            for(var i = 0; i < ev.target.childNodes.length; i++)
            {
                if(ev.target.childNodes[i].className == 'container')
                {
                    target = ev.target.childNodes[i];

                    break;
                }
            }

        }
        else if(hasClass(ev.target, 'container'))
        {
            target = ev.target;
        }
        else if(hasClass(ev.target, 'arrow'))
        {
            for(var i = 0; i < ev.target.parentNode.childNodes.length; i++)
            {
                if(ev.target.parentNode.childNodes[i].className == 'container')
                {
                    target = ev.target.parentNode.childNodes[i];
                    break;
                }
            }
        }
        else if(hasClass(ev.target, 'thumbnail'))
        {
            target = ev.target.parentNode;
        }
        else
        {
            return;
        }
    } else {
        file_length = ev['transfer_file_differently'].length;
        target = document.getElementById(ev.new_id);
        //console.log(target);
    }

    var progress_bar = null;
    for(var i = 0; i < target.parentNode.childNodes.length; i++)
    {
        if(target.parentNode.childNodes[i].className == 'progress_bar')
        {
            progress_bar = target.parentNode.childNodes[i];

            break;
        }
    }

    //console.log(ev);

    if(file_length > 0)
    {
        // dragged a new file
        console.log('Dragging New File');

        var formData = new FormData();
        var up_file_num = parseInt($('#num_of_uploaded_files').text());

        if(typeof ev.new_id == 'undefined') {
            up_file_num += ev.dataTransfer.files.length;
            $('#num_of_uploaded_files').text(up_file_num);       
            
            for(var i = 0; i < ev.dataTransfer.files.length; i++)
            {
                formData.append('files[]', ev.dataTransfer.files[i]);
            }
        } else {
            up_file_num += ev['transfer_file_differently'].length;
            $('#num_of_uploaded_files').text(up_file_num);
            
            for(var i = 0; i < ev['transfer_file_differently'].length; i++)
            {
                formData.append('files[]', ev['transfer_file_differently'][i]);
            }
        }
        
        $('#num_of_files').text(total_file_num + parseInt(up_file_num));

        var classification = target.getAttribute('id');

        formData.append('classification', classification);
        formData.append('patient', patient);


        var xhr = new XMLHttpRequest();

        xhr.open('POST', 'dropzone.php', true);

        xhr.upload.onprogress = function(ev)
        {

            var percentComplete = (ev.loaded / ev.total) * 100;
            console.log('Complete: ' + percentComplete);

            if(percentComplete < 100.0)
            {
                progress_bar.style.display = 'block';
                progress_bar.childNodes[1].style.width = percentComplete.toString() + '%';
            }
        };

        xhr.onload = function()
        {
            progress_bar.style.display = 'none';
            if(xhr.status == 200)
            {
                console.log('Result: ' + xhr.response);

                var reports = JSON.parse(xhr.response), thumbnail = '', reportpath = '', report;

                for(var i = 0; i < reports.length; i++)
                {
                    thumbnail = window.location.protocol + '//' + window.location.host + '/' + reports[i]['thumbnail'];
                    reportpath = window.location.protocol + '//' + window.location.host + '/' + reports[i]['report'];
                    report = document.createElement("div");
                    report.className = 'thumbnail';
                    report.setAttribute('id', reports[i]['id']);
                    report.setAttribute('draggable', 'true');
                    report.setAttribute('ondragstart', 'onDragStart(event)');
                    report.setAttribute('data-reporturl', reportpath);
                    report.style.backgroundImage = 'url(' + thumbnail + ')';

                    target.appendChild(report);


                }

                var current = 5;
                var z = 1;
                var count = 0;
                for(var i = 0; i < target.childNodes.length; i++)
                {
                    if(hasClass(target.childNodes[i], 'thumbnail'))
                    {
                        target.childNodes[i].style.marginTop = current.toString() + 'px';
                        target.childNodes[i].style.zIndex = z.toString();

                        if(count == 0)
                            target.childNodes[i].style.marginLeft = (0).toString() + 'px';
                        else
                            target.childNodes[i].style.marginLeft = (-65).toString() + 'px';

                        z++;
                        current += 5;
                        count++;
                    }
                }

                $(".thumbnail").on('mouseenter', function(e)
                {
                    //if($(this).parent().parent().data('hover') == 1)
                    //{
                        var parent = $(this).parent();
                        var pos = parent.children(".thumbnail").index(this);
                        var count = parent.children().length;

                        $(this).parent().parent().data('pos', pos);
                        $(this).css('z-index', count.toString());

                        var current = count - 1;

                        $(this).css('-webkit-transform', 'initial');

                        for(var i = pos - 1; i >= 0; i--)
                        {
                            var deg = Math.floor((Math.random() * 9) - 4);
                            parent.children('.thumbnail').eq(i).css('z-index', current.toString());
                            parent.children('.thumbnail').eq(i).css('-webkit-transform', 'rotate(' + deg.toString()+ 'deg)');
                            current--;
                        }
                        current = count - 1;
                        for(var i = pos + 1; i < count; i++)
                        {
                            var deg = Math.floor((Math.random() * 9) - 4);
                            parent.children('.thumbnail').eq(i).css('z-index', current.toString());
                            parent.children('.thumbnail').eq(i).css('-webkit-transform', 'rotate(' + deg.toString()+ 'deg)');
                            current--;
                        }
                    //}
                });
            }
        };

        xhr.send(formData);


    }
    else
    {
        // reclassifying report
        console.log('Reclassifying Report');

        var data = ev.dataTransfer.getData("item_id");
        var parent = document.getElementById(data).parentNode;
        target.appendChild(document.getElementById(data));

        var formData = new FormData();
        var classification = target.getAttribute('id');
        var id = data;
        formData.append('classification', classification);
        formData.append('id', id);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'dropzone_save.php', true);
        xhr.send(formData);

        var current = 5;
        count = 0;
        for(var i = 0; i < parent.childNodes.length; i++)
        {
            if(hasClass(parent.childNodes[i], 'thumbnail'))
            {
                parent.childNodes[i].style.marginTop = current.toString() + 'px';

                if(count == 0)
                    parent.childNodes[i].style.marginLeft = (0).toString() + 'px';
                else
                    parent.childNodes[i].style.marginLeft = (-65).toString() + 'px';

                current += 5;
                count++;
            }
        }

        var current = 5;
        var z = 1;
        var count = 0;
        for(var i = 0; i < target.childNodes.length; i++)
        {
            if(hasClass(target.childNodes[i], 'thumbnail'))
            {
                target.childNodes[i].style.marginTop = current.toString() + 'px';
                target.childNodes[i].style.zIndex = z.toString();

                if(count == 0)
                    target.childNodes[i].style.marginLeft = (0).toString() + 'px';
                else
                    target.childNodes[i].style.marginLeft = (-65).toString() + 'px';

                z++;
                current += 5;
                count++;
            }
        }
    }
}



function onDropButton(ev) {
    ev.preventDefault();
    DZdialog.dialog('open');
    var targetId = "61";

    ev['new_id'] = targetId;
    ev['transfer_file_differently'] = ev.dataTransfer.files;
    setTimeout(function() {onDrop(ev)}, 1000);
}

$('.send_referral_upload').click(function(e) {
    e.preventDefault();
    DZdialog.dialog('open');
});

/********UPLOAD SECTION***********/
$('#ref_doc_name').autocomplete({
    minLength: 3,
    delay: 500,
    source: function(request, response) {
        $.ajax({
            type: "POST",
            url: "ref_doc_search.php",
            data: { query: request.term },
            dataType: 'json',
            success: function(data) {   
                response($.map(data, function(obj) {
                    return {
                        id: obj.id,
                        name: obj.name,
                        phone: obj.phone,
                        email: obj.email,
                        speciality: obj.speciality
                    };
                }));
            },
            error: function(error) {
                console.log(error);
            }
        });
    },
    focus: function(event, ui) {
        event.preventDefault();
    },
    select: function(event, ui) {
        $('#ref_doc_name').val(ui.item.name);
        $('#ref_doc_phone').val(ui.item.phone);
        console.log(ui);
        return false;
    }
});

$('#ref_doc_name').data("ui-autocomplete")._renderItem = function(ul, item) {
    var div = $('<div class="list"></div>');
    $("<span>").attr("id",item.id).css('color','#22AEFF').text(item.name).appendTo(div);
    $("<br>").appendTo(div);
    $("<span>").css({color: 'gray', 'font-size': '10px'}).text(item.speciality).appendTo(div);
    $("<br>").appendTo(div);
    $("<span>").css({color: 'darkgray', 'font-size': '8px'}).text(item.email).appendTo(div);
    $("<br>").appendTo(div);
    $("<span>").css({color: 'black', 'font-size': '12px'}).text(item.phone).appendTo(div);
    
    return $("<li>").append(div).appendTo(ul);
}; 

/*$('#ref_doc_name').keyup(function() {
   autoCom.autocomplete('instance'); 
});*/

$('ref_doc_phone').keypress(function(){
    var search_term = $(this).val();
    $.post('ref_doc_search.php' , {qTerm: search_term}, function(data,status) {
        //content here when the ajax call has been successfully done
    });
});
                         

/********SHARING SECTION***********/
$('.send_referral_share').click(function(e) {
    SHdialog.dialog('open');
});


/********SHARING SECTION***********/
