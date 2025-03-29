<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">账户管理</h2>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=user&a=init"><em>账户列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=user&a=add" class="on"><em>账户注册</em></a>
	</div>
</div>
<div class="content-t">
	<form action="<?php echo ADMIN_PATH?>&c=user&a=add" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th>用户名：</th>
				<td>
					<input class="input-text" type="text" name="username" value="" id="username" style="width: 200px;" />
				</td>
			</tr>
			<tr>
				<th>锁定：</th>
				<td>
					<label for="lock_1"><input type="radio" id="lock_1" name="lock" value="1" />是</label>
					<label for="lock_2"><input type="radio" id="lock_2" name="lock" value="0" checked="checked" />否</label>
					<span class="label">锁定后将禁止登录</span>
				</td>
			</tr>
			<tr>
				<th>密码：</th>
				<td>
					<input class="input-text" type="text" name="password" value="" id="password" />
					<span>密码限制为6-20个字符，留空则为初始密码：123456</span>
				</td>
			</tr>
			<tr>
				<th>角色类型：</th>
				<td>
					<label for="aid_1"><input type="radio" name="is_robot" value="0" checked="checked" />普通账户</label>
					<label for="aid_2"><input type="radio" name="is_robot" value="1" />机器人</label>
				</td>
			</tr>
			<tr>
				<th>代理：</th>
				<td>
					<label for="aid_1"><input type="radio" id="aid_1" name="aid" value="0" checked="checked" />普通账户</label>
					<label for="aid_2"><input type="radio" id="aid_2" name="aid" value="1" />一级代理</label>
					<label for="aid_3"><input type="radio" id="aid_3" name="aid" value="2" />二级代理</label>
					<label for="aid_4"><input type="radio" id="aid_4" name="aid" value="3" />二级代理(阅)</label>
					<span class="label">如果为二级代理，请在填写上级代理人UID</span>
				</td>
			</tr>
			<tr>
				<th>上级代理人UID：</th>
				<td>
					<input class="input-text" type="text" name="agent" value="" id="agent" />
					<span>指定上级代理人，普通账户或二级代理账户有效，为二级代理时必填</span>
				</td>
			</tr>
		</table>
		<div class="mt20"></div>
		<input type="submit" class="button" name="dosubmit" value=" 提 交 " />
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
			datatype:'*',
			ajaxurl:'<?php echo ADMIN_PATH?>&c=user&a=ajax_username',
			nullmsg:'请输入用户名',
			errormsg:'用户名限制在3-20字符之间'
		},
		{
			ele:'#password',
			datatype:'s6-20',
			ignore:"ignore",
			nullmsg:'请输入密码',
			errormsg:'密码限制为6-20个字符'
		}
	]);
})
//-->
</script>
</body>
</html>