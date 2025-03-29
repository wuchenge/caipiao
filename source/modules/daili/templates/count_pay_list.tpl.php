<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="content-t">
	<div class="table-list">
	  <table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th align="left" width="120">账户(UID)</th>
					<th align="left" width="140">上级代理人(UID)</th>
					<th align="left" width="60">类型/状态</th>
					<th align="center" width="100">金额</th>
					<th align="left" width="150">单号</th>
					<th align="left">备注</th>
					<th align="left" width="150">创建/支付时间</th>
					<th align="center" width="120">操作</th>
				</tr>
			</thead>
	    <tbody>
	    	<?php foreach ($list as $v){ ?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="left"><?php echo $this -> go_user($v['uid'])?></td>
					<td align="left"><?php echo $this -> go_user($v['agent'])?></td>
					<td align="left" id="state_<?php echo $v['id']?>"><?php echo $this->state[$v['state']]?></td>
					<td align="center"><?php echo $v['money']?></td>
					<td align="left"><?php echo $v['payid']?></td>
					<td align="left" style="table-layout:fixed; word-break: break-all;"><?php echo $v['comment']?></td>
					<td align="left">
						<p><?php echo format::date($v['addtime'], 1)?></p>
						<p id="paytime_<?php echo $v['id']?>"><?php echo format::date($v['paytime'], 1)?></p>
					</td>
					<td align="center">
						<?php if($v['state'] == 0) {?>
						<span id="addto_<?php echo $v['id']?>">
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=pay&a=addto&id=<?php echo $v['id']?>', '确定将这条充值订单到帐吗？<br/>注意：如操作的为代理会员旗下订单将不会对上级代理账户进行划账操作！', 1);">[成功]</a>
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=pay&a=delto&id=<?php echo $v['id']?>', '确定将这条充值订单标记为失败吗？', 1);">[失败]</a>
						</span>
						<?php }?>
						<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=pay&a=del&id=<?php echo $v['id']?>', '确定删除这条充值订单记录吗？<br/>注意：该删除操作不会对已充值的金额造成任何影响！如已充值错误，可在手工充值金额里填写负数进行退单！为方便用户查验，建议保留一定时间！');">[删除]</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div id="pages"><?php echo $pages?></div>
	</div>
	</div>
</body>
</html>