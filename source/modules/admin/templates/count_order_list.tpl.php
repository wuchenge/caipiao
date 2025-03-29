<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="content-t">
	<div class="table-list">
		<form name="myform" id="myform" action="<?php echo ADMIN_PATH?>&c=order" method="post">
			<input type="hidden" name="type" value="all">
			<input type="hidden" id="route_a" name="a" value="">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
						<th align="left" width="120">账户(UID)</th>
						<th align="left" width="120">游戏</th>
						<th align="left">单号</th>
						<th align="left" width="100">期数</th>
						<th align="left" width="120">玩法</th>
						<th align="center" width="80">金额</th>
						<th align="center" width="80">结算</th>
						<th align="left" width="150">下单/结算时间</th>
						<th align="center" width="120">操作</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($list as $v){ ?>
					<tr id="list_<?php echo $v['id']?>">
						<td align="left"><input type="checkbox" name="id[]" value="<?php echo $v['id']?>"></td>
						<td align="left"><?php echo $this -> go_user($v['uid'])?></td>
						<td align="left"><?php echo $this -> go_gamename($v['gameid'])?></td>
						<td align="left"><?php echo $v['orderid']?></td>
						<td align="left"><?php echo $v['qishu']?></td>
						<td align="left"><?php echo $v['wanfa']?></td>
						<td align="center"><?php echo $v['money']?></td>
						<td align="center">
							<?php
							if ($v['tui'] > 0) {
								echo $this->tuiarr[$v['tui']];
							} else {
								echo $v['account'];
							}
							?>
						</td>
						<td align="left">
							<p><?php echo format::date($v['addtime'], 1)?></p>
							<p><?php echo format::date($v['endtime'], 1)?></p>
						</td>
						<td align="center">
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=order&a=del&id=<?php echo $v['id']?>', '确定删除这条注单记录吗？<br/>注意：不论是否结算，删除操作不会记录任何流水信息和清算金额！为备查验，不建议手工删除！！');">[删除]</a>
							<?php if ($v['tui'] == 0) { ?>
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=order&a=invalid&id=<?php echo $v['id']?>', '确定这条注单无效吗？<br/>注意：标注为无效注单将自动清退该注单所盈利或亏损金额，并返还下注金额，如未结算将标注为退单！');">[无效]</a>
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=order&a=against&id=<?php echo $v['id']?>', '确定这条注单违规吗？<br/>注意：标注为违规注单将自动清退该注单所盈利金额！下注金额和亏损金额不退！');">[违规]</a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<div id="pages"><?php echo $pages?></div>
	</div>
	</div>
</body>
</html>