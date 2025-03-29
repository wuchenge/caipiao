<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->daili_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">用户统计</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=account&a=init" class="on"><em>用户统计</em></a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo ADMIN_PATH?>&c=account&a=user" method="get">
			<input type="hidden" name="m" value="admin">
			<input type="hidden" name="c" value="account">
			<input type="hidden" name="a" value="user">
			<table width="100%" cellspacing="0" class="search-form">
				<tbody>
					<tr>
						<td>
						<div class="explain-col">
							账户UID <input class="input-text" type="text" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
							<select name="search[type]">
								<option value="0">全部</option>
								<option value="1" <?= $search_type==1?'selected':'' ?>>普通账户</option>
								<option value="2" <?= $search_type==2?'selected':'' ?>>代理账户</option>
							</select>
							时间
							<link rel="stylesheet" type="text/css" href="statics/js/calendar/calendar-blue.css">
							<script type="text/javascript" src="statics/js/calendar/calendar.js"></script>
							<input class="input-text date" type="text" name="search[start_time]" id="start_time" value="<?php echo $start_time?>" size="21" readonly="" placeholder="日期 Date">
							<script type="text/javascript">
								date = new Date();
								// document.getElementById("start_time").value="";
								Calendar.setup({
									inputField     :    "start_time",
									ifFormat       :    "%Y-%m-%d",
									showsTime      :    true,
									timeFormat     :    "24"
								});
							</script>								到 
							<input class="input-text date" type="text" name="search[end_time]" id="end_time" value="<?php echo $end_time?>" size="21" readonly="" placeholder="日期 Date">
							<script type="text/javascript">
								date = new Date();
								// document.getElementById("end_time").value="";
								Calendar.setup({
									inputField     :    "end_time",
									ifFormat       :    "%Y-%m-%d",
									showsTime      :    true,
									timeFormat     :    "24"
								});
							</script>
							<input type="submit" value="搜索" class="button" name="dosubmit">
						</div>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<div class="table-list">
		<form name="myform" id="myform" action="<?php echo ADMIN_PATH?>&c=account" method="post">
			<input type="hidden" name="type" value="all">
			<input type="hidden" name="a" value="del">
			<table width="100%" cellspacing="0">
				<thead>
					<tr>
						<th align="left" width="120">账户(UID)</th>
						<th align="center" width="150">总充值</th>
						<th align="center" width="60">总提现</th>
						<th align="center" width="100">客损</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($infos as $v){ ?>
					<tr id="list_<?php echo $v['id']?>">
						<td align="left"><?php echo $this -> go_user($v['uid'])?></td>
						<td align="center"><?= $v['recharge'] ?></td>
						<td align="center"><?= $v['withdraw'] ?></td>
						<td align="center"><?= sprintf("%.2f",$v['recharge']+$v['withdraw']) ?></td>
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