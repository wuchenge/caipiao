<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">统计</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
</div>
<div class="content-t">
	<div class="uname">
    <em>总提现单数</em>
    <span><?php echo $cash_count?> 条 <a href="<?php echo ADMIN_PATH?>&c=cash">立即管理</a></span>
	</div>
	<div class="uname">
		<form name="searchform" action="<?php echo ADMIN_PATH?>&c=cash&a=counts" method="get">
			<input type="hidden" name="m" value="admin">
			<input type="hidden" name="c" value="cash">
			<input type="hidden" name="a" value="counts">
			<em>账户UID</em> <input class="input-text" type="text" name="uid" style="width:50px;" value="<?php echo $uid?>">
			<em>上级代理UID</em> <input class="input-text" type="text" name="agent" style="width:50px;" value="<?php echo $agent?>">
			<em>总代理UID</em> <input class="input-text" type="text" name="agents" style="width:50px;" value="<?php echo $agents?>">
			<em>状态</em> <?php echo form::select($statearr, $state, 'name="state"', '全部', 0)?>
			<em>自定义时间</em><?php echo form::date('start_time',$start_time,'1')?>
			<em>到</em><?php echo form::date('end_time',$end_time,'1')?>
			<input type="submit" value="查询" class="button" name="dosubmit">
			<?php if($custom){?><input type="submit" value="返回" class="button" name="backsubmit"><?php }?>
		</form>
	</div>
	<?php if($custom){?>
	<table width="100%" class="table_form info custom">
	  <tr>
	    <th class="title">类型</th>
	    <th><?php echo $start_time?> ~ <?php echo $end_time?></th>
	  </tr>
	  <tr>
	    <td class="title">提现单数<span title="用户提现的总单数。">？</span></td>
	    <td ><?php echo intval($custom_count['num'])?></td>
	  </tr>
	  <tr>
	    <td class="title">提现总额<span title="用户提现的总金额">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($custom_count['money'], 2)?></td>
	  </tr>
	</table>
	<?php }else{?>
	<table width="100%" class="table_form info">
	  <tr>
	    <th class="title">类型</th>
	    <th>今日</th>
	    <th>昨日</th>
	    <th>本周</th>
	    <th>本月</th>
	    <th>上月</th>
	    <th>本季度</th>
	  </tr>
	  <tr>
	    <td class="title">提现单数<span title="用户提现的总单数。">？</span></td>
	    <td><?php echo intval($today_count['num'])?></td>
	    <td><?php echo intval($yesterday_count['num'])?></td>
	    <td><?php echo intval($tswk_count['num'])?></td>
	    <td><?php echo intval($thismonth_count['num'])?></td>
	    <td><?php echo intval($lastmonth_count['num'])?></td>
	    <td><?php echo intval($quarter_count['num'])?></td>
	  </tr>
	  <tr>
	    <td class="title">提现总额<span title="用户提现的总总额。">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($today_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($yesterday_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($tswk_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($thismonth_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($lastmonth_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($quarter_count['money'], 2)?></td>
	  </tr>
	</table>
	<?php }?>
</div>
<script type="text/javascript">
	$('.table_form .title span').click(function(){
		layer.tips($(this).attr('title'), $(this), {tips: [1, '#333C59'], time: 4000});
	});
</script>
</body>
</html>
