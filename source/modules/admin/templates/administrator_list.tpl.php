<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">管理员管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=administrator&a=init" class="on"><em>管理员列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=administrator&a=add"><em>添加管理员</em></a>
	</div>
</div>
<div class="content-t">
	<div class="table-list">
		<table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th align="left">用户名</th>
					<th align="left" width="150">手机号</th>
					<th align="center" width="100">用户类型</th>
					<th align="left" width="150">最后登录</th>
					<th align="left" width="150">登录IP</th>
					<th align="center" width="100">操作</th>
				</tr>
			</thead>
	    <tbody>
	    	<?php foreach ($list as $v){?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="left"><?php echo $v['username']?></td>
					<td align="left"><?php echo $v['mobile']?></td>
					<td align="center"><?php echo $issuperarr[$v['issuper']]?></td>
					<td align="left"><?php echo format::date($v['lastlogin'], 1)?></td>
					<td align="left"><?php echo $v['ip']?></td>
					<td align="center">
						<a href="<?php echo ADMIN_PATH?>&c=administrator&a=edit&id=<?php echo $v['id']?>">[修改]</a>
						<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=administrator&a=del&id=<?php echo $v['id']?>', '确定要删除这个管理员吗？');">[删除]</a>
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