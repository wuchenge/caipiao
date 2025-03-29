<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->daili_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">充值管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo DAILI_PATH?>&c=pay&a=init" class="on"><em>充值列表</em></a><span>|</span>
		<!-- <a href="<?php echo DAILI_PATH?>&c=pay&a=add"><em>代理充值</em></a> -->
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo DAILI_PATH?>&c=pay&a=search" method="get">
			<input type="hidden" name="m" value="daili">
			<input type="hidden" name="c" value="pay">
			<input type="hidden" name="a" value="search">
			<table width="100%" cellspacing="0" class="search-form">
				<tbody>
					<tr>
						<td>
						<div class="explain-col">
							单号 <input class="input-text" type="text" name="search[payid]" style="width:100px;" value="<?php echo $search_payid?>">
							账户UID <input class="input-text" type="text" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
							<select name="search[state]">
								<option value="0" <?php echo $stateoption[0]?>>全部</option>
								<option value="5" <?php echo $stateoption[5]?>>等待支付</option>
								<option value="4" <?php echo $stateoption[4]?>>充值失败</option>
								<option value="1" <?php echo $stateoption[1]?>>在线充值</option>
								<option value="2" <?php echo $stateoption[2]?>>人工充值</option>
								<option value="3" <?php echo $stateoption[3]?>>代理充值</option>
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
					<th align="left" width="140">上级代理人(UID)</th>
					<th align="left" width="60">类型/状态</th>
					<th align="center" width="100">金额</th>
					<th align="left" width="150">单号</th>
					<th align="left">备注</th>
					<th align="left" width="150">创建时间</th>
					<th align="left" width="150">支付时间</th>
					<th align="center" width="120">操作</th>
				</tr>
			</thead>
	    <tbody>
	    	<?php foreach ($list as $v){ ?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="left"><?php echo $this -> go_user($v['uid'])?></td>
					<td align="left"><?php echo $v['agents'] ? $this -> go_user($v['agent']) : '--'?></td>
					<td align="left" id="state_<?php echo $v['id']?>"><?php echo $this->state[$v['state']]?></td>
					<td align="center"><?php echo $v['money']?></td>
					<td align="left"><?php echo $v['payid']?></td>
					<td align="left" style="table-layout:fixed; word-break: break-all;"><?php echo $v['comment']?></td>
					<td align="left"><?php echo format::date($v['addtime'], 1)?></td>
					<td align="left" id="paytime_<?php echo $v['id']?>"><?php echo format::date($v['paytime'], 1)?></td>
					<td align="center">
						<?php if($v['state'] == 0) {?>
						<span id="addto_<?php echo $v['id']?>">
							<a href="javascript:;" onclick="showwindow('<?php echo DAILI_PATH?>&c=pay&a=addto&id=<?php echo $v['id']?>', '确定将这条充值订单到帐吗？<br/>注意：同等金额将从对应代理账户划走！', 1);">[成功]</a>
							<a href="javascript:;" onclick="showwindow('<?php echo DAILI_PATH?>&c=pay&a=delto&id=<?php echo $v['id']?>', '确定将这条充值订单标记为失败吗？', 1);">[失败]</a>
						</span>
						<?php }?>
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