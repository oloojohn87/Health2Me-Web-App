$(document).ready(function() {

    var waitTime = 0;

    function intervalTrigger() {    
        return window.setInterval(function() {
            waitTime++;
            if(waitTime >= 5 && $('#header').css('opacity') != 0) {
                $('#header, #close').fadeTo('slow', 0);
                $('#left-arrow, #right-arrow').fadeTo('slow', 0.4);          
            }
            //console.log(waitTime);
        }, 1000);
    };
    
    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });
    
    $(this).on('click','#sharestation button#save', function() {
        SHdialog.dialog('close'); 
        console.log(highlighted_files);
    }); 

    var modal = (function(){
        var 
        method = {}, 
        $overlay,
        $modal,
        $header,
        $content,
        $footer,
        $close,
        intervalID;

        // Append the HTML
        $overlay = $('<div id="overlay"></div>');
        $modal = $('<div id="modal"></div>');
        $header = $('<div id="header"><input type="hidden" id="idpin"><span id="category_indicator"></span><span id="page_indicator">1 / 1</span><div id="delete_report"><img id="trashcan" alt="delete" src="../images/trashcan.png" width="20px" height="20px" /></div><div style="float:right; text-align:center;"><label lang="en" for="datepicker">Report Date</label><br><input type="date" id="datepicker"></div></div>');
        $content = $('<div id="content"></div>');
        $close = $('<a id="close" href="#">close</a>');

        $modal.hide();
        $overlay.hide();
        $modal.append($header, $content, $close);

        $(document).ready(function(){
            $('body').append($overlay, $modal);
            $(this).on('click', '.ui-widget-overlay', function() {
                modal.close();
            });
        });

        // Center the modal in the viewport
        method.center = function () {
            var top, left;

            top = Math.max($(window).height() - $modal.outerHeight(), 0) / 2;
            left = Math.max($(window).width() - $modal.outerWidth(), 0) / 2;

            $modal.css({
                top:top + $(window).scrollTop(), 
                left:left + $(window).scrollLeft()
            });
        };


        // Open the modal
        method.open = function (settings) {
            
            $content.empty().append($header, settings.content, $footer);

            $modal.css({
                width: settings.width || 'auto', 
                height: settings.height || 'auto',
                'z-index': 200
            });

            method.center();

            $(window).bind('resize.modal', method.center);
            $modal.show();
            $overlay.show();   
            
            intervalID = intervalTrigger();
            modalEntry = true;
        };

        // Close the modal
        method.close = function () {
            $modal.hide();
            $overlay.hide();
            $('#datepicker').val('');
            $('#idpin').val('');
            $content.empty();
            $(window).unbind('resize.modal');
            window.clearInterval(intervalID);
            modalEntry = false;
        };

        $close.click(function(e){
            e.preventDefault();
            method.close();
        });

        return method;
    }());
    
    $('#theDZdialog').on('click', '.thumbnail', function() {

        var dropzone, wrapper, arrows, container, reports, date_pos, cat_color, index, cat_color_rgba, cat_name = '', catClass = '', idpin = '';

        //EXTRACTING THE BG COLOR AND NAME OF THE CATEGORY
        cat_color = $(this).parent().parent().css('background-color');
        cat_name = $(this).parent().parent().text().trim();
        idpin = $(this).attr('id');

        console.log(cat_name);

        //GETTING THE REPORT INDEX
        index = $(this).index() + 1;

        dropzone = $(this).parent().parent().clone().css({width: '790px', height: '970', display: 'inline-block', padding: '0', border: 'none', background:'none'});

        leftarrow = $('<div></div>').css({'margin-top':'440px', 'margin-right': '-50px', 'z-index': 100, position: 'relative'}).removeClass('arrow').attr('id', 'left-arrow');

        rightarrow = $('<div></div>').css({'margin-top':'440px', 'margin-left': '-45px', 'z-index': 100, position: 'relative'}).removeClass('arrow').attr('id','right-arrow');

        container = dropzone.find('.container').css({width: '740px', height: '960px', display: 'inline-block'});

        reports = dropzone.find('.thumbnail').css({width: '740px', height: '955px', display: 'inline-block', background: 'none'}).each(function() {
            var reportURL = $(this).data('reporturl');
            $(this).empty().append('<object data="'+reportURL+'" type="application/pdf" width="100%" height="100%"></object>')
        }).appendTo(container);  

        dropzone.empty().append(leftarrow, container, rightarrow);

        reports.each(function() {
            if(!$(this).is(':first-child')) $(this).css('margin-left', '-755px');
        });
        modal.open({content: dropzone});

        //UPDATE THE IdPin HIDDEN FIELD OF THE MODAL HEADER
        $('#header #idpin').val(idpin);

        //IMPORTING THE NAME OF THE DROPZONE
        catClass = dropzone.find('i').attr('class');
        $('#category_indicator').html('<i class="'+catClass+'" style="color:whitesmoke; vertical-align: middle;"></i>' + cat_name);      

        //WRITING THE data-pos VALUE TO THE ACTUAL HTML
        dropzone.attr('data-pos', dropzone.data('pos'));

        //APPLYING THE HEADER COLOR FROM THE CATEGORY BACKGROUND COLOR
        if(cat_color.indexOf('a') == -1) cat_color_rgba = cat_color.replace(')', ', 0.5)').replace('rgb','rgba');   

        //console.log(cat_color_rgba);

        $('#header').css('background', cat_color_rgba);      

        //INIT LOAD THE PAGE INDICATOR
        $('#page_indicator').text(index + ' / ' + reports.length);

        idpin = $('#header #idpin').val();
        $('#datepicker').val('');
        $.get('update_date_nDZ.php', {idpin: idpin, user: patient}, function(data, status) {
            if(data == 'not updated') $('#datepicker').val(new Date().toDateInputValue());                               else $('#datepicker').val(data);
        }); 
    });
    
    
    //SHOW THE ARROWS AND HEADER INFO WHEN MOUSE HOVERS
    $(this).on('mousemove click', '#modal', function() {
        waitTime = 0;
        if($('#header').css('opacity') == 0) {
            $('#left-arrow, #right-arrow, #close').fadeTo('slow', 1);
            $('#header').css('opacity', 1).hide().slideDown('slow');
            //animate({opacity: 1}, 1000);
        }
    });

    //UPDATE THE DATE WHEN DATE HAS BEEN CHANGED
    $(this).on('change', '#datepicker', function() {
        var contTipo = $(this).closest('#content').find('.container').attr('id');
        $.get('update_date_nDZ.php', {idpin: $('#header #idpin').val(), tipo: contTipo, fecha: $(this).val(), user: patient}, function(data, status) {
            //update!!
           //console.log(contTipo);
        });
        //console.log(contTipo);
    });

    //DELETE THE REPORT
    $(this).on('click', '#delete_report', function() {
        var idpin = $('#header #idpin').val();
        var currentContainer = $(this).closest('#content').find('.container');
        $.get('../getReportStatus.php?IdPin='+idpin, function(data, status) {
            if(data == 3) {
                swal({
                    title: "Delete", 
                    text: "Are You sure you want to delete this report?", 
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: 'Delete',
                    closeOnConfirm: false
                },
                function() {
                    $.get('../deleteReports.php', {IdPin: idpin, state: data}, function(d, s) {
                        currentContainer.find('#'+idpin).remove();
                        //console.log(currentContainer.children());
                        if(currentContainer.children().length == 0) modal.close();
                        else {
                            $('#left-arrow').trigger('click');
                            $('#right-arrow').trigger('click');       
                        }
                        $('#'+idpin).remove();
                        $('#'+currentContainer.attr('id')).find('.thumbnail:first-child').css('margin-left', '0px');
                        
                        swal('Report deleted', 'The report has been delete.', 'info');
                        
                    });
                });    
            }
            
        });
    }); //END OF DELETE FUNCTION
    
});