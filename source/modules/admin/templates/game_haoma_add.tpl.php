<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">游戏开奖号码</h2>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=game&a=init"><em>游戏列表</em></a><span>|</span>
		<a href="javascript:;" class="on"><em>号码补号</em></a>
	</div>
</div>
<div class="content-t">
	<form action="<?php echo ADMIN_PATH?>&c=game&a=haoma_add&id=<?php echo $id?>" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th>游戏ID：</th>
				<td>
					<?php echo $data['gameid']?>
					<span>请核对游戏ID！</span>
				</td>
			</tr>
			<tr>
				<th>游戏期数：</th>
				<td>
				    
					<?php  echo $data['qishu'];?>
					<span>请核对游戏期数！</span>
				</td>
			</tr>
			<tr>
				<th>开奖号码：</th>
				<td>
					<input class="input-text" type="text" name="haoma" value="" id="haoma" style="width: 200px;" />
					<span>请正确填写开奖号码，号码之间用半角逗号“,”分开，补号后无法修改，且未结算的注单可能会在瞬间结算！</span>
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
			ele:'#haoma',
			datatype:'/^[0-9][0-9,#@]*$/',
			nullmsg:'请输入正确的号码',
			errormsg:'号码为数字“,#@”线组成'
		}
	]);
})
//-->
</script>
</body>
</html>