$(document).ready(function(){

    var mchat2 = new Mchat();
    
    setInterval(function(){
       mchat2.getMsgs();
    },2000);    
 }); 


var Mchat = function() {
    var self = this;
    var form = null;
    var form_act = null;
    var msgs_el = null;
    var msgs_block_h = null;
    var text = null;
    var nick_el = null;
    var time_el = null;
    var token = null;

    var send = function(data, mchat) {
        $.ajax({
            type: "POST",
            url: form_act,
            data: data+"&mchat="+mchat,
            dataType: "JSON",
            success: function(msg){
                status = parseInt(msg.status);
                if (status == 2) {
                    alert (msg.msg);
                } else if (status == 0) {
                    $.each(msg.msgs_del, function(i, val){
                        delMsg(val);
                    });  

                    $.each(msg.msgs, function(i, val){
                        addMsg(val);
                    });  

                    $.each(msg.nick_del, function(i, val){
                        //alert(val);
                        nickDel(val);
                    });  

                    changeNick(msg.nick);
                    time_el.html(msg.time);
                }

                if (mchat == "msg") {
                    text.val('');
                }
                detHeight();
            },
            error: function (msg) {
            },
            beforeSend: function() {
                if (mchat == 'msg') {
                    form.trigger("start.search")
                }
            },
            complete: function() {
                if (mchat == 'msg') {
                    form.trigger("finish.search")
                }
            }
        });
    };

    var detHeight = function() {
        msgs_el.height('auto');
        var msgs_h = msgs_el.height();
        //alert(msgs_h+ ' : ' +msgs_block_h);
        if (msgs_h >= msgs_block_h) {
            msgs_el.height(msgs_block_h);
            var dest_f = msgs_el.find(".mch-msg:first").offset().top;
            var dest_s = msgs_el.find(".mch-msg:last").offset().top;
            dest_f = Math.abs(dest_f);
            dest_s = Math.abs(dest_s);

            dest = dest_f + dest_s;
            
            msgs_el.scrollTop(dest);
        }
        //else msgs_el.height('auto');
    };

    //var getMsgs = function() {
    self.getMsgs = function() {
        var mch_nick = nick_el.val();
        var msgs = [];
        form.find(".mch-msg").each(function(index, element){
            msgs.push($(element).data('id'));
        });
        msgs_id = msgs.join(',');   
        //if (msgs_id=='') msgs_id='-1'; 
        send("msgsid="+msgs_id+"&mch_nick="+mch_nick+"&mch_token="+token, "list");
    };

    var addMsg = function(msg)  {
        msgs_el.append('<div class="mch-msg" data-id="'+msg.id+'"><div class="mch-date">'+msg.date+'</div><div><a href="javascript:void();" class="mch-nick" data-id="'+msg.id_nick+'">'+msg.nick+'</a></div><div class="mch-text">'+msg.textmsg+'</div></div>');
    };

    var delMsg = function(id)  {
        form.find('.mch-msg[data-id="'+id+'"]').remove();
    };

    var nickDel = function(id)  {
        form.find('.mch-nick[data-id="'+id+'"]').html('Deleted');
    };

    var changeNick = function(nick) {
        var mch_nick = nick_el.val();
        if (mch_nick != nick) {
            var nick_el_vis = form.find(".mch-nick-vis");        
            nick_el.val(nick);
            nick_el_vis.html(nick);
        }
    };

    self.getForm = function() {
        return form;
    };

    var constructor = function() {
        form = $('#mch-form');
        form_act = form.attr('action');
        msgs_block_h = form.find('.mch-msgs-block').height();
        msgs_el = form.find('.mch-msgs');
        text = form.find(".mch-text");        
        time_el = form.find(".mch-curr-time");        
        nick_el = form.find("input[name='mch_nick']");        
        token = form.find("input[name='mch_token']").val();        

        detHeight();
        mch_ajsend(form);
    };
    constructor();

    form.submit(function(e) {
        send(form.serialize(), "msg");
        e.preventDefault();  
    });
    
    $("#mch-send").live("click", function(e){
        send(form.serialize(), "msg");
    });

    $("#mch-refresh").live("click", function(e){
        getMsgs();
    });
}

function mch_ajsend(form) {
    ajload = form.find(".mch-ajload");
    form.on("start.search", function() {
       ajload.show();
    })
    form.on("finish.search", function() {
        ajload.hide();       
    })
}

