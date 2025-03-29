<?php
defined('IN_DAILI') or exit('No permission resources.');
include $this->daili_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">统计信息</h2>
</div>
<div class="content-t">
	<div class="uname">
    <em>代理人</em>
    <span><?php echo $this -> username?></span>
    <em>旗下账户总数</em>
    <span><?php echo $daili_user_count?> 人 <a href="<?php echo DAILI_PATH?>&c=user&a=search&search[agent]=<?php echo $data['uid']?>">立即管理</a></span>
    <em>旗下注单总数</em>
    <span><?php echo $daili_order_count?> 条 <a href="<?php echo DAILI_PATH?>&c=order&a=search&search[agent]=<?php echo $data['uid']?>">立即管理</a></span>
	</div>
	<div class="uname">
		<form name="searchform" action="<?php echo DAILI_PATH?>&c=index&a=right" method="get">
			<input type="hidden" name="m" value="daili">
			<input type="hidden" name="c" value="index">
			<input type="hidden" name="a" value="right">
			<em>游戏</em> <?php echo form::select($gamearr, $gameid, 'name="gameid"', '全部', 0)?>
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
	    <td class="title">注单数<span title="该代理人旗下用户投注的总单数，但不包含退单。">？</span></td>
	    <td ><?php echo intval($custom_count['num'])?></td>
	  </tr>
	  <tr>
	    <td class="title">注单总额<span title="该代理人旗下用户未开奖结算与已结算注单的所有金额，但不包含已经退单的金额。">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($custom_count['money'], 2)?></td>
	  </tr>
    <tr>
      <td class="title">注单总额(输赢)<span title="该代理人旗下用户输赢注单金额，不包含和局、未结算和已经退单的金额。">？</span></td>
      <td><?php echo $settingarr['stamp'].round($custom_count['moneys'], 2)?></td>
    </tr>
	  <tr>
	    <td class="title">盈利总额<span title="该代理人旗下用户有效注单的结算统计，正数代表用户盈利，负数代表用户亏损。">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($custom_count['account'], 2)?></td>
	  </tr>
	  <tr>
	    <td class="title">本金总额(赢单)<span title="该代理人旗下用户有效赢单本金总额，不包含和局、输单、未结算和已经退单的金额。">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($custom_count['take'], 2)?></td>
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
	    <td class="title">注单数<span title="该代理人旗下用户投注的总单数，但不包含退单。">？</span></td>
	    <td><?php echo intval($daili_today_count['num'])?></td>
	    <td><?php echo intval($daili_yesterday_count['num'])?></td>
	    <td><?php echo intval($daili_tswk_count['num'])?></td>
	    <td><?php echo intval($daili_thismonth_count['num'])?></td>
	    <td><?php echo intval($daili_lastmonth_count['num'])?></td>
	    <td><?php echo intval($daili_quarter_count['num'])?></td>
	  </tr>
	  <tr>
	    <td class="title">注单总额<span title="该代理人旗下用户未开奖结算与已结算注单的所有金额，但不包含已经退单的金额。">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($daili_today_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_yesterday_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_tswk_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_thismonth_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_lastmonth_count['money'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_quarter_count['money'], 2)?></td>
	  </tr>
    <tr>
      <td class="title">注单总额(输赢)<span title="该代理人旗下用户输赢注单金额，不包含和局、未结算和已经退单的金额。">？</span></td>
      <td><?php echo $settingarr['stamp'].round($daili_today_count['moneys'], 2)?></td>
      <td><?php echo $settingarr['stamp'].round($daili_yesterday_count['moneys'], 2)?></td>
      <td><?php echo $settingarr['stamp'].round($daili_tswk_count['moneys'], 2)?></td>
      <td><?php echo $settingarr['stamp'].round($daili_thismonth_count['moneys'], 2)?></td>
      <td><?php echo $settingarr['stamp'].round($daili_lastmonth_count['moneys'], 2)?></td>
      <td><?php echo $settingarr['stamp'].round($daili_quarter_count['moneys'], 2)?></td>
    </tr>
	  <tr>
	    <td class="title">盈利总额<span title="该代理人旗下用户有效注单的结算统计，正数代表用户盈利，负数代表用户亏损。">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($daili_today_count['account'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_yesterday_count['account'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_tswk_count['account'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_thismonth_count['account'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_lastmonth_count['account'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_quarter_count['account'], 2)?></td>
	  </tr>
	  <tr>
	    <td class="title">本金总额(赢单)<span title="该代理人旗下用户有效赢单本金总额，不包含和局、输单、未结算和已经退单的金额。">？</span></td>
	    <td><?php echo $settingarr['stamp'].round($daili_today_count['take'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_yesterday_count['take'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_tswk_count['take'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_thismonth_count['take'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_lastmonth_count['take'], 2)?></td>
	    <td><?php echo $settingarr['stamp'].round($daili_quarter_count['take'], 2)?></td>
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
