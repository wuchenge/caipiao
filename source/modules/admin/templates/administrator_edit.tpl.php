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
	<form action="<?php echo ADMIN_PATH?>&c=administrator&a=edit&id=<?php echo $id?>" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th >用户名：</th>
				<td><?php echo $data['username']?></td>
			</tr>
			<tr>
				<th>密码：</th>
				<td>
					<input class="input-text" type="password" name="password" value="" id="password" />
					<span class="Validform_checktip">请输入新密码，不修改请留空</span>
				</td>
			</tr>
			<tr>
				<th>手机号：</th>
				<td><input class="input-text" type="text" name="mobile" value="<?php echo $data['mobile']?>" id="mobile" /></td>
			</tr>
		  <tr>
		    <th>管理类别：</th>
		    <td>
				<label><input type="radio" name="issuper" value="0" <?php echo $checked[0]?> />信息管理员</label>
				<?php if ($super == 1) { ?>
				<label><input type="radio" name="issuper" value="1" <?php echo $checked[1]?> />超级管理</label>
				<?php } ?>
				<span class="label">信息管理员可管理：账户、注单、充值、提现</span>
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
			datatype:'*6-20',
			ignore:'ignore',
			errormsg:'密码限制在6-20字符之间'
		}
	]);

})
//-->
</script>
</body>
</html>