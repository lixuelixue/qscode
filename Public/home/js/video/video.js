
var vlc,lay,ptz,del;
var arr = new Array(4);
var id;
// 覆盖层方法
function gain_id(v){
    id = v;
    var delId = 'del'+v;
    var ptzId = 'ptz'+v;
    var objId = 'obj'+v;
    var layId = 'vidLayer'+v;
     del = document.getElementById(delId);
     ptz = document.getElementById(ptzId)
     vlc = document.getElementById(objId);
     lay = document.getElementById(layId);
     lay.style.outline = '2px solid yellow';
}

// 选取rtsp方法
function changeRtsp(r){
    ids = id-1;
    arr[ids] = r;
    var stream;
    if(r=='dahua'){
        stream = vlc.playlist.add('rtsp://admin:admin@192.168.1.108:554/cam/realmonitor?channel=1&subtype=0&unicast=true&proto=Onvif');
     }
    else{
        stream = vlc.playlist.add('rtsp://admin:admin123@192.168.1.64:554/Streaming/Channels/101?transportmode=unicast&profile=Profile_1');
     }
     lay.style.display = "none";
    //   lay.style.outline = '2px solid yellow';
     del.style.display = 'block';
     ptz.style.display = 'block';
     vlc.playlist.playItem(stream);
     vlc.playlist.play();
}


// 视频关闭
function closeVideo(v1){

         gain_id(v1);
        lay.style.display = 'block';
        del.style.display = 'none';
        ptz.style.display = 'none';
        lay.style.border = '0px solid red';
        vlc.playlist.stop();
        // vlc.playlist.items.clear();
    }