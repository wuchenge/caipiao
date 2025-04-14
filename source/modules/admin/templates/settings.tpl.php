<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');
?>
<div class="subnav">
	<h2 class="title-1">系统设置</h2>
	<div class="content-menu">
		<a id="menu-1" href="javascript:;" onclick="menuswitch('1');" class="on"><em>SEO设置</em></a><span>|</span>
		<a id="menu-2" href="javascript:;" onclick="menuswitch('2');"><em>基本设置</em></a><span>|</span>
		<a id="menu-3" href="javascript:;" onclick="menuswitch('3');"><em>财务设置</em></a><span>|</span>
		<a id="menu-4" href="javascript:;" onclick="menuswitch('4');"><em>中奖公告</em></a><span>|</span>
		<a id="menu-5" href="javascript:;" onclick="menuswitch('5');"><em>首页轮番图</em></a>
	</div>
</div>
<div class="content-t">
	<form enctype="multipart/form-data" action="<?php echo ADMIN_PATH?>&c=settings&a=init" method="post" id="myform">
		<div id="content-1">
			<table width="100%" cellspacing="0" class="table_form">
				<tbody>
					<tr>
						<th>网站名称：</th>
						<td><input class="input-text" type="text" name="setting[webname]" value="<?php echo $webname;?>"></td>
					</tr>
					<tr>
						<th>网站访问地址：</th>
						<td>
							<input class="input-text" type="text" name="setting[weburl]" value="<?php echo $weburl;?>">
							<span>以“http://”开始，“/”结尾的网站访问地址</span>
						</td>
					</tr>
					<tr>
						<th>网站关键字：</th>
						<td><input class="input-text" type="text" name="setting[keywords]" style="width: 500px;" value="<?php echo $keywords;?>"><span>多个用“,”分开</span></td>
					</tr>
					<tr>
						<th>网站描述：</th>
						<td><input class="input-text" type="text" name="setting[description]" style="width: 500px;" value="<?php echo $description;?>"></td>
					</tr>
					<tr>
						<th>网站版权：</th>
						<td><input class="input-text" type="text" name="setting[copyright]" style="width: 500px;" value="<?php echo $copyright;?>"></td>
					</tr>
					<tr>
						<th>统计代码：</th>
						<td>
							<textarea class="input-text" name="setting[code]" style="width: 500px;height: 80px;"><?php echo $code;?></textarea>
						</td>
					</tr>
					<tr>
						<th>客服QQ：</th>
						<td>
							<input class="input-text" type="text" name="setting[qq]" style="width: 500px;" value="<?php echo $qq;?>">
							<p>多个QQ用“|”分开，显示名称和QQ用“@”分开，如：售前咨询@8888888|售后咨询@6666666</p>
						</td>
					</tr>
					<tr>
						<th>联系电话：</th>
						<td><input class="input-text" type="text" name="setting[phone]" value="<?php echo $phone;?>"></td>
					</tr>
					<tr>
						<th>Email：</th>
						<td><input class="input-text" type="text" name="setting[email]" value="<?php echo $email;?>"></td>
					</tr>
					<tr>
						<th>app链接：</th>
						<td><input class="input-text" type="text" name="setting[applink]" value="<?php echo $applink;?>"></td>
					</tr>
					<tr>
						<th>返点设置:</th>
						<td><input class="input-text" type="text" name="setting[rebates]" value="<?php echo $rebates;?>">%</td>
					</tr>
					<tr>
						<th>汇率设置：</th>
						<td style='line-height:30px;'><div>名称：<input class="input-text" type="text" name="setting[hbname1]" value="<?php echo $hbname1;?>">   
						         汇率：<input class="input-text" type="text" name="setting[hblv1]" value="<?php echo $hblv1;?>"></div>
						    <div>名称：<input class="input-text" type="text" name="setting[hbname2]" value="<?php echo $hbname2;?>">   
						         汇率：<input class="input-text" type="text" name="setting[hblv2]" value="<?php echo $hblv2;?>"></div>
						    <div>名称：<input class="input-text" type="text" name="setting[hbname3]" value="<?php echo $hbname3;?>">   
						         汇率：<input class="input-text" type="text" name="setting[hblv3]" value="<?php echo $hblv3;?>"></div>
						    <div>名称：<input class="input-text" type="text" name="setting[hbname4]" value="<?php echo $hbname4;?>">   
						         汇率：<input class="input-text" type="text" name="setting[hblv4]" value="<?php echo $hblv4;?>"></div>
						    <div>名称：<input class="input-text" type="text" name="setting[hbname5]" value="<?php echo $hbname5;?>">   
						         汇率：<input class="input-text" type="text" name="setting[hblv5]" value="<?php echo $hblv5;?>"></div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="content-2" style="display:none;">
			<table width="100%" cellspacing="0" class="table_form">
				<tbody>

				  	<tr>
					    <th>暂停投注：</th>
					    <td>
							<label><input type="radio" name="setting[stop]" value="0" <?php if ($stop == 0) echo 'checked="checked"'?> />否</label>
							<label><input type="radio" name="setting[stop]" value="1" <?php if ($stop == 1) echo 'checked="checked"'?> />是</label>
							<span class="label">控制全局投注开关，如需关停个别游戏，请前往《游戏管理》</span>
					    </td>
				  	</tr>
				  	<tr>
						<th>豹子通杀：</th>
						<td>
							<label><input type="radio" name="setting[bz_ts]" value="0" <?php if ($bz_ts == 0) echo 'checked="checked"'?> />否</label>
							<label><input type="radio" name="setting[bz_ts]" value="1" <?php if ($bz_ts == 1) echo 'checked="checked"'?> />是</label>
						</td>
					</tr>
					<tr>
						<th>13/14特殊玩法：</th>
						<td>
							<label><input type="radio" name="setting[is_1314]" value="0" <?php if ($is_1314 == 0) echo 'checked="checked"'?> />否</label>
							<label><input type="radio" name="setting[is_1314]" value="1" <?php if ($is_1314 == 1) echo 'checked="checked"'?> />是</label>
						</td>
					</tr>
					<tr>
						<th>初始金额：</th>
						<td>
							<input class="input-text" type="text" name="setting[money]" value="<?php echo $money;?>">
							<span>新注册用户初始的金额，一般用于限时全体活动</span>
						</td>
					</tr>
					<tr>
						<th>货币符号：</th>
						<td>
							<input class="input-text" type="text" name="setting[stamp]" value="<?php echo $stamp;?>">
						</td>
					</tr>
					<tr>
						<th>用户名验证：</th>
						<td>
							<input class="input-text" type="text" name="setting[username_type]" value="<?php echo $username_type;?>">
							<span>用于注册验证，支持正则或内置验证，*：不限制、m：手机号、*2-20：长度限制，支持验证组合，如：m|e：判断是否手机或者邮箱</span>
						</td>
					</tr>
					<tr>
						<th>禁用关键词：</th>
						<td>
							<input class="input-text" type="text" name="setting[userfilter]" style="width: 500px;" value="<?php echo $userfilter;?>">
							<span>多个关键字用用半角逗号“,”分开，包含关键词的用户名或者昵称禁止使用</span>
						</td>
					</tr>
					<tr>
						<th>网站公告：</th>
						<td>
							<textarea class="input-text" name="setting[ann]" style="width: 500px;height: 80px;"><?php echo $ann;?></textarea>
							<p>支持HTML</p>
						</td>
					</tr>
					<tr>
						<th>客服链接：</th>
						<td>
							<input class="input-text" type="text" name="setting[kefulink]" style="width: 500px;" value="<?php echo $kefulink;?>">
							<!-- <span>多个关键字用用半角逗号“,”分开，包含关键词的用户名或者昵称禁止使用</span> -->
						</td>
					</tr>
					<tr>
						<th>中国彩票祝您：</th>
						<td>
							<input class="input-text" type="text" name="setting[gfkjhref]" style="width: 500px;" value="<?php echo $gfkjhref;?>">
							<!-- <span>多个关键字用用半角逗号“,”分开，包含关键词的用户名或者昵称禁止使用</span> -->
						</td>
					</tr>
					<tr>
						<th>网站弹出公告：</th>
						<td>
							<textarea class="input-text" name="setting[announcement]" style="width: 500px;height: 80px;"><?php echo $announcement;?></textarea>
							<p>支持HTML</p>
						</td>
					</tr>
					<tr>
                <th>新用户注册送金额</th>
                <td>
                    最小值：<input type="text" class="textWid1" value="<?= $gift['registerMoneyMin']?>" name="setting[gift][registerMoneyMin]">元
                    最大值：<input type="text" class="textWid1" value="<?= $gift['registerMoneyMax']?>" name="setting[gift][registerMoneyMax]">元
                </td>
            </tr>
            <tr>
                <th>首冲送金额</th>
                <td>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoney']?>" name="setting[gift][firstRechargeMoney]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoneyGive']?>" name="setting[gift][firstRechargeMoneyGive]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoney2']?>" name="setting[gift][firstRechargeMoney2]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoneyGive2']?>" name="setting[gift][firstRechargeMoneyGive2]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoney3']?>" name="setting[gift][firstRechargeMoney3]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoneyGive3']?>" name="setting[gift][firstRechargeMoneyGive3]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoney4']?>" name="setting[gift][firstRechargeMoney4]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoneyGive4']?>" name="setting[gift][firstRechargeMoneyGive4]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoney5']?>" name="setting[gift][firstRechargeMoney5]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['firstRechargeMoneyGive5']?>" name="setting[gift][firstRechargeMoneyGive5]"><br>
                </td>
            </tr>
            <tr>
                <th>每日首冲送金额</th>
                <td>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoney']?>" name="setting[gift][dayFirstRechargeMoney]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoneyGive']?>" name="setting[gift][dayFirstRechargeMoneyGive]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoney2']?>" name="setting[gift][dayFirstRechargeMoney2]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoneyGive2']?>" name="setting[gift][dayFirstRechargeMoneyGive2]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoney3']?>" name="setting[gift][dayFirstRechargeMoney3]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoneyGive3']?>" name="setting[gift][dayFirstRechargeMoneyGive3]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoney4']?>" name="setting[gift][dayFirstRechargeMoney4]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoneyGive4']?>" name="setting[gift][dayFirstRechargeMoneyGive4]"><br>
                    首冲多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoney5']?>" name="setting[gift][dayFirstRechargeMoney5]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMoneyGive5']?>" name="setting[gift][dayFirstRechargeMoneyGive5]"><br>
                    <br>
                    赠送封顶：<input type="text" class="textWid1" value="<?= $gift['dayFirstRechargeMax']?>" name="setting[gift][dayFirstRechargeMax]">
                </td>
            </tr>
            <tr>
                <th>每次充值送金额</th>
                <td>
                    冲多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoney']?>" name="setting[gift][rechargeMoney]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoneyGive']?>" name="setting[gift][rechargeMoneyGive]"><br>
                    冲多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoney2']?>" name="setting[gift][rechargeMoney2]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoneyGive2']?>" name="setting[gift][rechargeMoneyGive2]"><br>
                    冲多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoney3']?>" name="setting[gift][rechargeMoney3]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoneyGive3']?>" name="setting[gift][rechargeMoneyGive3]"><br>
                    冲多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoney4']?>" name="setting[gift][rechargeMoney4]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoneyGive4']?>" name="setting[gift][rechargeMoneyGive4]"><br>
                    冲多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoney5']?>" name="setting[gift][rechargeMoney5]">&nbsp;&nbsp;
                    送多少：<input type="text" class="textWid1" value="<?= $gift['rechargeMoneyGive5']?>" name="setting[gift][rechargeMoneyGive5]"><br>
                    <br>
                    赠送封顶：<input type="text" class="textWid1" value="<?= $gift['rechargeGiveMax']?>" name="setting[gift][rechargeGiveMax]">
                </td>
            </tr>
					<tr>
						<th>充值提醒ID黑名单(如：,2343,223,)：</th>
						<td>
							<textarea class="input-text" name="setting[blacklist]" style="width: 500px;height: 80px;"><?php echo $blacklist;?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="content-3" style="display:none;">
			<table width="100%" cellspacing="0" class="table_form">
				<tbody>
					<tr>
						<th>充值失败提示：</th>
						<td>
							<textarea style="width: 300px;height: 60px;" class="input-text" type="text" name="setting[pay_error_remind]" ><?php echo $pay_error_remind;?></textarea>
						</td>
					</tr>
					<tr>
						<th>提现限制时间：</th>
						<td>
							<input class="input-text date" type="text" name="setting[cash_limit_start_time]" id="cash_limit_start_time" value="<?php echo $cash_limit_start_time;?>" size="21"  placeholder="开始时间">
							<input class="input-text date" type="text" name="setting[cash_limit_end_time]" id="cash_limit_end_time" value="<?php echo $cash_limit_end_time;?>" size="21"  placeholder="结束时间">
							<!-- <link rel="stylesheet" type="text/css" href="statics/js/calendar/calendar-blue.css">
							<script type="text/javascript" src="statics/js/calendar/calendar.js"></script>
							<script type="text/javascript">
								Calendar.setup({
									inputField     :    "cash_limit_start_time",
									ifFormat       :    "%H:%M:%S",
									showsTime      :    true,
									timeFormat     :    "24"
								});
								Calendar.setup({
									inputField     :    "cash_limit_end_time",
									ifFormat       :    "%H:%M:%S",
									showsTime      :    true,
									timeFormat     :    "24"
								});
							</script> -->
						</td>
					</tr>
					<tr>
						<th>初始打码值：</th>
						<td>
							<input class="input-text" type="text" name="setting[init_dama]" value="<?php echo $init_dama;?>">
							<span>全局生效</span>
						</td>
					</tr>
					<tr>
						<th>打码倍数：</th>
						<td>
							<input class="input-text" type="text" name="setting[dama_mult]" value="<?php echo $dama_mult;?>">
							<span>全局生效</span>
						</td>
					</tr>
					<tr>
						<th>最低充值金额：</th>
						<td>
							<input class="input-text" type="text" name="setting[pay]" value="<?php echo $pay;?>">
							<span>全局生效</span>
						</td>
					</tr>
					<tr>
						<th>最低提现金额：</th>
						<td>
							<input class="input-text" type="text" name="setting[tixian]" value="<?php echo $tixian;?>">
							<span>全局生效</span>
						</td>
					</tr>
					<tr>
						<th>提现手续费：</th>
						<td>
							<input class="input-text" type="text" name="setting[cash]" value="<?php echo $cash;?>">
							<span>全局生效，可填写百分比如：5% 即按照提现金额百分之5计算，或直接填写每笔订单的手续费</span>
						</td>
					</tr>
					<tr>
						<th>单笔提现上限：</th>
						<td>
							<input class="input-text" type="text" name="setting[maxcash]" value="<?php echo $maxcash;?>">
							<span>全局生效，每笔最大提现金额</span>
						</td>
					</tr>
					<tr>
						<th>投注金额限制：</th>
						<td>
							<input class="input-text" type="text" name="setting[send_money]" value="<?php echo $send_money;?>">
							<span>全局生效，填写格式：1-50000，如需限制个人账户请前往《账户管理》</span>
						</td>
					</tr>
			<tr>
				<th width="100"></th>
				<td><?php echo $wxewm ? '<img src="uppic/ewm/'.$wxewm.'" width="200" height="200" />' : ''?></td>
			</tr>
			<tr>
				<th width="100">微信收款二维码：</th>
				<td>
					<input type="file" id="wxfile" name="wxfile" accept="image/*" />
					<input type="hidden" id="wxfile_old" name="setting[wxfile_old]" value="<?= $wxewm ?>"/>
					<span>该信息将展示在直属会员或代理支付页面，建议二维码图片尺寸：200PX * 200PX</span>
				</td>
			</tr>
			<tr>
				<th width="100"></th>
				<td><?php echo $aliewm ? '<img src="uppic/ewm/'.$aliewm.'" width="200" height="200" />' : ''?></td>
			</tr>
			<tr>
				<th width="100">数字货币收款二维码：</th>
				<td>
					<input type="file" id="alifile" name="alifile" accept="image/*" />
					<input type="hidden" id="alifile_old" name="setting[alifile_old]" value="<?= $aliewm ?>"/>
					<span>该信息将展示在直属会员或代理支付页面，建议二维码图片尺寸：200PX * 200PX</span>
				</td>
			</tr>
			<tr>
				<th>收款银行：</th>
				<td>
					<textarea class="input-text" name="setting[card]" style="width: 300px;height: 60px;"><?php echo $card;?></textarea>
					<p>该信息将展示在直属会员或代理支付页面，请完整填写银行名称、卡号和姓名信息</p>
				</td>
			</tr>
			<tr>
				<th>支付备注：</th>
				<td>
					<input class="input-text" type="text" name="setting[remark]" value="<?php echo $remark?>" style="width: 200px;" />
					<span>该信息将展示在直属会员或代理支付页面，可填写联系方式或其他备注信息</span>
				</td>
			</tr>
				</tbody>
			</table>
		</div>
		<div id="content-4" style="display:none;">
			<table width="100%" cellspacing="0" class="table_form">
			<tbody>
			<tr>
				<th>中奖公告滚动：</th>
				<td>
					<label><input type="radio" name="setting[zj_gd]" value="1" <?php if ($zj_gd == 1) echo 'checked="checked"'?> />快</label>
					<label><input type="radio" name="setting[zj_gd]" value="2" <?php if ($zj_gd == 2) echo 'checked="checked"'?> />中</label>
					<label><input type="radio" name="setting[zj_gd]" value="3" <?php if ($zj_gd == 3) echo 'checked="checked"'?> />慢</label>
				</td>
			</tr>
			</tbody>
			</table>
			<table width="100%" cellspacing="0" class="table_form">
				<tbody id="zjTbody">
					<?php $zjarr = unserialize(urldecode($zjarr)); ?>
					<?php foreach ($zjarr as $key => $val) { ?>
					<tr>
						<th>中奖名称：</th>
						<td>
							<input class="input-text" type="text" name="setting[zjarr][zjname][]" value="<?= $val['zjname'] ?>">
						</td>
						<th>中奖游戏：</th>
						<td>
							<input class="input-text" type="text" name="setting[zjarr][zjgame][]" value="<?= $val['zjgame'] ?>">
						</td>
						<th>中奖金额：</th>
						<td>
							<input class="input-text" type="text" name="setting[zjarr][zjmoney][]" value="<?= $val['zjmoney'] ?>">
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div id="content-5" style="display:none;">
			<table width="100%" cellspacing="0" class="table_form">
				<tbody>
					<tr>
						<th>轮翻图1：</th>
						<td><input type="file" id="lunfantu1" name="lunfantu1" accept="image/*" />
					    <input type="hidden" id="lunfantu1_old" name="setting[lunfantu1_old]" value="<?= $lunfantu1 ?>"/>
						<span>建议二维码图片尺寸：750PX * 322PX</span>
					     跳转地址：
						<input class="input-text" type="text" name="setting[lfturl1]" value="<?php echo $lfturl1;?>">（本地直接填写路径，外链需要填写http://或https://）</td>
					</tr>
					<tr>
				<th width="100"></th>
				<td><?php echo $lunfantu1 ? '<img src="uppic/ewm/'.$lunfantu1.'" width="375" height="161" />' : ''?></td>
			        </tr>
			       <tr>
						<th>轮翻图2：</th>
						<td><input type="file" id="lunfantu2" name="lunfantu2" accept="image/*" />
					    <input type="hidden" id="lunfantu2_old" name="setting[lunfantu2_old]" value="<?= $lunfantu2 ?>"/>
						<span>建议二维码图片尺寸：750PX * 322PX</span>
					     跳转地址：
						<input class="input-text" type="text" name="setting[lfturl1]" value="<?php echo $lfturl1;?>">（本地直接填写路径，外链需要填写http://或https://）</td>
					</tr>
					<tr>
				<th width="100"></th>
				<td><?php echo $lunfantu2 ? '<img src="uppic/ewm/'.$lunfantu2.'" width="375" height="161" />' : ''?></td>
			      </tr>
			       <tr>
						<th>轮翻图3：</th>
						<td><input type="file" id="lunfantu3" name="lunfantu3" accept="image/*" />
					    <input type="hidden" id="lunfantu3_old" name="setting[lunfantu3_old]" value="<?= $lunfantu3 ?>"/>
						<span>建议二维码图片尺寸：750PX * 322PX</span>
					     跳转地址：
						<input class="input-text" type="text" name="setting[lfturl1]" value="<?php echo $lfturl1;?>">（本地直接填写路径，外链需要填写http://或https://）</td>
					</tr>
					<tr>
				<th width="100"></th>
				<td><?php echo $lunfantu3 ? '<img src="uppic/ewm/'.$lunfantu3.'" width="375" height="161" />' : ''?></td>
			</tr>
			<tr>
						<th>轮翻图4：</th>
						<td><input type="file" id="lunfantu4" name="lunfantu4" accept="image/*" />
					    <input type="hidden" id="lunfantu4_old" name="setting[lunfantu4_old]" value="<?= $lunfantu4 ?>"/>
						<span>建议二维码图片尺寸：750PX * 322PX</span>
					     跳转地址：
						<input class="input-text" type="text" name="setting[lfturl1]" value="<?php echo $lfturl1;?>">（本地直接填写路径，外链需要填写http://或https://）</td>
					</tr>
					<tr>
				    <th width="100"></th>
				       <td><?php echo $lunfantu4 ? '<img src="uppic/ewm/'.$lunfantu4.'" width="375" height="161" />' : ''?></td>
			        </tr>
			        <tr>
						<th>轮翻图5：</th>
						<td><input type="file" id="lunfantu5" name="lunfantu5" accept="image/*" />
					    <input type="hidden" id="lunfantu5_old" name="setting[lunfantu5_old]" value="<?= $lunfantu5 ?>"/>
						<span>建议二维码图片尺寸：750PX * 322PX</span>
					     跳转地址：
						<input class="input-text" type="text" name="setting[lfturl1]" value="<?php echo $lfturl1;?>">（本地直接填写路径，外链需要填写http://或https://）</td>
					</tr>
					<tr>
				      <th width="100"></th>
				     <td><?php echo $lunfantu5 ? '<img src="uppic/ewm/'.$lunfantu5.'" width="375" height="161" />' : ''?></td>
			       </tr>
				</tbody>
			</table>
		</div>
		<p class="mt20"></p>
		<input type="submit" class="button" name="dosubmit" value=" 提 交 " />
		<input style="display: 	none;" id="addzj" type="button" class="button"  value=" 添 加 " />
	</form>
</div>
</body>
</html>
<script id="zjTr" type="text/html">
	<tr>
		<th>中奖名称：</th>
		<td>
			<input class="input-text" type="text" name="setting[zjarr][zjname][]" value="">
		</td>
		<th>中奖游戏：</th>
		<td>
			<input class="input-text" type="text" name="setting[zjarr][zjgame][]" value="">
		</td>
		<th>中奖金额：</th>
		<td>
			<input class="input-text" type="text" name="setting[zjarr][zjmoney][]" value="">
		</td>
	</tr>
</script>
<script type="text/javascript">
	$("#menu-4").click(function(){
		$("#addzj").show();
	});
	$("#menu-1,#menu-5,#menu-2,#menu-3").click(function(){
		$("#addzj").hide();
	});
	$("#addzj").click(function(){
		$("#zjTbody").append($("#zjTr").html());
	});
</script>