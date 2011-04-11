<?php /* Smarty version 2.6.26, created on 2010-07-17 12:20:44
         compiled from my_index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('ctl' => 'redchess','act' => 'index')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--发布区-->
<div class="d_write">
	<div class="d_wordNumBg" id="write_info">你还可以输入<span>140</span>字</div> 
	<div class="d_inputmsg"><textarea name="msg" id="msg"></textarea></div>
    <div class="d_toolers">
        <span class="pic"><a href="#">图片</a> 

        </span> 
        <span class="tit" id="write_video">
        	<a href="javascript:void(0);" >多媒体</a> 
        </span>
        <span class="tit" id="write_topic"> 
        	<a href="javascript:void(0);" >话题</a> 
        </span> 
    </div> 
    <div class="d_btn"><!-- 不可点状态<div class="postBtnBg bgColorA_No"> --> 
        <a href="javascript:void(0);" id="write_submit"> 发 布 </a> 
    </div> 
    
    <!--图片上传用-->
    <form target="Upfiler_file_iframe" action="http://picupload.t.sina.com.cn/interface/pic_upload.php?marks=1&amp;markstr=t.sina.com.cn/5d13&amp;s=rdxt&amp;app=miniblog&amp;cb=http://t.sina.com.cn/upimgback.html" id="write_image_form" enctype="multipart/form-data" method="POST" style="display:none;"> 
    	<input type="file" name="pic1" id="write_file" /> 
    </form>
    <iframe name="Upfiler_file_iframe" style="display:none;width:0px;height:0px;" src="about:blank"></iframe>    
    
    <div style="display:none;" class="layerPicBg" id="write_imgpreview">
        
        <table class="fb_img"> 
            <tr> 
                <td> 
                </td> 
                <td class="j_bg"> 
                </td> 
                <td> 
                </td> 
            </tr> 
            <tr> 
                <td class="t_l"> 
                </td> 
                <td class="t_c"> 
                </td> 
                <td class="t_r"> 
                </td> 
            </tr> 
            <tr> 
                <td class="c_l"> 
                </td> 
                <td id="write_preimage" class="c_c"> 
                    <img src="http://static14.photo.sina.com.cn/small/4ba9e0b3t790489c3987d&amp;690"/> 
                </td> 
                <td class="c_r"> 
                </td> 
            </tr> 
            <tr> 
                <td class="b_l"> 
                </td> 
                <td class="b_c"> 
                </td> 
                <td class="b_r"> 
                </td> 
            </tr> 
        </table> 
    </div> 
     <!--/图片上传用-->
</div> 
<!--/发布区 --> 
<div class="cl"> &nbsp; </div>
<!--帐号标签切换区-->
<div class="d_tab">
    <ul>
    <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['keyid'] => $this->_tpl_vars['api']):
?>
        <li onclick="switchTab('<?php echo $this->_tpl_vars['keyid']; ?>
');" ondbclick="list('<?php echo $this->_tpl_vars['keyid']; ?>
');" id="tab_<?php echo $this->_tpl_vars['keyid']; ?>
"><?php echo $this->_tpl_vars['api']['name']; ?>
【<?php echo $this->_tpl_vars['api']['lfromname']; ?>
】</li>
    <?php endforeach; endif; unset($_from); ?>
    </ul>
</div>
<!--/帐号标签切换区-->
<div class="cl"> &nbsp; </div>

<!--主工作区-->
<div id="main">
   <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['keyid'] => $this->_tpl_vars['api']):
?>
        <div id="list_<?php echo $this->_tpl_vars['keyid']; ?>
" class="d_st_list"></div>
    <?php endforeach; endif; unset($_from); ?>
    
    <!--
    
    <div class="d_st_list" id="list_0">
    <div class="d_message">			
        <div class="content">			
            <span class="author"><a href="#">江南都市报</a></span>瞧，北航毕业生热闹的"床单展"			
        <br/>【<a target="_blank" href="http://ss14.sinaimg.cn/orignal/67784e22g8a4fb58c15dd&690">查原图片</a>】
        </div>
			
        <div class="other">				
            <div class="d_retime">				
            .Thu Jul 01 17:01:13 +0800 2010 发自<a href="http://t.sina.com.cn/" rel="nofollow">新浪微博</a>页	
            </div>				
            <div class="d_btn">				 
            <a href="#">对话</a> | <a href="#">转播</a> | <a href="#">★</a> | <a href="#d_top">Top</a>    				
            </div>			
        </div>			
        <div class="cl"> &nbsp; </div>		
    </div>


   	<div class="d_message">
        <div class="content">
            <span class="author">沪江网</span>:#海报学英语#This is not a love story. It's a story about love. 和莎莫的500天（500 Days of Summer），不是爱情故事，而是跟爱情有关的故事。片名也译做恋夏500日。夏天再火热，也只能等到秋天才有收获。
            <br/>【<a href="#">查原图片</a>】
        </div>
        <div class="d_comments">
            <div class="comm_content">
                <span class="author">沪江网</span>:#海报学英语#This is not a love story. It's a story about love. 和莎莫的500天（500 Days of Summer），不是爱情故事，而是跟爱情有关的故事。片名也译做恋夏500日。夏天再火热，也只能等到秋天才有收获。
            <br>【<a href="#">查原图片</a>】&nbsp;&nbsp;&nbsp; 22分钟前 发自网页
            </div>
            <div class="comm_content">
                <span class="author">沪江网</span>:#海报学英语#This is not a love story. It's a story about love. 和莎莫的500天（500 Days of Summer），不是爱情故事，而是跟爱情有关的故事。片名也译做恋夏500日。夏天再火热，也只能等到秋天才有收获。
            <br>【<a href="#">查原图片</a>】&nbsp;&nbsp;&nbsp; 22分钟前 发自网页
            </div>
        </div>
        <div class="other">
            <div class="d_retime">
            .22分钟前 发自网页
            </div>
            <div class="d_btn">
             <a href="#">对话</a> | <a href="#">转播</a> | <a href="#">★</a> | <a href="#d_top">Top</a>    
            </div>
        </div>
        <div class="cl"> &nbsp; </div>
    </div>
    
    
    
   	<div class="d_message">
        <div class="content">
            <span class="author">沪江网</span>:#海报学英语#This is not a love story. It's a story about love. 和莎莫的500天（500 Days of Summer），不是爱情故事，而是跟爱情有关的故事。片名也译做恋夏500日。夏天再火热，也只能等到秋天才有收获。
            <br/>【<a href="#">查原图片</a>】
        </div>
        <div class="d_comments">
            <div class="comm_content">
                <span class="author">沪江网</span>:#海报学英语#This is not a love story. It's a story about love. 和莎莫的500天（500 Days of Summer），不是爱情故事，而是跟爱情有关的故事。片名也译做恋夏500日。夏天再火热，也只能等到秋天才有收获。
            <br>【<a href="#">查原图片</a>】&nbsp;&nbsp;&nbsp; 22分钟前 发自网页
            </div>
            <div class="comm_content">
                <span class="author">沪江网</span>:#海报学英语#This is not a love story. It's a story about love. 和莎莫的500天（500 Days of Summer），不是爱情故事，而是跟爱情有关的故事。片名也译做恋夏500日。夏天再火热，也只能等到秋天才有收获。
            <br>【<a href="#">查原图片</a>】&nbsp;&nbsp;&nbsp; 22分钟前 发自网页
            </div>
        </div>
        <div class="other">
            <div class="d_retime">
            .22分钟前 发自网页
            </div>
            <div class="d_btn">
             <a href="#">对话</a> | <a href="#">转播</a> | <a href="#">★</a> | <a href="#d_top">Top</a>   
            </div>
        </div>
        <div class="cl"> &nbsp; </div>
    </div>
    
    </div>
    -->
</div>
<!--/主工作区-->

<script>
var islist=[];
var lastuid=null;
function $(id) {return document.getElementById(id);}

function switchTab(uid){
	if (lastuid) {
		$('tab_'+lastuid).className="";
		jq('#list_'+lastuid).hide();
	}
	if(jq('#list_'+uid).html()<10)
		list(uid);
	else
		jq('#list_'+uid).show();
		
	lastuid=uid;
	 $('tab_'+uid).className="sel";	
}

function list(uid){
	if(islist[uid])return;
	islist[uid]=true;
	jq.get("?c=my&a=home_timeline&kuid="+uid,function(res){
		var ph=[];
		eval('res='+res);
		var rl=res.length;
		for(var i=0;i<rl;i++){
			var st=res[i];
			ph.push('<div class="d_message">\
			<div class="content">\
				<span class="author"><a href="#">'+ st.user.screen_name +'</a></span>:');
				
			ph.push(st.text);
			
			if (st.thumbnail_pic)
				ph.push('			<br/>【<a target="_blank" href="'+st.original_pic+'">查原图片</a>】');
				
			ph.push('</div>');
			
			if (st.retweeted_status){
				var ret=st.retweeted_status;
				ph.push('\
			<div class="d_comments">\
				<div class="comm_content">\
					<span class="author"><a href="#">'+ret.user.screen_name+'</a></span>:'+ret.text+'\
				<br>');
				if (ret.thumbnail_pic)
				ph.push('			【<a target="_blank" href="'+ret.original_pic+'">查原图片</a>】&nbsp;&nbsp;&nbsp;');
				ph.push(ret.created_at+' 发自'+ret.source+'\
				</div>\
				</div>');
			}
				
			ph.push('			<div class="other">\
				<div class="d_retime">\
				.'+st.created_at+' 发自'+st.source+'页\
				</div>\
				<div class="d_btn">\
				 <a href="#">对话</a> | <a href="#">转播</a> | <a href="#">★</a> | <a href="#d_top">Top</a>    \
				</div>\
			</div>\
			<div class="cl"> &nbsp; </div>\
		</div>');
		}
		//alert(ph.join(''));
		jq('#list_'+uid).html(ph.join('')).show();
		islist[uid]=false;
	});	
}

jq(document).ready(function(){
	var uid=jq("input[name='kuid']")[0].value;
	if(uid) {
		switchTab(uid);
		list(uid);
	}
});
</script>
</body>
</html>