<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">账户管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<a href="javascript:;" onclick="searchshow(1)" class="searchshow">展开/收起搜索栏</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=user&a=init" class="on"><em>账户列表</em></a><span>|</span>
		<a href="<?php echo ADMIN_PATH?>&c=user&a=add"><em>账户注册</em></a>
	</div>
</div>
<div class="content-t">
	<div id="searchshow">
		<form name="searchform" action="<?php echo ADMIN_PATH?>&c=user&a=search" method="get" >
			<input type="hidden" name="m" value="admin">
			<input type="hidden" name="c" value="user">
			<input type="hidden" name="a" value="search">
			<table width="100%" cellspacing="0" class="search-form">
			  <tbody>
					<tr>
						<td>
							<div class="explain-col">
								UID <input class="input-text" type="text" id="uid" name="search[uid]" style="width:50px;" value="<?php echo $search_uid?>">
								注册时间 <?php echo form::date('search[start_time]',$search_start_time,'1')?>
								到 <?php echo form::date('search[end_time]',$search_end_time,'1')?>
								用户名/昵称 <input class="input-text" type="text" id="username" name="search[username]" value="<?php echo $search_username?>">
								代理人UID <input class="input-text" type="text" id="agent" name="search[agent]" style="width:50px;" value="<?php echo $search_agent?>">
								<select name="search[aid]">
									<option value="0" <?php echo $aidoption[0]?>>全部账户</option>
									<option value="4" <?php echo $aidoption[4]?>>普通账户</option>
									<option value="1" <?php echo $aidoption[1]?>>一级代理</option>
									<option value="2" <?php echo $aidoption[2]?>>二级代理</option>
									<option value="3" <?php echo $aidoption[3]?>>二级代理(阅)</option>
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
					<th align="center" width="80">UID</th>
					<th align="left" width="120">用户名</th>
					<th align="left">昵称</th>
					<th align="left" width="100">姓名/手机号</th>
					<th align="left" width="70">金额</th>
					<th align="center" width="30">锁定</th>
					<th align="center" width="50">代理</th>
					<th align="center" width="140">代理二维码</th>
					<th align="left" width="140">上级代理人(UID)</th>
					<th align="left" width="120">登录IP</th>
					<th align="left" width="140">登录/注册时间</th>
					<th align="center" width="185">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($infos as $v){?>
				<tr id="list_<?php echo $v['uid']?>">
					<td align="center"><a style="color: #45c2b5;" href="<?php echo ADMIN_PATH?>&c=user&a=count_info&uid=<?php echo $v['uid']?>"><?php echo $v['uid']?></a></td>
					<td align="left"><a style="color: #45c2b5;" href="<?php echo ADMIN_PATH?>&c=user&a=count_info&uid=<?php echo $v['uid']?>"><?php echo $v['username']?></a></td>
					<td align="left"><?php echo $v['nickname']?></td>
					<td align="left"><p><?php echo $v['name']?></p><p><?php echo $v['mobile']?></p></td>
					<td align="left"><?php echo $v['money']?></td>
					<td align="center"><?php echo $this -> lock[$v['lock']]?></td>
					<td align="center"><?php echo $this -> daili[$v['aid']]?></td>
					<td align="left"><?php echo $v['aid']>0?'<div data-url="'.$this->setting["weburl"].'/?a=register&agent='.$v['uid'].'" class="dlqrcode"></div>':'' ?></td>
					<td align="left"><?php echo $this -> go_user($v['agent'])?></td>
					<td align="left"><?php echo $v['loginip']?></td>
					<td align="left"><p><?php echo format::date($v['logintime'], 1)?></p><p><?php echo format::date($v['regtime'], 1)?></p></td>
					<td align="center">
						<p>
							<a href="<?php echo ADMIN_PATH?>&c=order&a=search&search[uid]=<?php echo $v['uid']?>">[注单]</a>
							<a href="<?php echo ADMIN_PATH?>&c=account&a=addBet&uid=<?php echo $v['uid']?>">[增加流水]</a>
							<a href="<?php echo ADMIN_PATH?>&c=account&a=search&search[uid]=<?php echo $v['uid']?>">[流水]</a>
							<a href="<?php echo ADMIN_PATH?>&c=pay&a=search&search[uid]=<?php echo $v['uid']?>">[充值单]</a>
							<a href="<?php echo ADMIN_PATH?>&c=cash&a=search&search[uid]=<?php echo $v['uid']?>">[提现单]</a>
							
						</p>
						<p>
							<?php if ($v['aid'] > 0) {?>
							<a href="<?php echo ADMIN_PATH?>&c=user&a=search&search[agent]=<?php echo $v['uid']?>"><span style="color: #F00;">[下级]</span></a>
							<?php }?>
							<a href="<?php echo ADMIN_PATH?>&c=user&a=info&uid=<?php echo $v['uid']?>">[报表]</a>
							<a href="<?php echo ADMIN_PATH?>&c=pay&a=add&uid=<?php echo $v['uid']?>">[充值]</a>
							<a href="<?php echo ADMIN_PATH?>&c=user&a=edit&uid=<?php echo $v['uid']?>">[修改]</a>
							<a href="javascript:;" onclick="showwindow('<?php echo ADMIN_PATH?>&c=user&a=del&uid=<?php echo $v['uid']?>', '确定要删除吗？<br/>注意：账户下所有的注单报表流水等记录也将删除！');">[删除]</a>
							<a href="<?php echo ADMIN_PATH?>&c=user&a=reset_m_pwd&uid=<?php echo $v['uid']?>">[初始化资金密码]</a>
						</p>
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
<script type="text/javascript" src="/statics/jquery-qrcode-master/jquery.qrcode.min.js"></script>
<script type="text/javascript">
	$(function(){
		$(".dlqrcode").each(function(){
			var url = $(this).attr('data-url');
			$(this).qrcode({width: 128,height: 128,text: url});
		});
	});
	
</script>