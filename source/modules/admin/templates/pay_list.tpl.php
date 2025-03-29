<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">充值管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=pay&a=init" class="on"><em>充值列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=pay&a=add"><em>人工充值</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=pay&a=counts"><em>充值统计</em></a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo ADMIN_PATH?>&c=pay&a=search" method="get">
			<input type="hidden" name="m" value="admin">
			<input type="hidden" name="c" value="pay">
			<input type="hidden" name="a" value="search">
			<table width="100%" cellspacing="0" class="search-form">
				<tbody>
					<tr>
						<td>
						<div class="explain-col">
							单号 <input class="input-text" type="text" name="search[payid]" style="width:100px;" value="<?php echo $search_payid?>">
							账户UID <input class="input-text" type="text" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
							上级代理UID <input class="input-text" type="text" name="search[agent]" style="width:50px;" value="<?php echo $search_agent?>">
							总代理UID <input class="input-text" type="text" name="search[agents]" style="width:50px;" value="<?php echo $search_agents?>">
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
					<th align="left" width="140">通道</th>
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
					<td align="left">
						<?php 
						  switch($v['paytype']){
						  	case 1:
						  	    echo $v['type2'];
						  		/*if($v['type2'] == 'zfbsm'){
						  			echo "支付宝扫码";
						  		}else if($v['type2'] == 'ylkj'){
						  			echo '银联快捷';
						  		}else{
						  		    echo '支付宝';
						  		}*/
						  		break;
					  		case 2:
					  			echo '微信';
					  			break;
						  	case 3:
						  		echo '银联';
						  		break;
						  }
						?>
					
					</td>
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