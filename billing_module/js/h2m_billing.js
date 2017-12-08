var _cacheElementValues = new Array();

//TIMEZONE SETTING
Date.prototype.toDateInputValue = (function() {
    var local = new Date(this);
    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    return local.toJSON().slice(0,10);
}); 

function mask( element ) {
    element = $(element);
    var maskedLine = '';
    var line = element.attr('value');
    
    // check the cache of the input and abord if no change since last treatment
    if (_cacheElementValues[element.attr('id')] != undefined && _cacheElementValues[element.attr('id')] == line) {
        return;
    }
    line = line.replace(/\D/g, ''); // remove all characters != digits
    line = line.substring(0, 10);
    if (line != '') {
        // apply mask
        if (line.length <= 2 ) {
            maskedLine = "(" + line;
        } else if (line.length < 6) {
            maskedLine = line.replace(/^([0-9]{3})([0-9]{0,3})/g, '($1) $2');
        } else {
            // mask : '(XXX) XXX-XXXX'
            maskedLine = line.replace(/^([0-9]{3})([0-9]{3})([0-9]{0,4})/g, '($1) $2-$3');
        }        
    }

    // define cursor position at the end of the input by default
    var pos = maskedLine.length;

    // Change cursor placement if necessary
    if (typeof element[0].selectionStart != 'undefined') {
        var start = element[0].selectionStart;
        var end   = element[0].selectionEnd;
        var insText = element[0].value.substring(start, end);

        // get current cursor placement
        if (insText.length == 0) {
            pos = start;
        } else {
            pos = start + insText.length;
        }
    
        // find how many digits typing since last mask application
        var previousLength = 0;
        if (_cacheElementValues[element.attr('id')] != undefined) {
            previousLength = _cacheElementValues[element.attr('id')].replace(/\s/g, '').length;
        }
        var diff = maskedLine.replace(/\s/g, '').length - previousLength;

        // if sum of new typing digit is > 0 : we change cursor placement
        if (diff > 0) {
            pos += (diff - 1) + Math.round((diff-1)/3);
            if (pos%6 == 0 && maskedLine.length >= pos+1) pos++;
        }
    }

    // update input data & cache
    element.val(maskedLine);
    _cacheElementValues[element.attr('id')] = maskedLine;

    // update cursor placement
    element[0].selectionStart = element[0].selectionEnd = pos;    
}

//FUNCTION AUTOGROW FOR THE NOTES TEXTAREA
function AutoGrowTextArea(textField, maxsize)
{
    var move = true;
    if(maxsize) {
        var max_size = maxsize;
        if(textField.clientHeight >= maxsize) move = false;
    }
    if(move) {
        if (textField.clientHeight < textField.scrollHeight)
        {
            textField.style.height = textField.scrollHeight + "px";
            if (textField.clientHeight < textField.scrollHeight)
            {
                textField.style.height = 
                (textField.scrollHeight * 2 - textField.clientHeight) + "px";
            }
        }
    }
}

$(function() {
    //SET ALL INPUT DATE DEFAULT VALUE AS TODAY, ACCORDING TO THE BROWSER TIME ZONE.
    $("input[type='date']").val(new Date().toDateInputValue());
});
