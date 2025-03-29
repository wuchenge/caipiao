<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->daili_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">流水管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo DAILI_PATH?>&c=account&a=init" class="on"><em>流水列表</em></a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo DAILI_PATH?>&c=account&a=search" method="get">
			<input type="hidden" name="m" value="daili">
			<input type="hidden" name="c" value="account">
			<input type="hidden" name="a" value="search">
			<table width="100%" cellspacing="0" class="search-form">
				<tbody>
					<tr>
						<td>
						<div class="explain-col">
							账户UID <input class="input-text" type="text" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
							<select name="search[type]">
								<option value="0" <?php echo $typeoption[0]?>>全部</option>
								<option value="5" <?php echo $typeoption[5]?>>充值</option>
								<option value="1" <?php echo $typeoption[1]?>>提现</option>
								<option value="2" <?php echo $typeoption[2]?>>投注</option>
								<option value="3" <?php echo $typeoption[3]?>>盈利</option>
								<option value="4" <?php echo $typeoption[4]?>>退单</option>
							</select>
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
					<th align="left" width="150">时间</th>
					<th align="left" width="60">类型</th>
					<th align="center" width="100">金额</th>
					<th align="center" width="100">资金变动</th>
					<th align="left">备注</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($list as $v){ ?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="left"><?php echo $this -> go_user($v['uid'])?></td>
					<td align="left"><?php echo format::date($v['addtime'], 1)?></td>
					<td align="left"><?php echo $this->type[$v['type']]?></td>
					<td align="center"><?php echo $v['money']?></td>
					<td align="center"><?php echo $v['countmoney']?></td>
					<td align="left" style="table-layout:fixed; word-break: break-all;"><?php echo $v['comment']?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div id="pages"><?php echo $pages?></div>
	</div>
	</div>
</body>
</html>