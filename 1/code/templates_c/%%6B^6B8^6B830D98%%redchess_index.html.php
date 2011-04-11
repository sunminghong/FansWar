<?php /* Smarty version 2.6.26, created on 2010-07-17 12:20:17
         compiled from redchess_index.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.html", 'smarty_include_vars' => array('ctl' => 'redchess','act' => 'sync')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p><?php echo $this->_tpl_vars['tpl_title']; ?>
导航</p>

<p>内容</p>
<p>
<b><?php echo $this->_tpl_vars['userinfo']['uid']; ?>
,<?php echo $this->_tpl_vars['userinfo']['name']; ?>
</b>,赢<b><?php echo $this->_tpl_vars['redinfo']['wins']; ?>
</b>场，输<b><?php echo $this->_tpl_vars['redinfo']['losts']; ?>
</b>，积分<b><?php echo $this->_tpl_vars['redinfo']['score']; ?>
</b><br/>
</p>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>