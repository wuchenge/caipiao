<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">充值管理</h2>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=pay&a=init"><em>充值列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=pay&a=add" class="on"><em>人工充值</em></a>
	</div>
</div>
<div class="content-t">
	<form action="<?php echo ADMIN_PATH?>&c=pay&a=add" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th>账户UID：</th>
				<td>
					<input class="input-text" type="text" name="uid" id="uid" value="<?php echo $uid?>"/>
				</td>
			</tr>
			<tr>
				<th>充值金额：</th>
				<td>
					<input class="input-text" type="text" name="money" id="money" />
					<span>退单、扣款可输入负数</span>
				</td>
			</tr>
			<tr>
				<th>充值类型：</th>
				<td>
					<label><input type="radio" name="type" value="1" checked="checked">赠送</label>
					<label><input type="radio" name="type" value="2">充值</label>
				</td>
			</tr>
			<tr>
				<th>备注：</th>
				<td>
					<input class="input-text" type="text" name="comment" id="comment" style="width: 200px;" />
				</td>
			</tr>
			<tr>
				<th class="red">注意：</th>
				<td>如对其他一级代理或二级代理的下属账户充值，该充值操作将不会对上级代理进行划账！</td>
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
			ele:'#uid',
			datatype:'n',
			ajaxurl:'<?php echo ADMIN_PATH?>&c=pay&a=ajax_uid',
			nullmsg:'账户UID',
			errormsg:'账户UID为数字'
		},
		{
			ele:'#money',
			datatype:'rmb2',
			nullmsg:'请输入充值金额',
			errormsg:'请输入正确的货币格式'
		}
	]);
	$('#uid').focus();//处理需要即时验证的表单需要点击后才能提交的BUG
})
//-->
</script>
</body>
</html>