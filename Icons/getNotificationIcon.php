<script>
var h2m_notifications_definitions = {
    NEWREF: {icon: 'icon-share-alt', color: '#54BC00'},
    REFCNG: {icon: 'icon-share-alt', color: '#357800'},
    NEWMES: {icon: 'icon-envelope-alt', color: '#FFC23B'},
    REPUPL: {icon: 'icon-arrow-up', color: '#22AEFF'},
    SUMEDT: {icon: 'icon-pencil', color: '#6B02C0'},
    NEWPRB: {icon: 'icon-signal', color: '#C900B3'},
    PRBALR: {icon: 'icon-exclamation', color: '#89150B'},
    NEWAPP: {icon: 'icon-calendar-empty', color: '#00C9AB'},
    APPCNL: {icon: 'icon-calendar-empty', color: '#E12313'},
    REVREQ: {icon: 'icon-folder-open', color: '#555'},
    SNDREQ: {icon: 'icon-briefcase', color: '#000'},
};
</script>

<style> 
    .notification_button{ 
        width: 15%; 
        height: 40px; 
        float: right; 
        outline: none; 
        border: 0px solid #FFF; 
        background-color: #BABABA; 
        color: #FFF; 
        font-size: 16px;
    }
    .notification_button:hover{
        background-color:  #AAA; 
        cursor: pointer; 
    } 
    .notification_row{ 
        width: 98%; 
        height: 40px; 
        background-color: #FBFBFB; 
        border-radius: 5px; 
        border: 1px solid #E8E8E8; 
        margin: auto; 
        margin-bottom: 7px; 
        margin-top: 7px; 
        overflow: hidden; 
        font-family: Helvetica, sand-serif; 
    } 
    .notification_row_info{
        background-color:  #FBFBFB; 
        cursor: default; 
    } 
    .notification_row_info:hover{
        background-color:  #F7F7F7; 
        cursor: pointer; 
    } 
    #notifications a{ 
        color: inherit;
        text-decoration: none;
    }
</style>
<div class="notification_row">
    <div id="notification_row_info_'+res[i].id+'_'+res[i].type+'_'+receiver+'_'+sender+'_'+auxilary+'_'+res[i].is_receiver_doctor+'_'+res[i].is_sender_doctor+'" class="notification_row_info">
    <div style="width: 7%; height: 40px; background-color: '+def.color+ color: #FFF; font-size: 22px; position: relative; text-align: center; margin-right: 1%; float: left;">
    <div style="width: 20px; height: 20px; padding-top: 8px; color: #FFF; text-align: center; margin: auto;">
    <i class="'+def.icon+'" style="margin-top: 10px;"></i>
    </div>
    </div>
    <div style="width: 68%; height: 40px; float: left; padding: 0px;">
    <div style="width: 100%; height: 22px; color: '+def.color+ font-size: 16px; padding-top: 5px;">
    html += res[i].message
    </div>
    <div style="width: 100%; height: 10px; color: #999; font-size: 10px; padding: 0px; margin-top: -6px;">
    html += moment(new Date(res[i].created)).fromNow();
    </div>
    </div>
    </div>
    <button id="notificationsDismissButton_'+res[i].id+'" class="notification_button">Dismiss</button>
</div>