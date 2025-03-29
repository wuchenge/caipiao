<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">每日统计</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=account&a=day" class="on"><em>每日统计</em></a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo ADMIN_PATH?>&c=account&a=day" method="get">
			<input type="hidden" name="m" value="admin">
			<input type="hidden" name="c" value="account">
			<input type="hidden" name="a" value="day">
			<table width="100%" cellspacing="0" class="search-form">
				<tbody>
					<tr>
						<td>
						<div class="explain-col">
							账户UID <input class="input-text" type="text" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
							<!-- <select name="search[type]">
								<option value="0" <?php echo $typeoption[0]?>>全部</option>
								<option value="5" <?php echo $typeoption[5]?>>充值</option>
								<option value="1" <?php echo $typeoption[1]?>>提现</option>
								<option value="2" <?php echo $typeoption[2]?>>投注</option>
								<option value="3" <?php echo $typeoption[3]?>>盈利</option>
								<option value="4" <?php echo $typeoption[4]?>>退单</option>
							</select> -->
							<input type="submit" value="搜索" class="button" name="dosubmit">
						</div>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<div class="table-list">
			<input type="hidden" name="type" value="all">
			<input type="hidden" name="a" value="del">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th align="center" width="120">日期</th>
						<th align="center" width="150">充值总额</th>
						<th align="center" width="150">提现总额</th>
						<th align="center" width="150">盈亏</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<td align="center">合计</td>
					<td align="center"><?= sprintf("%.2f",$count[0]) ?></td>
					<td align="center"><?= sprintf("%.2f",$count[1]) ?></td>
					<td align="center"><?= sprintf("%.2f",$count[0]+$count[1]) ?></td>
					</tr>
				<?php foreach ($list as $v){ 
					$recharge = isset($data[$v][0])?$data[$v][0]:'0.00';
					$withdraw = isset($data[$v][1])?$data[$v][1]:'0.00';
				?>
					<tr>
					<td align="center"><?= $v ?></td>
					<td align="center"><?= $recharge ?></td>
					<td align="center"><?= $withdraw ?></td>
					<td align="center"><?= sprintf("%.2f",$recharge+$withdraw) ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<!-- <div class="btn">
				<a href="javascript:;" onClick="selectall('id[]',1);">[全选]</a>/<a href="javascript:;" onClick="selectall('id[]',2);">[反选]</a>/<a href="javascript:;" onClick="selectall('id[]',3);">[取消]</a>
				<input type="button" class="button" value="删除选中" onClick="showwindow('myform','确定要批量删除选中的流水记录吗？<br/>注意：流水记录自动生成，为备查验，不建议手工删除！',3)" />
			</div> -->
		<div id="pages"><?php echo $pages?></div>
	</div>
	</div>
</body>
</html>