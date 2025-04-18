<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">游戏管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=game&a=init" class="on"><em>游戏列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=game&a=add"><em>添加游戏</em></a><span>|</span>
		<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=game&a=delall', '确定清理3个月之前的开奖记录吗？<br/>开奖记录号码库日益增大，3个月之前的开奖记录已无翻查意义，建议定期清理开奖记录！');">清理开奖记录</a>
	</div>
</div>
<div class="content-t">
	<div class="table-list">
		<table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th align="center" width="80">游戏ID</th>
					<th align="left">游戏名称</th>
					<th align="center" width="80">提前封盘</th>
					<th align="left" width="120">模板名称</th>
					<th align="center" width="50">游戏状态</th>
					<th align="center" width="80">排序</th>
					<th align="center" width="150">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($list as $v){?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="center"><?php echo $v['id']?></td>
					<td align="left"><?php echo $v['name']?></td>
					<td align="center"><?php echo $v['fptime']?> s</td>
					<td align="left"><?php echo $v['template']?></td>
					<td align="center"><?php echo $state[$v['state']]?></td>
					<td align="center"><?php echo $v['sort']?></td>
					<td align="center">
					     <?php if(($v['id']==13 || $v['id']==14) && false) echo "<a href=\"".ADMIN_PATH."&c=game&a=haoma_inset&gameid=".$v['id']."\">[预开]</a>" ?>
					     <a href="<?php echo ADMIN_PATH?>&c=game&a=room&gameid=<?php echo $v['id']?>">[房间]</a>
						<a href="<?php echo ADMIN_PATH?>&c=game&a=haoma&gameid=<?php echo $v['id']?>">[开奖号码]</a>
						<a href="<?php echo ADMIN_PATH?>&c=game&a=edit&id=<?php echo $v['id']?>">[修改]</a>
						<!--<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=game&a=del&id=<?php echo $v['id']?>', '确定要删除这个游戏吗？');">[删除]</a>-->
					</td>
				</tr>
			<?php }?>
			</tbody>
		</table>
		<div id="pages"><?php echo $pages?></div>
	</div>
</div>
</body>
</html>