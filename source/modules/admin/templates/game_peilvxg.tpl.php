<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<style type="text/css">
.content-t .data_box {
	position: relative;
}

.content-t .data_box a {
	position: absolute;
	left: 720px;
}
.content-t .data_box .df_box {
	margin-top: 10px;
}

</style>
<div class="subnav">
	<h2 class="title-1">游戏管理</h2>
	<div class="content-menu">
		<a class="on"><em>賠率修改</em></a>
	</div>
</div>
<div class="content-t">
	<form action="<?php echo ADMIN_PATH?>&c=game&a=edit&id=<?php echo $id?>" method="post" id="myform">
		<table width="100%" class="table_form">
			<tr>
				<th>游戏名称：</th>
				<td>
					<input class="input-text" type="text" name="name" value="<?php echo $data['name']?>" id="name" style="width: 200px;" />
				</td>
			</tr>
			<tr>
				<th>排序：</th>
				<td>
					<input class="input-text" type="text" name="sort" value="<?php echo $data['sort']?>" id="sort" />
					<span>显示顺序，数值越小越靠前</span>
				</td>
			</tr>
			<tr>
				<th>提前封盘：</th>
				<td>
					<input class="input-text" type="text" name="fptime" value="<?php echo $data['fptime']?>" id="fptime" />
					<span>单位：秒，请根据游戏开奖时间调整，注意如果开奖延迟过高，建议推前封盘的时间</span>
				</td>
			</tr>
			<tr>
				<th>模板名称：</th>
				<td>
					<input class="input-text" type="text" name="template" value="<?php echo $data['template']?>" id="template" style="width: 200px;" />
					<span>无需包含.html后缀名</span>
				</td>
			</tr>
			<tr>
				<th>状态：</th>
				<td>
					<label for="state"><input type="checkbox" id="state" name="state" value="1" <?php if($data['state'] == 1) echo 'checked="checked"';?> />开启</label>
				</td>
			</tr>
			<tr>
				<th>游戏数据配置：</th>
				<td>
					<div class="data_box">
						<div class="df_box">
							<a class="button add_data" href="javascript:;">增加一项</a>
							<textarea class="input-text" name="data[]" style="width: 700px;height: 120px;"><?php echo $dataarr[0]?></textarea>
						</div>
						<?php
						foreach($dataarr as $k => $data){
							if ($k > 0) {
						?>
						<div class="df_box">
							<a class="button del_data" href="javascript:;">删除此项</a>
							<textarea class="input-text" name="data[]" style="width: 700px;height: 120px;"><?php echo $data?></textarea>
						</div>
						<?php
							}
						}
						?>
					</div>
					<p>一行一条配置，具体配置写法需要配合模板及模块程序，请勿随意修改，你仅可配置赔率</p>
				</td>
			</tr>

		</table>
		<div class="mt20"></div>
		<input type="submit" class="button" name="dosubmit" value=" 提 交 " />
	</form>
</div>
<script type="text/javascript">
<!--
$(function(){
	var Vform = $("#myform").Validform();
	Vform.config({tiptype:3});
	Vform.addRule([
		{
			ele:'#name',
			datatype:'*1-20',
			nullmsg:'请输入游戏名称',
			errormsg:'游戏名称限制在1-20字符之间'
		},
		{
			ele:'#template',
			datatype:'/^[a-zA-Z][a-zA-Z0-9_]*$/',
			nullmsg:'请输入模板名称',
			errormsg:'模板名称必须以字母开头并且由字母数字下划线组成'
		}
	]);
	//绑定事件
	$('.add_data').click(function() {
		var html = ''+
			'<div class="df_box">'+
				'<a class="button del_data" href="javascript:;">删除此项</a>'+
				'<textarea class="input-text" name="data[]" style="width: 500px;height: 120px;"></textarea>'+
			'</div>';
		$('.data_box').append(html);
		$('.del_data').click(function() {
			$(this).parent('.df_box').remove();
		});
	});
	$('.del_data').click(function() {
		$(this).parent('.df_box').remove();
	});
})
//-->
</script>
</body>
</html>