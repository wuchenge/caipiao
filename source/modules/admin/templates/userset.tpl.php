<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">用户信息</h2>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=userset&a=init" <?php echo $this->a_arr['init']?>><em>用户信息</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=userset&a=pwset" <?php echo $this->a_arr['pwset']?>><em>修改密码</em></a>
	</div>
</div>

<div class="content-t">
<?php if(ROUTE_A == 'init'){ ?>
	<table width="100%"  class="table_form">
		<tr>
			<th width="100">用户ID：</th>
			<td><?php echo $userinfo['id']?></td>
		</tr>
		<tr>
			<th width="100">用户名：</th>
			<td><?php echo $userinfo['username']?></td>
		</tr>
		<tr>
			<th width="100">用户类型：</th>
			<td><?php echo $issuperarr[$userinfo['issuper']]?></td>
		</tr>
	</table>

<?php }elseif(ROUTE_A == 'pwset'){ ?>
	<form action="<?php echo ADMIN_PATH?>&c=userset&a=pwset" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th width="100">原密码：</th>
				<td><input class="input-text" type="password" name="password" id="password" value="" /></td>
			</tr>
			<tr>
				<th width="100">新密码：</th>
				<td><input class="input-text" type="password" name="newpassword"  id="newpassword" value="" /></td>
			</tr>
			<tr>
				<th width="100">确认新密码：</th>
				<td><input class="input-text" type="password" name="newpassword2" id="newpassword2" value="" /></td>
			</tr>
		</table>
		<div class="mt20"></div>
		<input type="submit" class="button" name="dosubmit" value=" 提 交 " />
	</form>

<script type="text/javascript">
<!--
$(function(){
	var Vform = $("#myform").Validform();
	Vform.config({tiptype:3});
	Vform.addRule([
		{
			ele:'#password',
			datatype:'*6-16',
			nullmsg:'请输入原密码',
			errormsg:'密码长度为6-20字符'
		},
		{
			ele:'#newpassword',
			datatype:'*6-16',
			nullmsg:'请输入新密码',
			errormsg:'密码长度为6-20字符'
		},
		{
			ele:'#newpassword2',
			datatype:'*',
			recheck:'newpassword',
			nullmsg:'请再次输入新密码',
			errormsg:'两次输入的新密码不一致'
		}
	]);

})
//-->
</script>
<?php } ?>
</div>
</body>
</html>