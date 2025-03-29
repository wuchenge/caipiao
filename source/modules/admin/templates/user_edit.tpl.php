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
	<form action="<?php echo ADMIN_PATH?>&c=user&a=edit&uid=<?php echo $uid?>" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th>用户名：</th>
				<td>
					<?php echo $data['username']?>
				</td>
			</tr>
			<tr>
				<th>昵称：</th>
				<td>
					<input class="input-text" type="text" name="nickname" value="<?php echo $data['nickname']?>" id="nickname" style="width: 200px;" />
				</td>
			</tr>
			<tr>
				<th>新密码：</th>
				<td>
					<input class="input-text" type="text" name="password" value="" id="password" />
					<span>密码限制为6-20个字符，不修改请留空</span>
				</td>
			</tr>
			<tr>
				<th>锁定：</th>
				<td>
					<label for="lock_1"><input type="radio" id="lock_1" name="lock" value="1" <?php if($data['lock'] == 1) echo 'checked="checked"';?> />是</label>
					<label for="lock_2"><input type="radio" id="lock_2" name="lock" value="0" <?php if(!$data['lock']) echo 'checked="checked"';?> />否</label>
					<span class="label">锁定后将禁止登录</span>
				</td>
			</tr>
			<tr>
				<th>冻结金额：</th>
				<td>
					<label for="freezing_1"><input type="radio" id="freezing_1" name="freezing" value="1" <?php if($data['freezing'] == 1) echo 'checked="checked"';?> />是</label>
					<label for="freezing_2"><input type="radio" id="freezing_2" name="freezing" value="0" <?php if(!$data['freezing']) echo 'checked="checked"';?> />否</label>
					<span class="label">冻结后将无法使用</span>
					<input type="test"  name="freezing_money" value="<?php echo $data['freezing_money']?>"  />
				</td>
			</tr>
			<tr>
				<th>Email：</th>
				<td>
					<input class="input-text" type="text" name="email" value="<?php echo $data['email']?>" id="email" />
				</td>
			</tr>
			<tr>
				<th>QQ：</th>
				<td>
					<input class="input-text" type="text" name="qq" value="<?php echo $data['qq']?>" id="qq" />
				</td>
			</tr>
			<tr>
				<th>手机号：</th>
				<td>
					<input class="input-text" type="text" name="mobile" value="<?php echo $data['mobile']?>" id="mobile" />
				</td>
			</tr>
			<tr>
				<th>投注金额限制：</th>
				<td>
					<input class="input-text" type="text" name="send_money" value="<?php echo $data['send_money']?>" id="send_money" />
					<span>填写格式：1-50000，此处设置优先于全局系统设置，留空则遵循全局系统设置</span>
				</td>
			</tr>
			<tr>
				<th class="red">敏感资料：</th>
				<td>以下是敏感资料，请谨慎修改</td>
			</tr>
			<tr>
				<th class="red">姓名：</th>
				<td>
					<input class="input-text" type="text" name="name" value="<?php echo $data['name']?>" id="name" />
				</td>
			</tr>
			<tr>
				<th class="red">银行名称：</th>
				<td>
					<input class="input-text" type="text" name="bank" value="<?php echo $data['bank']?>" id="bank" style="width: 200px;" />
				</td>
			</tr>
			<tr>
				<th class="red">银行账号：</th>
				<td>
					<input class="input-text" type="text" name="card" value="<?php echo $data['card']?>" id="card" style="width: 200px;" />
				</td>
			</tr>
			<tr>
				<th class="red">微信：</th>
				<td>
					<input class="input-text" type="text" name="weixin" value="<?php echo $data['weixin']?>" id="weixin" />
				</td>
			</tr>
			<tr>
				<th class="red">数字货币：</th>
				<td>
					<input class="input-text" type="text" name="alipay" value="<?php echo $data['alipay']?>" id="alipay" />
				</td>
			</tr>
			<tr>
				<th class="red">角色类型：</th>
				<td>
					<label for="aid_1"><input type="radio" name="is_robot" value="0" <?php if($data['is_robot'] == 0) echo 'checked="checked"';?> />普通账户</label>
					<label for="aid_2"><input type="radio" name="is_robot" value="1" <?php if($data['is_robot'] == 1) echo 'checked="checked"';?> />机器人</label>
				</td>
			</tr>
			<tr>
				<th class="red">代理关系：</th>
				<td>
					以下代理关系，在存在下级数据的情况下只能升级，不能降级，否则代理层级关系将错乱
				</td>
			</tr>
			
			<tr>
				<th class="red">代理级别：</th>
				<td>
					<label for="aid_1"><input type="radio" id="aid_1" name="aid" value="0" <?php if($data['aid'] == 0) echo 'checked="checked"';?> />普通账户</label>
					<label for="aid_2"><input type="radio" id="aid_2" name="aid" value="1" <?php if($data['aid'] == 1) echo 'checked="checked"';?> />一级代理</label>
					<label for="aid_3"><input type="radio" id="aid_3" name="aid" value="2" <?php if($data['aid'] == 2) echo 'checked="checked"';?> />二级代理</label>
					<label for="aid_4"><input type="radio" id="aid_4" name="aid" value="3" <?php if($data['aid'] == 3) echo 'checked="checked"';?> />二级代理(阅)</label>
					<span class="label">如果为二级代理，请在填写上级代理人UID</span>
				</td>
			</tr>
			<tr>
				<th class="red">上级代理人UID：</th>
				<td>
					<input class="input-text" type="text" name="agent" value="<?php echo $data['agent']?>" id="agent" />
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
			ele:'#password',
			datatype:'s6-20',
			ignore:"ignore",
			nullmsg:'请输入密码',
			errormsg:'密码限制为6-20个字符'
		}
	]);
	$('#username').focus();//处理需要即时验证的表单需要点击后才能提交的BUG
})
//-->
</script>
</body>
</html>