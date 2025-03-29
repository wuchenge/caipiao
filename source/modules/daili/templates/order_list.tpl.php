<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->daili_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">注单管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo DAILI_PATH?>&c=order&a=init" class="on"><em>注单列表</em></a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo DAILI_PATH?>&c=order&a=search" method="get">
			<input type="hidden" name="m" value="daili">
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
							<select name="search[state]">
								<option value="0" <?php echo $stateoption[0]?>>全部</option>
								<option value="4" <?php echo $stateoption[4]?>>未结算</option>
								<option value="1" <?php echo $stateoption[1]?>>盈利单</option>
								<option value="2" <?php echo $stateoption[2]?>>亏损单</option>
								<option value="3" <?php echo $stateoption[3]?>>已退单</option>
							</select>
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
	  <table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th align="left" width="120">账户(UID)</th>
					<th align="left" width="140">上级代理人(UID)</th>
					<th align="left" width="120">游戏</th>
					<th align="left">单号</th>
					<th align="left" width="100">期数</th>
					<th align="left" width="120">玩法</th>
					<th align="center" width="80">金额</th>
					<th align="center" width="80">结算</th>
					<th align="left" width="150">下单/结算时间</th>
				</tr>
			</thead>
			<tbody>
	    	<?php foreach ($list as $v){ ?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="left"><?php echo $this -> go_user($v['uid'])?></td>
					<td align="left"><?php echo $v['agents'] ? $this -> go_user($v['agent']) : '--'?></td>
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
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div id="pages"><?php echo $pages?></div>
	</div>
	</div>
</body>
</html>