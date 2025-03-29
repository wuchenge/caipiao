<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<style type="text/css">
	iframe{
		width: 100%;
		height: 415px;
	}
</style>
<div class="subnav">
	<h2 class="title-1">账户管理</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<div class="content-menu">
		<a href="<?php echo ADMIN_PATH?>&c=user&a=init"><em>账户列表</em></a><span>|</span>
		<a href="javascript:;" class="on"><em>详细信息</em></a>
	</div>
</div>
<div class="content-t">
	<div class="uname">
    <em>UID</em>
    <span><?php echo $data['uid']?></span>
    <em>上级代理人(UID)</em>
    <span><?php echo $this -> go_user($data['agent'])?></span>
    <em>用户名</em>
    <span><?php echo $data['username']?></span>
    <em>可用</em>
    <span><?php echo $data['money']?></span>
    <em>投注|盈亏</em>
    <span><?= $order_count['money'] ?>|<?= $order_count['account'] ?></span>
    
    <em>最后登录</em>
    <span><?php echo date("m-d H:i:s",$data['logintime'])?></span>
	</div>
	<div class="uname">
    <em>打码量(目标打码量)</em>
    <span><a id="dama_edit" href="javascript:;"><?= $data['dama']?></a>(<font id="aims_dama"><?= $data['aims_dama']?></font>)（打码量大于等于目标打码才可提现）</span>
    <em>清空打码量</em>
    <span><a id="dama_empty" href="javascript:;">清空</a></span>
    <em>免打码量可提现金额</em>
    <span><?= sprintf("%.2f",$data['free_dama'])?></span>
    
    
    
	</div>
	<div class="uname">
		<em>充值总额</em>
	    <span><?= $pay_count['money']?$pay_count['money']:'0.00' ?></span>
	    <em>提现总额</em>
	    <span><?= $cash_count['money']?$cash_count['money']:'0.00' ?></span>
		<em>今日盈亏</em>
    	<span><?= sprintf("%.2f",$day_order_count['account']-$day_order_count['money']) ?></span>
    	<em>今日投注总额</em>
	    <span><?= $day_order_count['money']?$day_order_count['money']:'0.00' ?></span>
	    <em>本周投注总额</em>
	    <span><?= $week_order_count['money']?$week_order_count['money']:'0.00' ?></span>
	</div>
	<div style="margin-top: 20px;" class="uname">
		<em>充值历史</em>
	</div>
	<iframe frameborder="no" border="0" src="<?php echo ADMIN_PATH?>&c=user&a=count_pay&uid=<?= $data['uid'] ?>"></iframe>

	<div class="uname">
		<em>提现历史</em>
	</div>
	<iframe frameborder="no" border="0" src="<?php echo ADMIN_PATH?>&c=user&a=count_cash&uid=<?= $data['uid'] ?>"></iframe>

	<div class="uname">
		<em>投注历史</em>
	</div>
	<iframe frameborder="no" border="0" src="<?php echo ADMIN_PATH?>&c=user&a=count_order&uid=<?= $data['uid'] ?>"></iframe>
</div>
</body>
<script src="statics/layer/layer.js" type="text/javascript"></script>
<script type="text/javascript">
	$("#dama_edit").click(function(){
		var _this = this;
		layer.prompt({title: '输入打码量', formType: 3,value:$(_this).text()}, function(dama, index){
			$.post("<?php echo ADMIN_PATH?>&c=user&a=ajax_dame_edit",{dama:dama,uid:<?=$data["uid"]?>},function(ret){
				if(ret.run == 'yes'){
					$(_this).text(dama);
					layer.closeAll();
				}else{
					layer.msg(ret.msg);
				}
			},'json');
		});
	});
	$("#dama_empty").click(function(){
		layer.confirm('确定要清空目标打码量吗？', {
		  btn: ['确定','取消'] //按钮
		}, function(){
			$.post("<?php echo ADMIN_PATH?>&c=user&a=ajax_dame_empty",{uid:<?=$data["uid"]?>},function(ret){
				if(ret.run == 'yes'){
					$("#aims_dama").text("0");
					layer.closeAll();
				}else{
					layer.msg(ret.msg);
				}
			},'json');
		},function(){
			layer.closeAll();
		});
	});
</script>
</html>