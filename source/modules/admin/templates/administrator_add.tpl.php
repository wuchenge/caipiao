<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">管理员管理</h2>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=administrator&a=init"><em>管理员列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=administrator&a=add" class="on"><em>添加管理员</em></a>
	</div>
</div>
<div class="content-t">
	<form action="<?php echo ADMIN_PATH?>&c=administrator&a=add" method="post" id="myform">
		<table width="100%" class="table_form">
		  <tr>
		    <th>用户名：</th>
		    <td><input class="input-text" type="text" name="username" id="username" /></td>
		  </tr>
		  <tr>
		    <th>密码：</th>
		    <td><input class="input-text" type="password" name="password" id="password" /></td>
		  </tr>
		  <tr>
		    <th>手机号：</th>
		    <td><input class="input-text" type="text" name="mobile" id="mobile" /></td>
		  </tr>
		  <tr>
		    <th>管理类别：</th>
		    <td>
		    	<label><input type="radio" name="issuper" value="0" checked="checked" />信息管理员</label>
		    	<?php if ($super == 1) { ?>
		    	<label><input type="radio" name="issuper" value="1" />超级管理</label>
		    	<?php } ?>
				<span class="label">信息管理员可管理：账户、注单、充值、提现</span>
		    </td>
		  </tr>
		  <tr>
		    <th>关联Google验证码：</th>
		    <td>
		      <div style='color:red;padding:10px 0;'>扫码进行绑定，或输入Google码进行绑定</div>
		      <div style='padding:10px;'><img src='<?php echo $qrcode;?>'/></div>
		        <div  style='padding:10px 0;'>Google码：<?php echo $google_secret;?></div>
		    </td>
		  </tr>
		</table>
		<div class="mt20"></div>
		<input type="hidden" name='google_secret' value='<?php echo $google_secret;?>' />
		<input type="submit" class="button" id="dosubmit" name="dosubmit" value=" 提 交 " />
	</form>
</div>
<script type="text/javascript">
<!--
$(function(){
	var Vform = $("#myform").Validform();
	Vform.config({tiptype:3});
	Vform.addRule([
		{
			ele:'#username',
			datatype:'s3-20',
			ajaxurl:'<?php echo ADMIN_PATH?>&c=administrator&a=ajax_username',
			nullmsg:'请输入用户名',
			errormsg:'用户名限制在3-20字符之间'
		},
		{
			ele:'#password',
			datatype:'*6-20',
			nullmsg:'请输入密码',
			errormsg:'密码限制在6-20字符之间'
		}
	]);

})
//-->
</script>
</body>
</html>