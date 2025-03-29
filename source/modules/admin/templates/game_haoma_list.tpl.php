<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">游戏开奖号码</h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
</div>
<div class="content-t">

	<div class="table-list">
	<table width="100%" cellspacing="0">
		<thead>
			<tr>
				<th align="center" width="240"><div style="float:left;" >下期期号&nbsp;:&nbsp;</div><div style="float:left;" id="nextqishu"></div>&nbsp;&nbsp;&nbsp;&nbsp;倒计时&nbsp;:&nbsp;<spam id="nextqishuTimer">加载中...</spam></th>
				<?php if(in_array($gameid,array(27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55))){ ?>
				<th align="center" width="80">预开奖:</th>
				<th align="left" width="150"><input placeholder="游戏期数" type="text" name="qishu" value=""></th>
				<th align="left" width="120"><input placeholder="开奖号码" type="text" name="haoma" id="haoma"></th>
				<th align="left"><a id="addykj" style="background:none;" href="javascript:;">提交</a> | <a id="random_haoma" style="background:none;padding-left:0;" href="javascript:;">随机</a> | <a id="addykjs" style="background:none;padding-left:0;" href="javascript:;">批量</a></th>
				<th align="center" width="80"></th>
				<?php } ?>
			</tr>
		</thead>
	</table>
	</div>

	<div class="tps">
		<p>提示：如果发生漏号现象，请手工补号，补号后，系统会自动给予结算；为了保证下注安全，如果发生漏期，系统不支持补期操作！请对相应注单进行退单处理（如果是漏期，前台也无法下注）！</p>
	</div>
	<div class="table-list">
		<table width="100%" cellspacing="0">
			<thead>
				<tr>
					<th align="center" width="80">游戏ID</th>
					<th align="left" width="150">期数</th>
					<th align="left" width="120">开奖时间</th>
					<th align="left">开奖号码</th>
					<th align="left">预开奖号码</th>
					<th align="center" width="80">结清</th>
					<th align="center" width="80">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($list as $v){?>
				<tr id="list_<?php echo $v['id']?>">
					<td align="center"><?php echo $v['gameid']?></td>
					<td align="left"><?php echo $v['qishu']?></td>
					<td align="left"><?php echo format::date($v['sendtime'], 1)?></td>
					<td align="left" class="haoma"><?php echo $v['haoma'] ? $v['haoma'] : '<a href="'.ADMIN_PATH.'&c=game&a=haoma_add&id='.$v['id'].'">[补号]</a>'?></td>
					<td align="left" class="haoma"><?php echo $v['yukaihaoma'] ? $v['yukaihaoma'] : '无'?></td>
					<td align="center"><?php echo $account[$v['account']]?></td>
					<td align="center">
						<?php if($v['is_lottery']==0){ ?>
							<a data-id="<?= $v['id']?>" class="saveyjk" href="javascript:;">修改</a>
						<?php } ?> 
						<a data-id="<?= $v['id']?>" class="delyjk" href="javascript:;">清除</a>
					</td>
				</tr>
			<?php }?>
			</tbody>
		</table>
		<div id="pages"><?php echo $pages?></div>
	</div>
</div>
</body>
<script src="statics/layer/layer.js" type="text/javascript"></script>
<script type="text/javascript">
	var haomaList = {
		"nextqishu":0,
		"uri":"/?a=ajax_haoma&gameid=<?= $gameid ?>&re=1",
		"TimerId":0,
		"CountdownId":0,
		"CountdownTime":0,
		"cycle":7,
		init:function(){
			var $this = this;
			$this.getQishu($this);
			$this.TimerId = setInterval(function(){$this.getQishu($this)},$this.cycle*1000);
			
		},
		getQishu:function($this){
			if($this.TimerId != 0){
				window.clearInterval($this.TimerId);
				$this.TimerId = 0;
			}
			if($this.CountdownId != 0){
				window.clearInterval($this.CountdownId);
				$this.CountdownId = 0;
			}

			$.get($this.uri,{},function(ret){
				if($this.nextqishu == 0){
					$this.nextqishu = ret.nextqishu;
					$("input[name='qishu']").val(ret.nextqishu);
				}else{
					if($this.nextqishu != ret.nextqishu){
						window.location.href = window.location.href;
					}
				}
				
				$("#nextqishu").text(ret.nextqishu);
				var timestamp = Date.parse(new  Date());
				$this.CountdownTime = ret.nextsendtime-(timestamp/1000);
				$this.CountdownId = setInterval(function(){$this.countdown($this)},1000);
			},'json');
		},
		countdown:function($this){
			var time = $this.CountdownTime;

			if(time > 0){
				time -= 1;
				var f = Math.floor(time/60);
				if(f.length < 2) f = '0'+f;
				var m = time%60;
				if(m.length < 2) m = '0'+m;
				$("#nextqishuTimer").text(f+':'+m);

				$this.CountdownTime = time;
			}else{
				window.location.href = window.location.href;
			}
			
		},
		editykj:function(){
			var qishu = $("input[name='qishu']").val();
			var haoma = $("input[name='haoma']").val();
			$.post("<?php echo ADMIN_PATH?>&c=game&a=haoma_inset_ajax&gameid=<?= $_GET['gameid'] ?>",{
				"qishu":qishu,
				"haoma":haoma,
			},function(ret){
				if(ret.status == 1){
					layer.msg(ret.info);
					setTimeout(function(){window.location.href = window.location.href;},1500);
				}else{
					layer.msg(ret.info);
				}
			},'json');
		},
		addsykjs:function(){
			layer.prompt({title: '请输入开始期数', formType: 3}, function(qishu, index){
				layer.prompt({title: '请输入批量个数', formType: 3}, function(num, index){
					$.post("<?php echo ADMIN_PATH?>&c=game&a=haoma_insets_ajax&gameid=<?= $_GET['gameid'] ?>",{
						qishu:qishu,
						num:num
					},function(ret){
						if(ret.status == 1){
							layer.msg(ret.info);
							setTimeout(function(){window.location.href = window.location.href;},1500);
						}else{
							layer.msg(ret.info);
							setTimeout(function(){window.location.href = window.location.href;},1500);
						}
					},'json');
				});
			});
		},
		saveyjk:function(obj){
			var id = $(obj).attr('data-id');
			layer.prompt({title: '请输入修改的号码', formType: 3}, function(haoma, index){
				$.post("<?php echo ADMIN_PATH?>&c=game&a=haoma_update_ajax&gameid=<?= $_GET['gameid'] ?>",{
						id:id,
						haoma:haoma
					},function(ret){
						if(ret.status == 1){
							layer.msg(ret.info);
							setTimeout(function(){window.location.href = window.location.href;},1500);
						}else{
							layer.msg(ret.info);
							setTimeout(function(){window.location.href = window.location.href;},1500);
						}
					},'json');
			});
		},
		delyjk:function(obj){
			var id = $(obj).attr('data-id');
			layer.confirm('确认要清除当前预开奖吗？', {
			  btn: ['确认','取消'] //按钮
			}, function(){
				$.post("<?php echo ADMIN_PATH?>&c=game&a=haoma_del_ajax&gameid=<?= $_GET['gameid'] ?>",{id:id},function(ret){
					if(ret.status == 1){
						layer.msg(ret.info);
						setTimeout(function(){window.location.href = window.location.href;},500);
					}else{
						layer.msg(ret.info);
						setTimeout(function(){window.location.href = window.location.href;},500);
					}
				},'json');
			},function(){
				layer.closeAll();
			});
			
		},
		random_haoma:function(){
			$.post("<?php echo ADMIN_PATH?>&c=game&a=random_haoma_ajax",{gameid:'<?= $gameid ?>'},function(ret){
				$("#haoma").val(ret);
			},'json');
		}
	};
	$(function(){
		$("#addykj").click(function(){haomaList.editykj();});
		$("#addykjs").click(function(){haomaList.addsykjs();});
		$(".delyjk").click(function(){haomaList.delyjk(this);});
		$(".saveyjk").click(function(){haomaList.saveyjk(this);});
		$("#random_haoma").click(function(){haomaList.random_haoma();});
		haomaList.init();
	});
</script>
</html>