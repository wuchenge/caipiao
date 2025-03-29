<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<style type="text/css">
	.content-menu div{display: inline-block;}
</style>
<div class="subnav">
	<h2 class="title-1"><?= $game['name'] ?></h2>
	<a href="javascript:location.reload();" class="reload">刷新</a>
	<div class="content-menu">
		<div class="room_title"><a href="javascript:;" class="on"><em>原房间</em></a><span>|</span></div>
		<?php if(!empty($room)){foreach ($room as $key => $val) { ?>
			<div class="room_title"><a href="javascript:;" class=""><em class="room_name"><?= $val['name'] ?></em></a><span>|</span></div>
		<?php }} ?>
		<div><a class="addRoom" href="javascript:;"><em>添加房间</em></a></div>
	</div>
</div>
<div class="content-t">
	<form enctype="multipart/form-data" action="<?php echo ADMIN_PATH?>&c=game&a=room&gameid=<?= $game['id'] ?>" method="post" id="myform">
		<div class="content">
			<table width="100%" cellspacing="0" class="table_form">
				<tbody>
					<tr>
						<th>房间名称：</th>
						<td><input class="input-text" type="text" readonly value="<?= $game['name'] ?>"></td>
					</tr>
					<tr>
						<th>游戏数据配置：</th>
						<td>
							<div class="data_box">
								<div class="df_box">
									<textarea class="input-text" readonly  style="width: 700px;height: 120px;"><?= $game['data'] ?></textarea>
								</div>
							</div>
							<p>一行一条配置，具体配置写法需要配合模板及模块程序，请勿随意修改，你仅可配置赔率</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php if(!empty($room)){foreach ($room as $key => $val) { ?>
		<div style="display: none;" class="content">
			<table width="100%" cellspacing="0" class="table_form">
				<tbody>
					<tr>
						<th>房间名称：</th>
						<td><input class="input-text" type="text" name="name[]" value="<?= $val['name'] ?>"></td>
					</tr>
					<tr>
						<th>最低投注金额：</th>
						<td>
							<input class="input-text" type="text" name="minBetting[]" value="<?= $val['minBetting'] ?>">
						</td>
					</tr>
					<tr>
						<th>最小准入金额：</th>
						<td>
							<input class="input-text" type="text" name="minimum[]" value="<?= $val['minimum'] ?>">
						</td>
					</tr>
					<tr>
						<th>最大准入金额：</th>
						<td>
							<input class="input-text" type="text" name="maximum[]" value="<?= $val['maximum'] ?>">
						</td>
					</tr>
					<tr>
						<th>房间图片：</th>
						<td>
							<input class="room_input new_img_input" type="file" name="new_img_<?= $key+1 ?>" accept="image/*">
						</td>
					</tr>
					<tr>
						<th></th>
						<td>
							<img <?= empty($val['img'])?'style="display:none;"':'' ?> class="room_img" src="/uppic/room/<?= $val['img'] ?>" />
							<input class="input-text" type="hidden" name="old_img[]" value="<?= $val['img'] ?>">
						</td>
					</tr>
					<tr>
						<th>排序：</th>
						<td>
							<input class="input-text" type="text" name="sort[]" value="<?= $val['sort'] ?>">
							<span>显示顺序，数值越小越靠前</span>
						</td>
					</tr>
					<tr>
						<th>状态：</th>
						<td>
							<label for="state"><input type="checkbox" name="state[]" value="1" <?= empty($val['state'])?'':'checked="checked"' ?> >开启</label>
						</td>
					</tr>
					<tr>
						<th>房间密码：</th>
						<td>
							<label for="state"><input type="text" name="pass[]" value="<?= $val['pass'] ?>"  ></label>
						</td>
					</tr>
					<tr>
						<th>游戏数据配置：</th>
						<td>
							<div class="data_box">
								<div class="df_box">
									<textarea class="input-text" name="data[]" style="width: 700px;height: 120px;"><?= $val['data'] ?></textarea>
								</div>
							</div>
							<p>一行一条配置，具体配置写法需要配合模板及模块程序，请勿随意修改，你仅可配置赔率</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php }} ?>
		<p class="mt20"></p>
		<input type="submit" class="button" name="dosubmit" value=" 提 交 " />
		<input id="delcord" style="display:none;" type="button" class="button" value=" 删 除 " />
	</form>
</div>
</body>
</html>
<script id="room_template" type="text/html">
	<div class="content">
		<table width="100%" cellspacing="0" class="table_form">
			<tbody>
				<tr>
					<th>房间名称：</th>
					<td><input class="input-text" type="text" name="name[]" value=""></td>
				</tr>
				<tr>
					<th>最低投注金额：</th>
					<td>
						<input class="input-text" type="text" name="minBetting[]" value="<?= $val['minBetting'] ?>">
					</td>
				</tr>
				<tr>
					<th>最小准入金额：</th>
					<td>
						<input class="input-text" type="text" name="minimum[]" value="">
					</td>
				</tr>
				<tr>
					<th>最大准入金额：</th>
					<td>
						<input class="input-text" type="text" name="maximum[]" value="">
					</td>
				</tr>
				<tr>
					<th>房间图片：</th>
					<td>
						<input class="room_input new_img_input" type="file" name="new_img_1" accept="image/*">
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<img style="display:none;" class="room_img" src="###" />
						<input class="input-text" type="hidden" name="old_img[]" value="">
					</td>
				</tr>
				<tr>
					<th>排序：</th>
					<td>
						<input class="input-text" type="text" name="sort[]" value="99">
						<span>显示顺序，数值越小越靠前</span>
					</td>
				</tr>
				<tr>
					<th>状态：</th>
					<td>
						<label for="state"><input type="checkbox" name="state[]" value="1" checked="checked">开启</label>
					</td>
				</tr>
				<tr>
					<th>游戏数据配置：</th>
					<td>
						<div class="data_box">
							<div class="df_box">
								<textarea class="input-text" name="data[]" style="width: 700px;height: 120px;"><?= $game['data'] ?></textarea>
							</div>
						</div>
						<p>一行一条配置，具体配置写法需要配合模板及模块程序，请勿随意修改，你仅可配置赔率</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</script>
<script type="text/javascript">
	var room = {
		num:0,
		add:function(){
			var $this = this;
			var num = $("#myform .content").length;
			$(".room_title").last()
			.after('<div class="room_title"><a href="javascript:;" class=""><em class="room_name">房间'+num+'</em></a><span>|</span></div>');

			$("#room_template").find(".new_img_input").attr('name','new_img_'+num);

			$("#myform .content").hide();
			$("#myform .content").last().after($("#room_template").html());

			$this.select(num);
		},
		select:function(num){
			var $this = this;
			$this.num = num;
			if(num == 0){
				$("#delcord").hide();
			}else{
				$("#delcord").show();
			}
			$(".room_title a").removeClass("on");
			$(".room_title a").eq(num).addClass("on");

			$("#myform .content").hide();
			$("#myform .content").eq(num).show();
		},
		delcord:function(){
			var $this = this;
			var num = $this.num;
			if(num == 0){
				alert("无法删除");
			}else{
				$(".room_title").eq(num).remove();
				$("#myform .content").eq(num).remove();
				$this.select(num-1);
			}
		}
	};
	$(function(){
		$(".addRoom").click(function(){room.add()});
		$(document).on("click",".room_title",function(){
			room.select($(this).index());
		});
		$("#delcord").click(function(){room.delcord()});
		$(document).on("change",".new_img_input",function(){
			var fileObj = $(this)[0].files[0];
			if(fileObj){
				var src = window.URL.createObjectURL(fileObj);
				$(this).parents("tr").next("tr").find(".room_img").attr('src',src);
				$(this).parents("tr").next("tr").find(".room_img").show();
			}
		});
	});
</script>