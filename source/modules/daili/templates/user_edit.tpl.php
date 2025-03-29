<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->daili_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">账户管理</h2>
	<div class="content-menu">
		<a href="<?php echo DAILI_PATH?>&c=user&a=init"><em>账户列表</em></a><span>|</span>
		<a href="<?php echo DAILI_PATH?>&c=user&a=add" class="on"><em>账户注册</em></a>
	</div>
</div>
<div class="content-t">
	<form action="<?php echo DAILI_PATH?>&c=user&a=edit&uid=<?php echo $uid?>" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th>用户名：</th>
				<td><?php echo $data['username']?></td>
			</tr>
			<tr>
				<th>禁用：</th>
				<td>
					<label for="lock_1"><input type="radio" id="lock_1" name="lock" value="1" <?php if($data['lock'] == 1) echo 'checked="checked"';?> />是</label>
					<label for="lock_2"><input type="radio" id="lock_2" name="lock" value="0" <?php if(!$data['lock']) echo 'checked="checked"';?> />否</label>
				</td>
			</tr>
			<tr>
				<th>新密码：</th>
				<td>
					<input class="input-text" type="text" name="password" value="" id="password" />
					<span>密码限制为6-20个字符，不修改请留空</span>
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