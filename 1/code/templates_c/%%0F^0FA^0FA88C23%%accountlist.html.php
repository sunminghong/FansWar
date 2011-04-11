<?php /* Smarty version 2.6.26, created on 2010-07-17 12:19:44
         compiled from lbi%5Caccountlist.html */ ?>
<!--帐列表区开始-->
<div id="a_list">
	<ul>
    <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['keyid'] => $this->_tpl_vars['api']):
?>
        <li><label><input name="kuid" type="checkbox" checked value="<?php echo $this->_tpl_vars['keyid']; ?>
"/><a href="?c=<?php echo $this->_tpl_vars['ctl']; ?>
&a=<?php echo $this->_tpl_vars['act']; ?>
&kuid=<?php echo $this->_tpl_vars['keyid']; ?>
" target="_blank"><?php echo $this->_tpl_vars['api']['name']; ?>
</a>【<?php echo $this->_tpl_vars['api']['lfromname']; ?>
】</label></li>
    <?php endforeach; endif; unset($_from); ?>
<li><a href="?c=account&a=tologin&lfrom=tsina">新浪登录</a></li>
    </ul>
</div>
<!--帐列表区结束-->