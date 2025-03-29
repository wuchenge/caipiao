<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">提现管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=cash&a=init" class="on"><em>申请列表</em></a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo ADMIN_PATH?>&c=cash&a=search" method="get">
			<input type="hidden" name="m" value="admin">
			<input type="hidden" name="c" value="cash">
			<input type="hidden" name="a" value="search">
			<table width="100%" cellspacing="0" class="search-form">
				<tbody>
					<tr>
						<td>
						<div class="explain-col">
							账户UID <input class="input-text" type="text" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
							<select name="search[state]">
								<option value="0" <?php echo $stateoption[0]?>>全部</option>
								<option value="4" <?php echo $stateoption[4]?>>等待处理</option>
								<!--<option value="1" <?php echo $stateoption[1]?>>正在处理</option>-->
								<option value="2" <?php echo $stateoption[2]?>>提现成功</option>
								<option value="3" <?php echo $stateoption[3]?>>提现失败</option>
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
					<th align="left" width="60">状态</th>
					<th align="center" width="100">申请金额</th>
					<th align="center" width="100">到帐金额</th>
					<th align="left">备注（点击可编辑）</th>
					<th align="left" width="150">账号信息</th>
					<th align="left" width="130">申请/完成时间</th>
					<th align="center" width="130">操作</th>
				</tr>
			</thead>
	    <tbody>
	    	<?php foreach ($list as $v){ ?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="left"><?php echo $this -> go_user($v['uid'])?></td>
					<td align="left"><?php echo $this -> go_user($v['agent'])?></td>
					<td align="left" id="state_<?php echo $v['id']?>"><?php echo $this->state[$v['state']]?></td>
					<td align="center"><?php echo $v['money']?></td>
					<td align="center">
						<p style="color: #F00; font;font-weight: 700;font-size: 13px;"><?php echo $v['money'] - $v['service']?><p>
						<p style="color: #999;">手续费：<?php echo $v['service']?><p>
					</td>
					<td align="left">
						<input title="点击可编辑" class="input-text no" type="text" value="<?php echo $v['comment']?>" style="width: 200px;" ignore="ignore" datatype="*1-50" ajaxurl="<?php echo ADMIN_PATH?>&c=cash&a=ajax_comment&id=<?php echo $v['id']?>" nullmsg="输入备注" errormsg="备注内容在50字内" />
					</td>
					<td align="left"><?php echo $v['from']?></td>
					<td align="left">
						<p><?php echo format::date($v['addtime'], 1)?></p>
						<p id="endtime_<?php echo $v['id']?>"><?php echo format::date($v['endtime'], 1)?></p>
					</td>
					<td align="center">
						<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=cash&a=ajax_comment&del=yes&id=<?php echo $v['id']?>', '确定清空备注内容？');">[清空]</a>
						<span id="caozuo_<?php echo $v['id']?>">
							<?php if($v['state'] == 0) {?>
							<!--<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=cash&a=setstate&state=1&id=<?php echo $v['id']?>', '确定转为《正在处理》吗？', 1);">[处理]</a>-->
							<!--<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=cash&a=setstate&state=3&id=<?php echo $v['id']?>', '确定拒绝并转为《提现失败》吗？<br/>对应申请金额将自动返还给申请账户！<br/>建议备注失败的原因供用户参考。', 1);">[拒绝]</a>-->
							<!--?php } elseif($v['state'] == 1) {?-->
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=cash&a=setstate&state=2&id=<?php echo $v['id']?>', '确定转为《提现成功》吗？<br/>注意：如操作的为代理会员旗下订单将不会对上级代理账户进行入账操作！', 1);">[成功]</a>
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=cash&a=setstate&state=3&id=<?php echo $v['id']?>', '确定转为《提现失败》吗？<br/>对应申请金额将自动返还给申请账户！', 1);">[失败]</a>
							<?php } elseif($v['state'] > 1) {?>
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=cash&a=del&id=<?php echo $v['id']?>', '确定删除这条提现记录吗？<br/>注意：只能删除提现失败或者成功的数据！为方便用户查验，建议保留一定时间！');">[删除]</a>
							<?php }?>
						</span>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div id="pages"><?php echo $pages?></div>
	</div>
</div>
<script type="text/javascript">
<!--
$(function(){
	var Vform = $(".table-list").Validform();
	Vform.config({tiptype:3});
})
//-->
</script>
</body>
</html>