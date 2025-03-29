<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">注单管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=order&a=init" class="on"><em>注单列表</em></a><span>|</span>
		<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=order&a=delall', '确定清理3个月之前的注单数据吗？<br/>注意：清理操作只能清理已经结算的注单，如果数据量不大建议尽可能保留，以备用户查验！');">清理</a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo ADMIN_PATH?>&c=order&a=search" method="get">
			<input type="hidden" name="m" value="admin">
			<input type="hidden" name="c" value="order">
			<input type="hidden" name="a" value="search">
			<table width="100%" cellspacing="0" class="search-form">
				<tbody>
					<tr>
						<td>
						<div class="explain-col">
							单号 <input class="input-text" type="text" name="search[orderid]" style="width:100px;" value="<?php echo $search_orderid?>">
							游戏 <?php echo form::select($gamearr, $search_gameid, 'name="search[gameid]"', '全部', 0)?>
							期数 <input class="input-text" type="text" name="search[qishu]" style="width:100px;" value="<?php echo $search_qishu?>">
							账户UID <input class="input-text" type="text" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
							代理人UID <input class="input-text" type="text" id="agent" name="search[agent]" style="width:50px;" value="<?php echo $search_agent?>">
							<select name="search[state]">
								<option value="0" <?php echo $stateoption[0]?>>全部</option>
								<option value="6" <?php echo $stateoption[6]?>>未结算</option>
								<option value="1" <?php echo $stateoption[1]?>>盈利单</option>
								<option value="2" <?php echo $stateoption[2]?>>亏损单</option>
								<option value="3" <?php echo $stateoption[3]?>>已退单</option>
								<option value="4" <?php echo $stateoption[4]?>>无效单</option>
								<option value="5" <?php echo $stateoption[5]?>>违规单</option>
							</select>
						</div>
						</td>
					</tr>
						<td>
						<div class="explain-col">
							下单时间 <?php echo form::date('search[start_time]',$search_start_time,'1')?>
							到 <?php echo form::date('search[end_time]',$search_end_time,'1')?>
							<input type="submit" value="搜索" class="button" name="dosubmit">
						</div>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
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
			<div class="btn">
				<a href="javascript:;" onClick="selectall('id[]',1);">[全选]</a>/<a href="javascript:;" onClick="selectall('id[]',2);">[反选]</a>/<a href="javascript:;" onClick="selectall('id[]',3);">[取消]</a>
				<input type="button" class="button" value="选中设为无效" onClick="$('#route_a').val('invalid');showwindow('myform','确定这些注单无效吗？<br/>注意：标注为无效注单将自动清退该注单所盈利或亏损金额，并返还下注金额，如未结算将标注为退单！',3)" />
				<input type="button" class="button" value="选中设为违规" onClick="$('#route_a').val('against');showwindow('myform','确定这些注单违规吗？<br/>注意：标注为违规注单将自动清退该注单所盈利金额！下注金额和亏损金额不退！',3)" />
			</div>
		</form>
		<div id="pages"><?php echo $pages?></div>
	</div>
	</div>
</body>
</html>