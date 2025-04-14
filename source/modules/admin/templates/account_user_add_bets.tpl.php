<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<style type="text/css">
.content-t .data_box {
	position: relative;
}

.content-t .data_box a {
	position: absolute;
	left: 720px;
}
.content-t .data_box .df_box {
	margin-top: 10px;
}

</style>
<div class="subnav">
	<h2 class="title-1">今日下注流水</h2>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=account&a=search&search[uid]=<?php echo $uid; ?>"><em>流水列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=account&a=addBet&uid=<?php echo $uid; ?>" class="on"><em>添加流水</em></a>
	</div>
</div>
<div class="content-t">
	<form action="<?php echo ADMIN_PATH?>&c=account&a=addBet&uid=<?php echo $uid; ?>" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th>今日总下注流水:</th>
				<td>
					<?php echo $total_bet; ?>
				</td>
			</tr>
			<tr>
				<th>额外流水:</th>
				<td>
					<?php echo $extra; ?>
				</td>
			</tr>
			<tr>
				<th>增加流水: </th>
				<td>
					<input class="input-text" type="text" name="extra" value="" />
					<span>正数为增加，负数为减少</span>
				</td>
			</tr>
			
		</table>
		<div class="mt20"></div>
		<input type="submit" class="button" name="dosubmit" value=" 提 交 " />
	</form>
</div>

</body>
</html>