
    function SC(n,v)
{
    var Days = 30; 
    var exp  = new Date();    //new Date("December 31, 9998");
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = n + "="+ escape (v) + ";expires=" + exp.toGMTString();
}
function GC(n)     
{
    var arr = document.cookie.match(new RegExp("(^| )"+n+"=([^;]*)(;|$)"));
     if(arr != null) return unescape(arr[2]); return null;

}
function DC(n)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(n);
    if(cval!=null) document.cookie= n + "="+cval+";expires="+exp.toGMTString();
}
function sS(skin){

if(typeof skin!="string")skin=skin.id;	//  alert(skin);
  document.getElementById("cssfile").setAttribute("href","/templets/mobile/style/"+ skin +".css");   SC( "MyCssSkin",skin);
}

//window.onload=function(){
	  var skin = GC("MyCssSkin");
	  if(!skin)skin="skins_0"
	  sS(skin);//	  };