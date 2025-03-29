<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->daili_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">账户信息</h2>
	<div class="content-menu">
		<a href="<?php echo DAILI_PATH?>&c=userset&a=init" <?php echo $this->a_arr['init']?>><em>账户信息</em></a><span>|</span>
		<a href="<?php echo DAILI_PATH?>&c=userset&a=pwset" <?php echo $this->a_arr['pwset']?>><em>修改密码</em></a>
		<?php if($this -> aid < 3){ ?><<!-- span>|</span><a href="<?php echo DAILI_PATH?>&c=userset&a=agent" <?php echo $this->a_arr['agent']?>><em>代理设置</em></a> --><?php } ?>
	</div>
</div>

<div class="content-t">
<?php if(ROUTE_A == 'init'){ ?>
	<table width="100%"  class="table_form">
		<tr>
			<th width="100">账户UID：</th>
			<td><?php echo $user['uid']?></td>
		</tr>
		<tr>
			<th width="100">用户名：</th>
			<td><?php echo $user['username']?></td>
		</tr>
		<tr>
			<th width="100">代理等级：</th>
			<td><?php echo $this -> agent[$user['aid']]?></td>
		</tr>
		<tr>
			<th width="100">金额：</th>
			<td><?php echo $user['money']?></td>
		</tr>
		<tr>
			<th width="100">积分：</th>
			<td><?php echo $user['credit']?></td>
		</tr>
		<tr>
			<th width="100">最后登录时间：</th>
			<td><?php echo format::date($user['logintime'], 1)?></td>
		</tr>
	</table>

<?php }elseif(ROUTE_A == 'pwset'){ ?>
	<form action="<?php echo DAILI_PATH?>&c=userset&a=pwset" method="post" id="myform">
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
<?php }elseif(ROUTE_A == 'agent'){ ?>
	<form enctype="multipart/form-data" action="<?php echo DAILI_PATH?>&c=userset&a=agent" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th width="100"></th>
				<td><?php echo $config['wxewm'] ? '<img src="uppic/ewm/'.$config['wxewm'].'" width="200" height="200" />' : ''?></td>
			</tr>
			<tr>
				<th width="100">微信收款二维码：</th>
				<td>
					<input type="file" id="wxfile" name="wxfile" accept="image/*" />
					<span>该信息将展示在直属会员或代理支付页面，建议二维码图片尺寸：200PX * 200PX</span>
				</td>
			</tr>
			<tr>
				<th width="100"></th>
				<td><?php echo $config['aliewm'] ? '<img src="uppic/ewm/'.$config['aliewm'].'" width="200" height="200" />' : ''?></td>
			</tr>
			<tr>
				<th width="100">数字货币收款二维码：</th>
				<td>
					<input type="file" id="alifile" name="alifile" accept="image/*" />
					<span>该信息将展示在直属会员或代理支付页面，建议二维码图片尺寸：200PX * 200PX</span>
				</td>
			</tr>
			<tr>
				<th>收款银行：</th>
				<td>
					<textarea class="input-text" name="config[card]" style="width: 300px;height: 60px;"><?php echo $config['card'];?></textarea>
					<p>该信息将展示在直属会员或代理支付页面，请完整填写银行名称、卡号和姓名信息</p>
				</td>
			</tr>
			<tr>
				<th>支付备注：</th>
				<td>
					<input class="input-text" type="text" name="config[remark]" value="<?php echo $config['remark']?>" style="width: 200px;" />
					<span>该信息将展示在直属会员或代理支付页面，可填写联系方式或其他备注信息</span>
				</td>
			</tr>
			<tr>
				<th>代理公告：</th>
				<td>
					<input class="input-text" type="text" name="config[ann]" value="<?php echo $config['ann']?>" style="width: 500px;" />
					<span>该信息将展示在首页，仅直属会员或代理可见</span>
				</td>
			</tr>
			<!--<tr>
				<th>提现佣金：</th>
				<td>
					<input class="input-text" type="text" name="config[cash]" value="<?php echo $config['cash']?>" style="width: 200px;" />
					<span>直属会员或代理提现自动抽取的佣金比例，可填写百分比如：5% 即按照提现金额百分之5计算，或直接填写每笔订单的手续费</span>
				</td>
			</tr>-->
			<tr>
				<th>游戏开关：</th>
				<td>
					<?php echo form::checkbox($gamearr, $config['gameid'], 'name="config[gameid][]"', '', 0, true)?>
					<p>选择需要开启的游戏，全选或者全不选代表全部开启，仅直属会员或代理生效</p>
				</td>
			</tr>
		</table>
		<div class="mt20"></div>
		<input type="submit" class="button" name="dosubmit" value=" 提 交 " />
	</form>
<?php } ?>
</div>
</body>
</html>