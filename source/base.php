<?php
define('IN_MYWEB', true);
// 框架路径
define('FILE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

if (!defined('MYFILE_PATH')) define('MYFILE_PATH', FILE_PATH . '..' . DIRECTORY_SEPARATOR);
// 缓存文件夹地址
define('CACHE_PATH', FILE_PATH . '..' . DIRECTORY_SEPARATOR . 'caches' . DIRECTORY_SEPARATOR);
// 配置文件地址
define('CONFIG_PATH', FILE_PATH . '..' . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR);
// 主机协议
define('SITE_PROTOCOL', isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://');
// 当前访问的主机名
define('SITE_URL', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
// 来源
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
// 系统开始时间
define('SYS_START_TIME', microtime());
// 加载公用函数库
base :: load_sys_func('global');
base :: load_config('system', 'errorlog') ? set_error_handler('my_error_handler') : error_reporting(E_ERROR | E_WARNING | E_PARSE);
// 设置本地时差
function_exists('date_default_timezone_set') && date_default_timezone_set(base :: load_config('system', 'timezone'));

define('CHARSET' , base :: load_config('system', 'charset'));
// 输出页面字符集
header('Content-type: text/html; charset=' . CHARSET);

define('SYS_TIME', time());
// 定义网站根路径
define('WEB_PATH', base :: load_config('system', 'web_path'));
define('INDEX_PATH', base :: load_config('system', 'index_path'));
define('ADMIN_PATH', INDEX_PATH . 'm=admin');
define('DAILI_PATH', INDEX_PATH . 'm=daili');
// js 路径
define('JS_PATH', base :: load_config('system', 'js_path'));
// css 路径
define('CSS_PATH', base :: load_config('system', 'css_path'));
// img 路径
define('IMG_PATH', base :: load_config('system', 'img_path'));

define('LANG', $_COOKIE['zh_choose']?:'s');

if (base :: load_config('system', 'gzip') && function_exists('ob_gzhandler')) {
	ob_start('ob_gzhandler');
} else {
	ob_start(); 
}

$zh_s = mb_str_split('皑蔼碍爱翱袄奥坝罢摆败颁办绊帮绑镑谤剥饱宝报鲍辈贝钡狈备惫绷笔毕毙币闭边编贬变辩辫标鳖别瘪濒滨宾摈饼并拨钵铂驳卜补财参蚕残惭惨灿苍舱仓沧厕侧册测层诧搀掺蝉馋谗缠铲产阐颤场尝长偿肠厂畅钞车彻尘沉陈衬撑称惩诚骋痴迟驰耻齿炽冲虫宠畴踌筹绸丑橱厨锄雏础储触处传疮闯创锤纯绰辞词赐聪葱囱从丛凑蹿窜错达带贷担单郸掸胆惮诞弹当挡党荡档捣岛祷导盗灯邓敌涤递缔颠点垫电淀钓调迭谍叠钉顶锭订丢东动栋冻斗犊独读赌镀锻断缎兑队对吨顿钝夺堕鹅额讹恶饿儿尔饵贰发罚阀珐矾钒烦范贩饭访纺飞诽废费纷坟奋愤粪丰枫锋风疯冯缝讽凤肤辐抚辅赋复负讣妇缚该钙盖干赶秆赣冈刚钢纲岗皋镐搁鸽阁铬个给龚宫巩贡钩沟构购够蛊顾剐关观馆惯贯广规硅归龟闺轨诡柜贵刽辊滚锅国过骇韩汉号阂鹤贺横轰鸿红后壶护沪户哗华画划话怀坏欢环还缓换唤痪焕涣黄谎挥辉毁贿秽会烩汇讳诲绘荤浑伙获货祸击机积饥讥鸡绩缉极辑级挤几蓟剂济计记际继纪夹荚颊贾钾价驾歼监坚笺间艰缄茧检碱硷拣捡简俭减荐槛鉴践贱见键舰剑饯渐溅涧将浆蒋桨奖讲酱胶浇骄娇搅铰矫侥脚饺缴绞轿较秸阶节茎鲸惊经颈静镜径痉竞净纠厩旧驹举据锯惧剧鹃绢杰洁结诫届紧锦仅谨进晋烬尽劲荆觉决诀绝钧军骏开凯颗壳课垦恳抠库裤夸块侩宽矿旷况亏岿窥馈溃扩阔蜡腊莱来赖蓝栏拦篮阑兰澜谰揽览懒缆烂滥捞劳涝乐镭垒类泪篱离里鲤礼丽厉励砾历沥隶俩联莲连镰怜涟帘敛脸链恋炼练粮凉两辆谅疗辽镣猎临邻鳞凛赁龄铃凌灵岭领馏刘龙聋咙笼垄拢陇楼娄搂篓芦卢颅庐炉掳卤虏鲁赂禄录陆驴吕铝侣屡缕虑滤绿峦挛孪滦乱抡轮伦仑沦纶论萝罗逻锣箩骡骆络妈玛码蚂马骂吗买麦卖迈脉瞒馒蛮满谩猫锚铆贸么霉没镁门闷们锰梦谜弥觅幂绵缅庙灭悯闽鸣铭谬谋亩钠纳难挠脑恼闹馁内拟腻撵捻酿鸟聂啮镊镍柠狞宁拧泞钮纽脓浓农疟诺欧鸥殴呕沤盘庞赔喷鹏骗飘频贫苹凭评泼颇扑铺朴谱栖凄脐齐骑岂启气弃讫牵扦钎铅迁签谦钱钳潜浅谴堑枪呛墙蔷强抢锹桥乔侨翘窍窃钦亲寝轻氢倾顷请庆琼穷趋区躯驱龋颧权劝却鹊确让饶扰绕热韧认纫荣绒软锐闰润洒萨鳃赛叁伞丧骚扫涩杀纱筛晒删闪陕赡缮伤赏烧绍赊摄慑设绅审婶肾渗声绳胜圣师狮湿诗尸时蚀实识驶势适释饰视试寿兽枢输书赎属术树竖数帅双谁税顺说硕烁丝饲耸怂颂讼诵擞苏诉肃虽随绥岁孙损笋缩琐锁獭挞抬态摊贪瘫滩坛谭谈叹汤烫涛绦讨腾誊锑题体屉条贴铁厅听烃铜统头秃图涂团颓蜕脱鸵驮驼椭洼袜弯湾顽万网韦违围为潍维苇伟伪纬谓卫温闻纹稳问瓮挝蜗涡窝卧呜钨乌污诬无芜吴坞雾务误锡牺袭习铣戏细虾辖峡侠狭厦吓锨鲜纤咸贤衔闲显险现献县馅羡宪线厢镶乡详响项萧嚣销晓啸蝎协挟携胁谐写泻谢锌衅兴汹锈绣虚嘘须许叙绪续轩悬选癣绚学勋询寻驯训讯逊压鸦鸭哑亚讶阉烟盐严颜阎艳厌砚彦谚验鸯杨扬疡阳痒养样瑶摇尧遥窑谣药爷页业叶医铱颐遗仪彝蚁艺亿忆义诣议谊译异绎荫阴银饮隐樱婴鹰应缨莹萤营荧蝇赢颖哟拥佣痈踊咏涌优忧邮铀犹游诱舆鱼渔娱与屿语吁御狱誉预驭鸳渊辕园员圆缘远愿约跃钥岳粤悦阅云郧匀陨运蕴酝晕韵杂灾载攒暂赞赃脏凿枣灶责择则泽贼赠扎札轧铡闸栅诈斋债毡盏斩辗崭栈战绽张涨帐账胀赵蛰辙锗这贞针侦诊镇阵挣睁狰争帧郑证织职执纸挚掷帜质滞钟终种肿众诌轴皱昼骤猪诸诛烛瞩嘱贮铸筑驻专砖转赚桩庄装妆壮状锥赘坠缀谆着浊兹资渍踪综总纵邹诅组钻');
$zh_t = mb_str_split('皚藹礙愛翺襖奧壩罷擺敗頒辦絆幫綁鎊謗剝飽寶報鮑輩貝鋇狽備憊繃筆畢斃幣閉邊編貶變辯辮標鼈別癟瀕濱賓擯餅並撥缽鉑駁蔔補財參蠶殘慚慘燦蒼艙倉滄廁側冊測層詫攙摻蟬饞讒纏鏟産闡顫場嘗長償腸廠暢鈔車徹塵沈陳襯撐稱懲誠騁癡遲馳恥齒熾沖蟲寵疇躊籌綢醜櫥廚鋤雛礎儲觸處傳瘡闖創錘純綽辭詞賜聰蔥囪從叢湊躥竄錯達帶貸擔單鄲撣膽憚誕彈當擋黨蕩檔搗島禱導盜燈鄧敵滌遞締顛點墊電澱釣調叠諜疊釘頂錠訂丟東動棟凍鬥犢獨讀賭鍍鍛斷緞兌隊對噸頓鈍奪墮鵝額訛惡餓兒爾餌貳發罰閥琺礬釩煩範販飯訪紡飛誹廢費紛墳奮憤糞豐楓鋒風瘋馮縫諷鳳膚輻撫輔賦複負訃婦縛該鈣蓋幹趕稈贛岡剛鋼綱崗臯鎬擱鴿閣鉻個給龔宮鞏貢鈎溝構購夠蠱顧剮關觀館慣貫廣規矽歸龜閨軌詭櫃貴劊輥滾鍋國過駭韓漢號閡鶴賀橫轟鴻紅後壺護滬戶嘩華畫劃話懷壞歡環還緩換喚瘓煥渙黃謊揮輝毀賄穢會燴彙諱誨繪葷渾夥獲貨禍擊機積饑譏雞績緝極輯級擠幾薊劑濟計記際繼紀夾莢頰賈鉀價駕殲監堅箋間艱緘繭檢堿鹼揀撿簡儉減薦檻鑒踐賤見鍵艦劍餞漸濺澗將漿蔣槳獎講醬膠澆驕嬌攪鉸矯僥腳餃繳絞轎較稭階節莖鯨驚經頸靜鏡徑痙競淨糾廄舊駒舉據鋸懼劇鵑絹傑潔結誡屆緊錦僅謹進晉燼盡勁荊覺決訣絕鈞軍駿開凱顆殼課墾懇摳庫褲誇塊儈寬礦曠況虧巋窺饋潰擴闊蠟臘萊來賴藍欄攔籃闌蘭瀾讕攬覽懶纜爛濫撈勞澇樂鐳壘類淚籬離裏鯉禮麗厲勵礫曆瀝隸倆聯蓮連鐮憐漣簾斂臉鏈戀煉練糧涼兩輛諒療遼鐐獵臨鄰鱗凜賃齡鈴淩靈嶺領餾劉龍聾嚨籠壟攏隴樓婁摟簍蘆盧顱廬爐擄鹵虜魯賂祿錄陸驢呂鋁侶屢縷慮濾綠巒攣孿灤亂掄輪倫侖淪綸論蘿羅邏鑼籮騾駱絡媽瑪碼螞馬罵嗎買麥賣邁脈瞞饅蠻滿謾貓錨鉚貿麽黴沒鎂門悶們錳夢謎彌覓冪綿緬廟滅憫閩鳴銘謬謀畝鈉納難撓腦惱鬧餒內擬膩攆撚釀鳥聶齧鑷鎳檸獰甯擰濘鈕紐膿濃農瘧諾歐鷗毆嘔漚盤龐賠噴鵬騙飄頻貧蘋憑評潑頗撲鋪樸譜棲淒臍齊騎豈啓氣棄訖牽扡釺鉛遷簽謙錢鉗潛淺譴塹槍嗆牆薔強搶鍬橋喬僑翹竅竊欽親寢輕氫傾頃請慶瓊窮趨區軀驅齲顴權勸卻鵲確讓饒擾繞熱韌認紉榮絨軟銳閏潤灑薩鰓賽三傘喪騷掃澀殺紗篩曬刪閃陝贍繕傷賞燒紹賒攝懾設紳審嬸腎滲聲繩勝聖師獅濕詩屍時蝕實識駛勢適釋飾視試壽獸樞輸書贖屬術樹豎數帥雙誰稅順說碩爍絲飼聳慫頌訟誦擻蘇訴肅雖隨綏歲孫損筍縮瑣鎖獺撻擡態攤貪癱灘壇譚談歎湯燙濤縧討騰謄銻題體屜條貼鐵廳聽烴銅統頭禿圖塗團頹蛻脫鴕馱駝橢窪襪彎灣頑萬網韋違圍爲濰維葦偉僞緯謂衛溫聞紋穩問甕撾蝸渦窩臥嗚鎢烏汙誣無蕪吳塢霧務誤錫犧襲習銑戲細蝦轄峽俠狹廈嚇鍁鮮纖鹹賢銜閑顯險現獻縣餡羨憲線廂鑲鄉詳響項蕭囂銷曉嘯蠍協挾攜脅諧寫瀉謝鋅釁興洶鏽繡虛噓須許敘緒續軒懸選癬絢學勳詢尋馴訓訊遜壓鴉鴨啞亞訝閹煙鹽嚴顔閻豔厭硯彥諺驗鴦楊揚瘍陽癢養樣瑤搖堯遙窯謠藥爺頁業葉醫銥頤遺儀彜蟻藝億憶義詣議誼譯異繹蔭陰銀飲隱櫻嬰鷹應纓瑩螢營熒蠅贏穎喲擁傭癰踴詠湧優憂郵鈾猶遊誘輿魚漁娛與嶼語籲禦獄譽預馭鴛淵轅園員圓緣遠願約躍鑰嶽粵悅閱雲鄖勻隕運蘊醞暈韻雜災載攢暫贊贓髒鑿棗竈責擇則澤賊贈紮劄軋鍘閘柵詐齋債氈盞斬輾嶄棧戰綻張漲帳賬脹趙蟄轍鍺這貞針偵診鎮陣掙睜猙爭幀鄭證織職執紙摯擲幟質滯鍾終種腫衆謅軸皺晝驟豬諸誅燭矚囑貯鑄築駐專磚轉賺樁莊裝妝壯狀錐贅墜綴諄著濁茲資漬蹤綜總縱鄒詛組鑽');

function mb_str_split($str){
 return preg_split('/(?<!^)(?!$)/u', $str);
}

$EnLib = [
	'云鼎国际'=>'Lottery',
	'幸运11选5'=>'Lucky 11 out of 5',
	"注册"=>"registered",
	"登录"=>"log in",
	"立即充值"=>"Top up now",
	"余额提现"=>"Balance withdrawal",
	"个人报表"=>"Personal report",
	"在线客服"=>"online service",
	"系列彩种"=>"Series color",
	"时时彩"=>"Shi Cai",
	"幸运28"=>"Lucky 28",
	"PK拾"=>"PK pick",
	"北京28"=>"Beijing Lucky 28",
	"20分钟一期"=>"20 minutes",
	"加拿大幸运28"=>"Canada Lucky 28",
	"3分半一期"=>"3 minutes and a half",
	"台湾幸运28"=>"Taiwan Lucky 28",
	"1分钟一期"=>"1 minute",
	"3分钟一期"=>"3 minutes",
	"新加坡幸运28"=>"Singapore Lucky 28",
	"5分钟一期"=>"5 minutes",
	"重庆时时彩"=>"Chongqing Shishicai",
	"玩法多样化"=>"Diversified gameplay",
	"新疆时时彩"=>"Xinjiang Shicai",
	"天津时时彩"=>"Tianjin Shishicai",
	"台湾时时彩"=>"Taiwan Times",
	"新加坡时时彩"=>"Singapore Times",
	"幸运时时彩"=>"Lucky",
	"幸运飞艇"=>"Lucky airship",
	"猜冠军"=>"Guess the champion",
	"北京赛车"=>"Beijing Racing",
	"台湾PK10"=>"Taiwan PK10",
	"新加坡PK10"=>"Singapore PK10",
	"幸运PK10"=>"Lucky PK10",
	"11选5"=>"11 out of 5",
	"快3"=>"Fast 3",
	"快乐十分"=>"Very happy",
	"广东"=>"Guangdong",
	"轻松中奖"=>"Winning easily",
	"山东"=>"Shandong",
	"台湾"=>"Taiwan",
	"新加坡"=>"Singapore",
	"幸运"=>"Lucky",
	"进入查看开奖"=>"Enter to view the draw",
	"台湾11选5"=>"11 of 5 in Taiwan",
	"猜骰子"=>"Guess the dice",
	"湖北快3"=>"Hubei Express 3",
	"安徽快3"=>"Anhui Express 3",
	"江苏快3"=>"Jiangsu Express 3",
	"贵州快3"=>"Guizhou Express 3",
	"广西快3"=>"Guangxi Express 3",
	"北京快3"=>"Beijing Express 3",
	"上海快3"=>"Shanghai Express 3",
	"河北快3"=>"Hebei Express 3",
	"甘肃快3"=>"Gansu Express 3",
	"幸运快3"=>"Lucky 3",
	"新加坡快3"=>"Singapore Express 3",
	"台湾快乐十分"=>"Happy Taiwan",
	"幸运等着你"=>"Lucky waiting for you",
	"新加坡快乐十分"=>"Happy Singapore",
	"幸运快乐十分"=>"Lucky and happy",
	"六合彩"=>"Mark Six",
	"快乐8"=>"Happy 8",
	"开发中"=>"In development",
	"keno彩"=>"keno color",
	"最盛行的港彩"=>"The most popular Hong Kong Lottery",
	"极速快乐8"=>"Happy Speed 8",
	"极速六合彩"=>"Speed Mark Six",
	"幸运六合彩"=>"Lucky Mark Six",
	"香港六合彩"=>"Hong Kong Mark Six",
	"首页"=>"Home","开奖"=>"Draw",
	"大厅"=>"hall","优惠"=>"Discount",
	"我的"=>"mine","最新公告"=>"Latest Announcement",
	"确定"=>"OK",
	"下一条"=>"next",
	"开发中！敬请期待！"=>"In development! Stay tuned!",
	"走势图"=>"Trend",
	"购彩大厅"=>'Hall',
	"疫情防控中，中国官方彩票休盘,开盘时间另行通知!祝大家玩得愉快！	"=>"During the epidemic prevention and control, China's official lottery is closed, and the opening time will be notified later! I wish you all a good time!",
    "最火爆的快彩"=>"The most popular Lottery",
    "简单易中奖"=>"Easy to win",
    "全部"=>"All",
    "已结束"=>"Ended",
    "首充好礼，送不停"=>"First gift, keep sending",
    "活动详情"=>"Activity details",
    "参与活动"=>"Apply",
    "帐户总额"=>"Total Account",
    "积分"=>"Points",
    "距升级还差"=>"Less than upgrade",
    "提现"=>"Withdraw",
    "收益"=>"Revenue",
    "盈亏"=>"Profit and Loss",
    "充值"=>"Recharge",
    "充值记录"=>"Recharge Record",
    "提现记录"=>"Withdrawal Record",
    "流水记录"=>"Records",
    "账户资料"=>"Information",
    "代理记录"=>"Agent Record",
    "我要分享"=>"Share",
    "设置"=>"Settings",
    "修改登录密码"=>"Modify Pass",
    "设置支付密码"=>"Mofidy Pay Pass",
    "联系客服"=>"Contact Customer",
    "退出登录"=>"Exit",
	"旧密码"=>"old password",
	"旧密码（初次设置无需填写）"=>"Old password (no need to fill in the initial setting)",
	"新密码"=>"new password",
	"确认密码"=>"confirm password",
	"验证码"=>"Captcha",
	"保存"=>"save",
	"请输入新密码"=>"Please enter a new password",
	"密码长度6-20位"=>"Password length 6-20 digits",
	"两次输入的密码不一致"=>"Passwords entered twice are inconsistent",
	"请输入验证码"=>"please enter verification code",
	"请输入4位验证码"=>"Please enter a 4-digit verification code",
	"支付宝"=>"Alipay",
	"微信"=>"WeChat",
	"银行卡"=>"Bank card",
	"支付宝收款码"=>"Alipay collection code",
	"微信收款码"=>"WeChat collection code",
	"最低充值金额"=>"Minimum recharge amount",
	"到帐账号"=>"Account number",
	"支付方式"=>"payment method",
	"银联快捷"=>"UnionPay Express",
	"支付宝扫码"=>"Alipay scan code",
	"手动支付"=>"Manual payment",
	"在线支付"=>"Online payments",
	"提交"=>"submit",
	"提示"=>"prompt",
	"请确认您的账号。最低充值金额为"=>"Please confirm your account. The minimum deposit amount is",
	"请按照上面提供的支付方式选择进行支付"=>"Please choose the payment method according to the payment method provided above",
	"支付时，请备注您支付的订单号提交审核。提交成功后，请等待到帐确认"=>"When paying, please note the order number you paid for submission and review. After successful submission, please wait for confirmation",
	"请输入金额"=>"Please enter the amount",
	"请输入正确的货币格式"=>"Please enter the correct currency format",
	"请输入备注内容"=>"Please enter the remarks",
	"备注内容最多50字"=>"Remarks up to 50 words",
	"请按照上面提供的支付方式选择进行支付。如您已经在提交订单之前支付，请等待确认"=>"Please choose the payment method according to the payment method provided above. If you have paid before submitting the order, please wait for confirmation",
	"支付时，请备注订单号后6位（红色数字）"=>"When paying, please note the last 6 digits of the order number (red numbers)",
	"订单号"=>"order number",
	"暂无数据"=>"No data",
	"退单"=>"Chargeback",
	"本页小计，输"=>"Subtotal on this page, lose",
	"赢"=>"win",
	"入账"=>"Credit",
	"期数"=>"Period",
	"单号"=>"Single number",
	"总计，输"=>"Total, lose",
	"至"=>"to",
	"帐户充值"=>'recharge',
	"结算减去投注"=>"Settlement minus bets",
	"充值总额-提现总额"=>"Total Deposit-Total Withdrawal",
	"新西兰幸运28"=>"New Zealand Lucky 28",
	"新加坡11选5"=>"Singapore 11 out of 5",
	"台湾快3"=>"Taiwan Express 3",
	"设置个性昵称"=>"Set a personalized nickname",
	"姓名，保存后不可修改"=>"Name, cannot be modified after saving",
	"姓名"=>"Name",
	"QQ，保存后不可修改"=>"QQ, cannot be modified after saving",
	"手机号，保存后不可修改"=>"Mobile phone number, cannot be modified after saving",
	"手机号"=>"phone number",
	"银行名称"=>"Bank name",
	"银行账号"=>"Bank Account",
	"提款银行名称，保存后不可修改"=>"Withdrawal bank name, cannot be modified after saving",
	"提款银行账号，保存后不可修改"=>"Withdrawal bank account number, cannot be modified after saving",
	"数字货币"=>"Digital currency",
	"数字货币，保存后不可修改"=>"Digital currency, cannot be modified after saving",
	"真实姓名需要与提款银行卡姓名对应，否则提款失败"=>"The real name needs to correspond to the name of the withdrawal bank card, otherwise the withdrawal fails",
	"除昵称外，其余资料项一经保存确认无法再次修改"=>"Except for the nickname, the rest of the data items cannot be modified once they are confirmed",
	"请输入Email"=>"Please enter Email",
	"请输入正确的Email"=>"Please enter the correct email",
	"请输入手机号"=>"Please enter phone number",
	"请输入正确的手机号"=>"please enter a valid phone number",
	"请输入昵称"=>"Please enter a nickname",
	"昵称限制为1-10个字"=>"Nickname is limited to 1-10 characters",
	"汇总"=>"Summary",
	"投注"=>"Betting",
	"账变"=>"Account change",
	"盈亏"=>"Profit and loss",
	"用户"=>"user",
	"推广"=>"Promote",
	"提交"=>"submit",
	"日期"=>"date",
	"余额"=>"Balance",
	"客损"=>"Customer loss",
	"下单"=>"Place an order",
	"月新"=>"New moon",
	"总计，输"=>"Total, lose",
	"期数"=>"Period",
	"退单"=>"Chargeback",
	"订单号"=>"order number",
	"红包"=>"Red envelope",
	"投注"=>"Betting",
	"资金变动"=>"Capital changes",
	"类型"=>"Types of",
	"账号"=>"account number",
	"可用余额"=>"Available Balance",
	"代理"=>"proxy",
	"用户"=>"user",
	"分享给好友，一起体验游戏的乐趣"=>"Share with friends and experience the fun of the game together",
	"点击复制连接"=>"Click Copy Connection",
	"推广连接"=>"Promotion connection",
	"扫描二维码下载APP"=>"Scan the QR code to download the APP",
	"单双"=>"Single and double",
	"大小"=>"size",
	"刷新"=>"Refresh",
	"号码"=>"number",
	"取值"=>"Value",
	"番"=>"Fan",
	"冠亚"=>"Guanya",
	"鼠"=>"mouse",
	"牛"=>"Cattle",
	"虎"=>"tiger",
	"兔"=>"rabbit",
	"龙"=>"Dragon",
	"蛇"=>"snake",
	"马"=>"horse",
	"羊"=>"sheep",
	"猴"=>"monkey",
	"鸡"=>"Chickens",
	"狗"=>"dog",
	"猪"=>"pig",
	"特码"=>"Special code",
	"总和"=>"sum",
	"大小"=>"size",
	"小"=>"small",
	"大"=>"Big",
	"单"=>"single",
	"双"=>"double",
	"和"=>"with",
    "单注"=>"Single note",
	"元起"=>"Yuanqi",
	"输入房间口令"=>"Enter room password",
	"温馨提示：金额限制"=>"Tips: Amount limit",
	"房间口令下正确"=>"Room password is correct",
    "初级"=>"primary",
	"中级"=>"intermediate",
	"高级"=>"advanced",
	"VIP房间"=>"VIP room",
	"代理公告"=>"Agent announcement",
	"番摊专场"=>"Fan booth",
	"在线下注"=>"Bet online",
	"金额限制"=>"Amount limit",
	"开奖记录"=>"Lottery record",
	"游戏规则"=>"game rules",
	"我的注单"=>"My bet slip",
	"我的资金流水"=>"My funds flow",
	"会员中心"=>"Member Centre",
	"修改资料"=>"Modify information",
	"昵称长度为30字符内!"=>"Nickname length is within 30 characters!",
	"昵称包含系统禁用的字符!"=>"The nickname contains characters prohibited by the system!",
	"昵称已被使用!"=>"Nickname has been used!",
	"保存成功!"=>"Saved successfully!",
	"保存失败!"=>"Save failed!",
	"修改头像"=>"Modify avatar",
	"修改密码"=>"change Password",
	"密码长度为6-20字符!"=>"Password length is 6-20 characters!",
	"旧密码验证错误!"=>"Old password verification error!",
	"修改成功，请重新登录..."=>"The modification is successful, please log in again...",
	"修改失败!"=>"fail to edit!",
	"修改资金密码"=>"Change fund password",
	"注单记录 - 游戏选择"=>"Note Record-Game Selection",
	"账户充值"=>"Account recharge",
	"输入的金额低于最低充值金额!请重新输入。"=>"The amount entered is lower than the minimum recharge amount! Please re-enter.",
	"订单创建成功!请等待确认。"=>"Order created successfully! Please wait for confirmation.",
	"订单创建失败!"=>"Order creation failed!",
	"很抱歉，您的上级代理已经失效，请联系客服"=>"Sorry, your superior agent has expired, please contact customer service",
	"很抱歉，您的上级代理尚未上传收款资料"=>"Sorry, your superior agent has not uploaded payment information",
	"充值二维码"=>"Recharge QR code",
	"设置"=>"Set up",
	"注单记录 - 游戏选择"=>"Note Record-Game Selection",
	"优惠"=>"Discount",
	"注单记录 - 游戏选择"=>"Note Record-Game Selection",
	"充值记录"=>"Recharge record",
	"输入的金额低于最低提现金额请重新输入。"=>"The amount entered is lower than the minimum cash withdrawal amount, please re-enter.",
	"您的姓名或银行卡信息不完整，请先修改资料填写完整!"=>"Your name or bank card information is incomplete, please modify the information and fill it in!",
	"您的姓名或数字货币未填写，请先修改资料填写完整"=>"Your name or digital currency is not filled in, please modify the information and fill it in completely",
	"资金密码错误!"=>"The fund password is wrong!",
	"提现金额超过您目前的资金，请重新填写!"=>"The cash withdrawal amount exceeds your current funds, please fill in again!",
	"单笔提现金额超过最大限制，请重新填写!"=>"The single withdrawal amount exceeds the maximum limit, please fill in again!",
	"打码量不足"=>"Insufficient coding",
	"提现申请成功!请耐心等待申请处理结果。"=>"The withdrawal application is successful! Please be patient and wait for the application processing result.",
	"提现操作失败!"=>"Withdrawal operation failed!",
	"提款记录"=>"Withdrawal records",
	"会员登录"=>"Member Login",
	"验证码错误或已过期!"=>"The verification code is wrong or has expired!",
	"登录成功，正在跳转..."=>"Successful login, jumping...",
	"账户或密码错误!"=>"Wrong account or password!",
	"注册会员"=>"registered member",
	"验证码错误或已过期!"=>"The verification code is wrong or has expired!",
	"姓名应该为2-5个字!"=>"The name should be 2-5 words!",
	"密码长度为6-20字符!"=>"Password length is 6-20 characters!",
	"用户名包含系统禁用的字符!"=>"The user name contains characters that are forbidden by the system!",
	"用户名已被注册!"=>"User name has been registered!",
	"请填写正确的邀请码!"=>"Please fill in the correct invitation code!",
	"新用户注册送金额"=>"New users register to send the amount",
	"注册成功，即将跳转至登录页面..."=>"Successful registration, will soon jump to the login page...",
	"注册失败!"=>"registration failed!",
	"客户端 APP"=>"Client APP",
	"验证码输入正确"=>"The verification code is entered correctly",
	"验证码错误或已过期!"=>"The verification code is wrong or has expired!",
	"尚未登录!"=>"Not logged in yet!",
	"昵称已被使用!"=>"Nickname has been used!",
	"昵称名包含系统禁用的字符!"=>"The nickname name contains characters that are forbidden by the system!",
	"昵称可用!"=>"Nickname available!",
	"用户名已被注册!"=>"User name has been registered!",
	"用户名包含系统禁用的字符!"=>"The user name contains characters that are forbidden by the system!",
	"用户名可用!"=>"Username is available!",
	"成功!"=>"success!",
	"您尚未登录!请登录后再操作。"=>"You are not logged in yet! Please log in before operating.",
	"抱歉，系统维护，暂停投注，请关注游戏公告。"=>"Sorry, system maintenance, betting is suspended, please pay attention to the game announcement.",
	"参数异常!"=>"The parameter is abnormal!",
	"请输入合规的金额!"=>"Please enter the amount of compliance!",
	"请联系客服"=>"Please contact customer service",
	"清选择房间进行下注"=>"Clear room selection for betting",
	"金额限制"=>"Amount limit",
	"元-"=>"yuan-",
	"该房间最低下注"=>"The minimum bet in this room",
	"当前投注玩法已经有玩家上庄，不能投注"=>"The current betting game has already been made by players",
	"已有玩家上庄，总投注金额已达到限制，不能投注"=>"Players have been listed, the total bet amount has reached the limit, can not bet",
	"已有玩家上庄，总投注金额不能超限，当前还能投注："=>"Existing players go to the bank and the total bet amount cannot exceed the limit. Currently, you can also bet:",
	"当期已经有玩家上庄，不能继续上庄"=>"Players have been listed in the current period.",
	"当前上庄金额至少"=>"The current amount is at least",
	"期 注单"=>"Period note",
	"...投注"=>"...Bet",
	"下注成功!祝君中奖!"=>"Betting is successful! Zhu Jun won the prize!",
	"下注失败!"=>"Betting failed!",
	"代理中心 - 投注"=>"Agent Center-Betting",
	"代理中心 - 账变"=>"Agent Center-Account Change",
	"代理中心 - 盈亏"=>"Agent Center-Profit and Loss",
	"代理中心 - 用户"=>"Agent Center-User",
	"代理中心 - 推广"=>"Agent Center-Promotion",
    "请输入房间口令"=>"Please enter the room password",
	"房间口令下正确"=>"Room password is correct",
	"温馨提示：金额限制"=>"Tips: Amount limit",
	"输入房间口令"=>"Enter room password",
	"元起"=>"Yuanqi",
	"单注"=>"Single note",
	"期"=>"period",
	"请登录"=>"please sign in",
	"封盘"=>"Seal",
	"历史开奖"=>"Historical draw",
	"摊路"=>"Road sharing",
	"加载中"=>"Loading",
	"更多"=>"More",
	"快捷下单"=>"Quick order",
	"选择玩法，点击金额购买"=>"Choose how to play, click on the amount to buy",
	"购买金额"=>"purchasing price",
	"购买"=>"buy",
	"重置"=>"Reset",
	"怎么玩"=>"How to play",
	"购买资源"=>"Purchase resources",
	"确定跟投吗"=>"Are you sure to follow",
	"玩家"=>"Player",
	"期数"=>"Period",
	"类别"=>"category",
	"金额"=>"Amount",
	"确 定"=>"Sure",
	"取 消"=>"Cancel",
	"开奖中"=>"Lottery",
	"已封盘"=>"Closed",
	"期正在投注中"=>"Betting",
	"期已经封盘"=>"Closed",
	"投注中"=>"Betting",
	"期即将开奖，现在封盘"=>"The draw is coming soon, now closed",
	"取后2位"=>"Take the last 2",
	"特码"=>"Special code",
	"1念2 2念3 3念4 4念1 1念4 2念1 3念2 4念3 1-2角 2-3角 3-4角 4-1角 正1 正2 正3 正4 番1 番2 番3 番4 单 双"=>"1idea2 2idea3 3idea4 4idea1 1idea4 2idea1 3idea2 4idea3 1-2angle 2-3angle 3-4angle 4-1angle positive1 positive2 positive3 positive4 Fan1 Fan2 Fan3 Fan4 Single Double",
	"1念2 2念3 3念4 4念1 1念4 2念1 3念2 4念3 1-2角 2-3角 3-4角 4-1角 正1 正2 正3 正4 番1 番2 番3 番4 单 双 冠亚大@冠亚 冠亚小@冠亚 冠亚单@冠亚 冠亚双@冠亚 3@冠亚 4@冠亚 5@冠亚 6@冠亚 7@冠亚 8@冠亚 9@冠亚 10@冠亚 11@冠亚 12@冠亚 13@冠亚 14@冠亚 15@冠亚 16@冠亚 17@冠亚 18@冠亚 19@冠亚 大@冠军 小@冠军 单@冠军 双@冠军 龙@冠军 虎@冠军 大@亚军 小@亚军 单@亚军 双@亚军 龙@亚军 虎@亚军 大@第三名 小@第三名 单@第三名 双@第三名 龙@第三名 虎@第三名 大@第四名 小@第四名 单@第四名 双@第四名 龙@第四名 虎@第四名 大@第五名 小@第五名 单@第五名 双@第五名 龙@第五名 虎@第五名 大@第六名 小@第六名 单@第六名 双@第六名 大@第七名 小@第七名 单@第七名 双@第七名 大@第八名 小@第八名 单@第八名 双@第八名 大@第九名 小@第九名 单@第九名 双@第九名 大@第十名 小@第十名 单@第十名 双@第十名 1@冠军 2@冠军 3@冠军 4@冠军 5@冠军 6@冠军 7@冠军 8@冠军 9@冠军 10@冠军 1@亚军 2@亚军 3@亚军 4@亚军 5@亚军 6@亚军 7@亚军 8@亚军 9@亚军 10@亚军 1@第三名 2@第三名 3@第三名 4@第三名 5@第三名 6@第三名 7@第三名 8@第三名 9@第三名 10@第三名 1@第四名 2@第四名 3@第四名 4@第四名 5@第四名 6@第四名 7@第四名 8@第四名 9@第四名 10@第四名 1@第五名 2@第五名 3@第五名 4@第五名 5@第五名 6@第五名 7@第五名 8@第五名 9@第五名 10@第五名 1@第六名 2@第六名 3@第六名 4@第六名 5@第六名 6@第六名 7@第六名 8@第六名 9@第六名 10@第六名 1@第七名 2@第七名 3@第七名 4@第七名 5@第七名 6@第七名 7@第七名 8@第七名 9@第七名 10@第七名 1@第八名 2@第八名 3@第八名 4@第八名 5@第八名 6@第八名 7@第八名 8@第八名 9@第八名 10@第八名 1@第九名 2@第九名 3@第九名 4@第九名 5@第九名 6@第九名 7@第九名 8@第九名 9@第九名 10@第九名 1@第十名 2@第十名 3@第十名 4@第十名 5@第十名 6@第十名 7@第十名 8@第十名 9@第十名 10@第十名"=>"1idea2 2idea3 3idea4 4idea1 1idea4 2idea1 3idea2 4idea3 1-2angle 2-3angle 3-4angle 4-1angle positive1 positive2 positive3 positive4 Fan1 Fan2 Fan3 Fan4 Single Double GuanYaBig@GuanYa GuanYaSmall@GuanYa GuanYasingle@GuanYa GuanYadouble@GuanYa 3@GuanYa 4@GuanYa 5@GuanYa 6@GuanYa 7@GuanYa 8@GuanYa 9@GuanYa 10@GuanYa 11@GuanYa 12@GuanYa 13@GuanYa 14@GuanYa 15@GuanYa 16@GuanYa 17@GuanYa 18@GuanYa 19@GuanYa Big@Champion Small@Champion single@Championdouble@Champion Dragon@Champion tiger@Champion Big@runner-up Small@runner-up single@runner-updouble@runner-up Dragon@runner-up tiger@runner-up Big@third Small@third single@thirddouble@third Dragon@third tiger@third Big@Forth Small@Forth single@Forthdouble@Forth Dragon@Forth tiger@Forth Big@Fivth Small@Fivth single@Fivthdouble@Fivth Dragon@Fivth tiger@Fivth Big@Sixth Small@Sixth single@Sixthdouble@Sixth Big@Seventh Small@Seventh single@Seventhdouble@Seventh Big@eighth Small@eighth single@eighthdouble@eighth Big@ninth Small@ninth single@ninthdouble@ninth Big@tenth Small@tenth single@tenthdouble@tenth 1@Champion 2@Champion 3@Champion 4@Champion 5@Champion 6@Champion 7@Champion 8@Champion 9@Champion 10@Champion 1@runner-up 2@runner-up 3@runner-up 4@runner-up 5@runner-up 6@runner-up 7@runner-up 8@runner-up 9@runner-up 10@runner-up 1@third 2@third 3@third 4@third 5@third 6@third 7@third 8@third 9@third 10@third 1@Forth 2@Forth 3@Forth 4@Forth 5@Forth 6@Forth 7@Forth 8@Forth 9@Forth 10@Forth 1@Fivth 2@Fivth 3@Fivth 4@Fivth 5@Fivth 6@Fivth 7@Fivth 8@Fivth 9@Fivth 10@Fivth 1@Sixth 2@Sixth 3@Sixth 4@Sixth 5@Sixth 6@Sixth 7@Sixth 8@Sixth 9@Sixth 10@Sixth 1@Seventh 2@Seventh 3@Seventh 4@Seventh 5@Seventh 6@Seventh 7@Seventh 8@Seventh 9@Seventh 10@Seventh 1@eighth 2@eighth 3@eighth 4@eighth 5@eighth 6@eighth 7@eighth 8@eighth 9@eighth 10@eighth 1@ninth 2@ninth 3@ninth 4@ninth 5@ninth 6@ninth 7@ninth 8@ninth 9@ninth 10@ninth 1@tenth 2@tenth 3@tenth 4@tenth 5@tenth 6@tenth 7@tenth 8@tenth 9@tenth 10@tenthGuanYaBig@GuanYa GuanYaSmall@GuanYa GuanYasingle@GuanYa GuanYadouble@GuanYa 3@GuanYa 4@GuanYa 5@GuanYa 6@GuanYa 7@GuanYa 8@GuanYa 9@GuanYa 10@GuanYa 11@GuanYa 12@GuanYa 13@GuanYa 14@GuanYa 15@GuanYa 16@GuanYa 17@GuanYa 18@GuanYa 19@GuanYa Big@Champion Small@Champion single@Championdouble@Champion Dragon@Champion tiger@Champion Big@runner-up Small@runner-up single@runner-updouble@runner-up Dragon@runner-up tiger@runner-up Big@third Small@third single@thirddouble@third Dragon@third tiger@third Big@Forth Small@Forth single@Forthdouble@Forth Dragon@Forth tiger@Forth Big@Fivth Small@Fivth single@Fivthdouble@Fivth Dragon@Fivth tiger@Fivth Big@Sixth Small@Sixth single@Sixthdouble@Sixth Big@Seventh Small@Seventh single@Seventhdouble@Seventh Big@eighth Small@eighth single@eighthdouble@eighth Big@ninth Small@ninth single@ninthdouble@ninth Big@tenth Small@tenth single@tenthdouble@tenth 1@Champion 2@Champion 3@Champion 4@Champion 5@Champion 6@Champion 7@Champion 8@Champion 9@Champion 10@Champion 1@runner-up 2@runner-up 3@runner-up 4@runner-up 5@runner-up 6@runner-up 7@runner-up 8@runner-up 9@runner-up 10@runner-up 1@third 2@third 3@third 4@third 5@third 6@third 7@third 8@third 9@third 10@third 1@Forth 2@Forth 3@Forth 4@Forth 5@Forth 6@Forth 7@Forth 8@Forth 9@Forth 10@Forth 1@Fivth 2@Fivth 3@Fivth 4@Fivth 5@Fivth 6@Fivth 7@Fivth 8@Fivth 9@Fivth 10@Fivth 1@Sixth 2@Sixth 3@Sixth 4@Sixth 5@Sixth 6@Sixth 7@Sixth 8@Sixth 9@Sixth 10@Sixth 1@Seventh 2@Seventh 3@Seventh 4@Seventh 5@Seventh 6@Seventh 7@Seventh 8@Seventh 9@Seventh 10@Seventh 1@eighth 2@eighth 3@eighth 4@eighth 5@eighth 6@eighth 7@eighth 8@eighth 9@eighth 10@eighth 1@ninth 2@ninth 3@ninth 4@ninth 5@ninth 6@ninth 7@ninth 8@ninth 9@ninth 10@ninth 1@tenth 2@tenth 3@tenth 4@tenth 5@tenth 6@tenth 7@tenth 8@tenth 9@tenth 10@tenth",
	"大@第一球 小@第一球 单@第一球 双@第一球 大@第二球 小@第二球 单@第二球 双@第二球 大@第三球 小@第三球 单@第三球 双@第三球 大@第四球 小@第四球 单@第四球 双@第四球 大@第五球 小@第五球 单@第五球 双@第五球 大@第六球 小@第六球 单@第六球 双@第六球 大@第七球 小@第七球 单@第七球 双@第七球 大@第八球 小@第八球 单@第八球 双@第八球 1@第一球 2@第一球 3@第一球 4@第一球 5@第一球 6@第一球 7@第一球 8@第一球 9@第一球 10@第一球 1@第二球 2@第二球 3@第二球 4@第二球 5@第二球 6@第二球 7@第二球 8@第二球 9@第二球 10@第二球 1@第三球 2@第三球 3@第三球 4@第三球 5@第三球 6@第三球 7@第三球 8@第三球 9@第三球 10@第三球 1@第四球 2@第四球 3@第四球 4@第四球 5@第四球 6@第四球 7@第四球 8@第四球 9@第四球 10@第四球 1@第五球 2@第五球 3@第五球 4@第五球 5@第五球 6@第五球 7@第五球 8@第五球 9@第五球 10@第五球 1@第六球 2@第六球 3@第六球 4@第六球 5@第六球 6@第六球 7@第六球 8@第六球 9@第六球 10@第六球 1@第七球 2@第七球 3@第七球 4@第七球 5@第七球 6@第七球 7@第七球 8@第七球 9@第七球 10@第七球 1@第八球 2@第八球 3@第八球 4@第八球 5@第八球 6@第八球 7@第八球 8@第八球 9@第八球 10@第八球 11@第一球 1@第一球 13@第一球 14@第一球 15@第一球 16@第一球 17@第一球 18@第一球 19@第一球 20@第一球 11@第二球 1@第二球 13@第二球 14@第二球 15@第二球 16@第二球 17@第二球 18@第二球 19@第二球 20@第二球 11@第三球 1@第三球 13@第三球 14@第三球 15@第三球 16@第三球 17@第三球 18@第三球 19@第三球 20@第三球 11@第四球 1@第四球 13@第四球 14@第四球 15@第四球 16@第四球 17@第四球 18@第四球 19@第四球 20@第四球 11@第五球 1@第五球 13@第五球 14@第五球 15@第五球 16@第五球 17@第五球 18@第五球 19@第五球 20@第五球 11@第六球 1@第六球 13@第六球 14@第六球 15@第六球 16@第六球 17@第六球 18@第六球 19@第六球 20@第六球 11@第七球 1@第七球 13@第七球 14@第七球 15@第七球 16@第七球 17@第七球 18@第七球 19@第七球 20@第七球 11@第八球 1@第八球 13@第八球 14@第八球 15@第八球 16@第八球 17@第八球 18@第八球 19@第八球 20@第八球"=>"Big@firstBall small@firstBall single@firstBall double@firstBall big@secondBall small@secondBall single@secondBall double@secondBall big@thirdBall small@thirdBall single@thirdBall double@thirdBall big@fourthBall small@fourthBall single@fourthBall double@fourthBall big@fifthBall small@fifthBall single@fifthBall double@fifthBall big@sixthBall small@sixthBall single@sixthBall double@sixthBall big@seventhBall big Small@seventhBall single@seventhBall double@seventhBall big@eighthBall small@eighthBall single@eighthBall double@eighthBall 1@firstBall 2@firstBall 3@firstBall 4@firstBall 5@firstBall 6@firstBall 7@firstBall 8@firstBall 9@firstBall 10@firstBall 1@secondBall 2@secondBall 3@secondBall 4@secondBall 5@secondBall 6@secondBall 7@secondBall 8@secondBall 9@secondBall 10@secondBall 1@thirdBall 2@thirdBall 3@thirdBall 4@thirdBall 5@thirdBall 6@thirdBall 7@thirdBall 8@thirdBall 9@thirdBall 10@thirdBall 1@fourthBall 2@fourthBall 3@fourthBall 4@fourthBall 5@fourthBall 6@fourthBall 7@fourthBall 8@fourthBall 9@fourthBall 10@fourthBall 1@fifthBall 2@fifthBall 3@fifthBall 4@fifthBall 5@fifthBall 6@fifthBall 7@fifthBall 8@fifthBall 9@fifthBall 10@fifthBall 1@sixthBall 2@sixthBall 3@sixthBall 4@sixthBall 5@sixthBall 6@sixthBall 7@sixthBall 8@sixthBall 9@sixthBall 10@sixthBall 1@seventhBall 2@seventhBall 3@seventhBall 4@seventhBall 5@seventhBall 6@seventhBall 7@seventhBall 8@seventhBall 9@seventhBall 10@seventhBall 1@eighthBall 2@eighthBall 3@eighthBall 4@eighthBall 5@eighthBall 6@eighthBall 7@eighthBall 8@eighthBall 9@eighthBall 10@eighthBall 11@firstBall 1@firstBall 13@firstBall 14@firstBall 15@firstBall 16@firstBall 17@firstBall 18@firstBall 19@firstBall 20@firstBall 11@secondBall 1@secondBall 13@secondBall 14@secondBall 15@secondBall 16@secondBall 17@secondBall 18@secondBall 19@secondBall 20@secondBall 11@thirdBall 1@thirdBall 13@thirdBall 14@thirdBall 14 15@thirdBall 16@thirdBall 17@thirdBall 18@thirdBall 19@thirdBall 20@thirdBall 11@fourthBall 1@fourthBall 13@fourthBall 14@fourthBall 15@fourthBall 16@fourthBall 17@fourthBall 18@fourthBall 19@fourthBall 20@fourthBall 11@fifthBall 1@fifthBall 13@fifthBall 14@fifthBall 15@fifthBall 16@fifthBall 17@fifthBall 18@fifthBall 19@fifthBall 20@fifthBall 11@sixthBall 1@sixthBall 13@sixthBall 14@sixthBall 15@sixthBall 16@sixthBall 17@sixthBall 18@sixthBall 19@sixthBall 20@sixthBall 11@seventhBall 1@seventhBall 13@seventhBall 14@seventhBall 15@seventhBall 16@seventhBall 16@seventhBall 17@seventhBall 18@seventhBall 19@seventhBall 20@seventhBall 11@eighthBall 1@eighthBall 13@eighthBall 14@eighthBall 15@eighthBall 16@eighthBall 17@eighthBall 18@eighthBall 19@eighthBall 20@eighthBall",
	"1@第一球 2@第一球 3@第一球 4@第一球 5@第一球 6@第一球 7@第一球 8@第一球 9@第一球 11@第二球 1@第二球 2@第二球 3@第二球 4@第二球 5@第二球 6@第二球 7@第二球 8@第二球 9@第二球 11@第三球 1@第三球 2@第三球 3@第三球 4@第三球 5@第三球 6@第三球 7@第三球 8@第三球 9@第三球 11@第四球 1@第四球 2@第四球 3@第四球 4@第四球 5@第四球 6@第四球 7@第四球 8@第四球 9@第四球 11@第五球 1@第五球 2@第五球 3@第五球 4@第五球 5@第五球 6@第五球 7@第五球 8@第五球 9@第五球"=>"1@firstBall 2@firstBall 3@firstBall 4@firstBall 5@firstBall 6@firstBall 7@firstBall 8@firstBall 9@firstBall 11@secondBall 1@secondBall 2@secondBall 3@secondBall 4@secondBall 5@secondBall 6@secondBall 7@secondBall 8@secondBall 9@secondBall 11@thirdBall 1@thirdBall 2@thirdBall 3@thirdBall 4@thirdBall 5@thirdBall 6@thirdBall 7@thirdBall 8@thirdBall 9@thirdBall 11@fourthBall 1@fourthBall 2@fourthBall 3@fourthBall 4@fourthBall 5@fourthBall 6@fourthBall 7@fourthBall 8@fourthBall 9@fourthBall 11@fifthBall 1@fifthBall 2@fifthBall 3@fifthBall 4@fifthBall 5@fifthBall 6@fifthBall 7@fifthBall 8@fifthBall 9@fifthBall 9@fifthBall",
	"总和大@总和 总和小@总和 总和单@总和 总和双@总和 龙@总和 虎@总和 和@总和 大@第一球 小@第一球 单@第一球 双@第一球 大@第二球 小@第二球 单@第二球 双@第二球 大@第三球 小@第三球 单@第三球 双@第三球 大@第四球 小@第四球 单@第四球 双@第四球 大@第五球 小@第五球 单@第五球 双@第五球 0@第一球 1@第一球 2@第一球 3@第一球 4@第一球 5@第一球 6@第一球 7@第一球 8@第一球 9@第一球 0@第二球 1@第二球 2@第二球 3@第二球 4@第二球 5@第二球 6@第二球 7@第二球 8@第二球 9@第二球 0@第三球 1@第三球 2@第三球 3@第三球 4@第三球 5@第三球 6@第三球 7@第三球 8@第三球 9@第三球 0@第四球 1@第四球 2@第四球 3@第四球 4@第四球 5@第四球 6@第四球 7@第四球 8@第四球 9@第四球 0@第五球 1@第五球 2@第五球 3@第五球 4@第五球 5@第五球 6@第五球 7@第五球 8@第五球 9@第五球 豹子@前三 顺子@前三 对子@前三 半顺@前三 杂六@前三 豹子@中三 顺子@中三 对子@中三 半顺@中三 杂六@中三 豹子@后三 顺子@后三 对子@后三 半顺@后三 杂六@后三"=>"Sumbig@Sum Sumsmall@Sum Sumsingle@Sum Sumdouble@total dragon@Sum tiger@Sum sum@Sum big@firstBall small@firstBall single@firstBall double@firstBall big@secondBall small@secondBall single@secondBall double@secondBall big@thirdBall small@thirdBall single@thirdBall double@thirdBall big@fourthBall small@fourthBall double@fourthBall double@fourthBall double@fourthBall double@fourthBall double@fourthBall big@fifthBall big Small@fifthBall single@fifthBall double@fifthBall 0@firstBall 1@firstBall 2@firstBall 3@firstBall 4@firstBall 5@firstBall 6@firstBall 7@firstBall 8@firstBall 9@firstBall 0@secondBall 1@secondBall 2@secondBall 3@secondBall 4@secondBall 5@secondBall 6@secondBall 7@secondBall 8@secondBall 9@secondBall 0@thirdBall 1@thirdBall 2@thirdBall 3@thirdBall 4@thirdBall 5@thirdBall 6@thirdBall 7@thirdBall 8@thirdBall 9@thirdBall 0@fourthBall 1@fourthBall 2@fourthBall 3@fourthBall 4@fourthBall 5@fourthBall 6@fourthBall 7@fourthBall 8@fourthBall 9@fourthBall 0@fifthBall 1@fifthBall 2@fifthBall 3@fifthBall 4@fifthBall 5@fifthBall 6@fifthBall 7@fifthBall 8@fifthBall 9@fifthBall leopard@firstThree pairs@firstThree pairsHalfSmooth@firstThree hybridSix@firstThree Leopards@middleThree pairs@middleThree Halfpairs@middleThree HalfSmooth@middleThree mixedSix@middleThree HalfSmooth@backThree HalfShun@backThree HalfShun@backThree six@lastThree",
	"大 小 单 双 大单 大双 小单 小双 极大 极小 豹子 红波 绿波 蓝波"=>"Big small single double bigSingle bigDouble smallSingle smallDouble great Littlesmall leopard redWave greenWave blueWave",
	"大 小 单 双 大单 大双 小单 小双 豹子"=>"Big small single double bigSingle bigDouble smallSingle smallDouble leopard",
	"大@第一球 小@第一球 单@第一球 双@第一球 大@第二球 小@第二球 单@第二球 双@第二球 大@第三球 小@第三球 单@第三球 双@第三球 大@第四球 小@第四球 单@第四球 双@第四球 大@第五球 小@第五球 单@第五球 双@第五球 大@第六球 小@第六球 单@第六球 双@第六球 大@第七球 小@第七球 单@第七球 双@第七球 大@第八球 小@第八球 单@第八球 双@第八球 1@第一球 2@第一球 3@第一球 4@第一球 5@第一球 6@第一球 7@第一球 8@第一球 9@第一球 10@第一球 1@第二球 2@第二球 3@第二球 4@第二球 5@第二球 6@第二球 7@第二球 8@第二球 9@第二球 10@第二球 1@第三球 2@第三球 3@第三球 4@第三球 5@第三球 6@第三球 7@第三球 8@第三球 9@第三球 10@第三球 1@第四球 2@第四球 3@第四球 4@第四球 5@第四球 6@第四球 7@第四球 8@第四球 9@第四球 10@第四球 1@第五球 2@第五球 3@第五球 4@第五球 5@第五球 6@第五球 7@第五球 8@第五球 9@第五球 10@第五球 1@第六球 2@第六球 3@第六球 4@第六球 5@第六球 6@第六球 7@第六球 8@第六球 9@第六球 10@第六球 1@第七球 2@第七球 3@第七球 4@第七球 5@第七球 6@第七球 7@第七球 8@第七球 9@第七球 10@第七球 1@第八球 2@第八球 3@第八球 4@第八球 5@第八球 6@第八球 7@第八球 8@第八球 9@第八球 10@第八球"=>"double@firstBall big@secondBall small@secondBall single@secondBall double@double@secondBall big@thirdBall small@thirdBall single@thirdBall single@thirdBall double@thirdBall double@thirdBall big@fourthBall big@fourthBall small@fourthBall single@fourthBall single@fourthBall double@fourthBall double@fourthBall big@fifthBall small@fifthBall single@fifthBall double@fifthBall double@fifthBall big@sixthBall small@sixthBall small@sixthBall small@sixthBall small@sixthBall small@sixthBall small@sixthBall small@fourthBall single@fourthBall single@fourthBall double@double@double@fifthBall big@sixthBall single@sixth double@sixth Big@seventhBall small@seventhBall single@seventhBall single@seventhBall double@seventhBall big@eighthBall small@eighthBall single@eighthBall double@eighthBall 1@firstBall 2@firstBall 3@firstBall 3@firstBall 4@firstBall 5@firstBall 6@firstBall 6@firstBall 7@firstBall 8@firstBall 8@firstBall 9@firstBall 10@firstBall 1@secondBall 2@secondBall 3@secondBall 4@secondBall 4@secondBall 5@secondBall 6@secondBall 7@secondBall 8@8@8@8@8@8@secondBall 8@8@8@8@secondBall 8@secondBall 8@secondBall 8@secondBall 8@secondBall 8@secondBall second 9@second 10@secondBall 1@thirdBall 2@thirdBall 3@thirdBall 3@thirdBall 4@thirdBall 5@thirdBall 6@thirdBall 6@thirdBall 7@thirdBall 8@thirdBall 9@thirdBall 10@thirdBall 10@thirdBall 1@fourthBall 2@fourthBall 3@fourthBall 4@fourthBall 4@fourthBall 5@fourthBall 5@fourthBall 6@fourthBall 7@fourthBall 8@fourthBall 9@fourthBall 10@fourthBall 1@fifthBall 2@fifthBall 3@fifthBall 9@thirdBall 4@fifthBall 4@fifthBall 4@fifthBall 4@fifthBall 4@fifthBall 4@fifthBall 4@fifthBall 4@fifthBall 4@fourthBall 4@fourthBall 5 5@5th 6@5th 7@5th 8@eighthBall 9@fifthBall 9@fifthBall 10@fifthBall 1@sixthBall 2@sixthBall 3@sixthBall 4@sixthBall 4@sixthBall 5@sixthBall 6@sixthBall 7@sixthBall 8@sixthBall 9@sixthBall 9@sixthBall 10@sixthBall 10@sixthBall 1@seventhBall 2@seventhBall 2@seventhBall 3@seventhBall 4@seventhBall 4@seventhBall 5@seventhBall 6@seventhBall 7@seventhBall 8@seventhBall 9@seventhBall 10@seventhBall 10@seventhBall 1@eighthBall 2@8@eighthBall 2@eighthBall 2@eighthBall 3@seventhBall 9@seventhBall 10@seventhBall 1@eighthBall 2@eighthBall 2@eighthBall 3@8th 4@8th 5@8th,6@8th 7@8th 8@8th 9@8th 10@8th",
	"大@第一球 小@第一球 单@第一球 双@第一球 大@第二球 小@第二球 单@第二球 双@第二球 大@第三球 小@第三球 单@第三球 双@第三球 大@第四球 小@第四球 单@第四球 双@第四球 大@第五球 小@第五球 单@第五球 双@第五球 大@第六球 小@第六球 单@第六球 双@第六球 大@单特码 小@单特码 单@单特码 双@单特码 1@单特码 2@单特码 3@单特码 4@单特码 5@单特码 6@单特码 7@单特码 8@单特码 9@单特码 10@单特码 11@单特码 1@单特码 13@单特码 14@单特码 15@单特码 16@单特码 17@单特码 18@单特码 19@单特码 20@单特码 21@单特码 22@单特码 23@单特码 24@单特码 25@单特码 26@单特码 27@单特码 28@单特码 29@单特码 30@单特码 31@单特码 32@单特码 33@单特码 34@单特码 35@单特码 36@单特码 37@单特码 38@单特码 39@单特码 40@单特码 41@单特码 42@单特码 43@单特码 44@单特码 45@单特码 46@单特码 47@单特码 48@单特码 49@单特码 牛@特码 鸡@特码 羊@特码 马@特码 蛇@特码 鼠@特码 虎@特码 兔@特码 龙@特码 猴@特码 猪@特码 狗@特码"=>"Big@1stBall small@1stBall single@1stBall double@1stBall big@2ndBall small@2ndBall single@2ndBall double@2ndBall big@ ThirdBall small@thirdBall single@thirdBall double@thirdBall big@fourthBall small@fourthBall single@fourthBall double@fourthBall big@fifthBall small@fifthBall single@fifthBall double@fifthBall big@sixthBall small@sixthBall single@sixthBall double@sixthBall big@SingleCode Small@SingleCode Single@SingleCode Double@SingleCode 1@SingleCode 2@SingleCode 3@SingleCode 4@SingleCode 5@SingleCode 6@ Single special code 7@SingleCode 8@SingleCode 9@SingleCode 10@SingleCode 11@SingleCode 1@SingleCode 13@SingleCode 14@SingleCode 15@SingleCode 16@SingleCode 17@SingleCode 18@SingleCode 19@SingleCode 20@SingleCode 21@SingleCode 22@SingleCode 23@SingleCode 24@SingleCode 25@SingleCode 26@SingleCode 27@SingleCode 28@SingleCode 29@SingleCode 30@SingleCode 31@ Single special code 32@SingleCode 33@SingleCode 34@SingleCode 35@SingleCode 36@SingleCode 37@SingleCode 38@SingleCode 39@SingleCode 40@SingleCode 41@SingleCode 42@SingleCode 43@SingleCode 44@SingleCode 45@SingleCode 46@SingleCode 47@SingleCode 48@SingleCode 49@SingleCode cattle@SpecialCode chicken@SpecialCode sheep@SpecialCode horse@SpecialCode snake@SpecialCode rat@SpecialCode Tiger@SpecialCode rabbit@SpecialCode dragon@SpecialCode monkey@SpecialCode pig@SpecialCode dog@SpecialCode",
	"大 小 单 双 大单 大双 小单 小双 家禽 野兽 合数大 合数小 合数单 合数双 红波 绿波 蓝波 红大 红小 红单 红双 蓝大 蓝小 蓝单 蓝双 绿大 绿小 绿单 绿双 0头 1头 2头 3头 4头 0尾 1尾 2尾 3尾 4尾 5尾 6尾 7尾 8尾 9尾 牛 鸡 羊 猪 狗 马 蛇 鼠 虎 兔 龙 猴"=>"Big small Single double bigSingle big double smallSingle smallDouble poultry beast largeComposite smallComposite compositeSingle compositeDouble redWave greenWave blueWave redBig redSmall redSingle redDouble blueBig blueSmall blueSingle blueDouble greenBig greenSmall greenSingle greenDouble 0head 1head 2heads 3heads 4heads 0tails 1tails 2tails 3tails 4tails 5tails 6tails 7tails 8tails 9tails cows chickens sheep pigs dogs horses snakes rats tigers rabbits monkey",
	"参数错误"=>"Parameter error",
	"前往登录"=>"Go to login",
	"服务器请求失败，请重试"=>"Server request failed, please try again",
	"取消"=>"cancel",
	"请不要违规下注，最多7注"=>"Please don't bet against the rules, up to 7 bets",
	"选择玩法，点击金额投注"=>"Choose how to play, click the amount to bet",
	"尚未取得期数"=>"Number of periods not yet obtained",
	"请选择玩法"=>"Please choose how to play",
	"投注金额在"=>"The bet amount is",
	"之间"=>"between",
	"总金额"=>"total amount",
	"确定投注"=>"Sure bet",
	"混合"=>"mixing",
	"大单"=>"BigSignle",
	"大双"=>"BigDouble",
	"小单"=>"SmallSignle",
	"小双"=>"LittleDouble",
	"极大"=>"great",
	"极小"=>"Minimal",
	"豹子"=>"leopard",
	"红波"=>"RedWave",
	"绿波"=>"GreenWave",
	"蓝波"=>"BlueWave",
    "返回"=>'return',
	"两面"=>"Two sides",
	"1-5名"=>"Top Five",
	"前中后"=>"In front",
	"第一球"=>"First ball",
	"第二球"=>"Second ball",
	"第三球"=>"Third ball",
	"第四球"=>"Fourth goal",
	"第五球"=>"Fifth ball",
	"前三"=>"Top3",
	"中三"=>"Center3",
	"后三"=>"Last3",
	"顺子"=>"Straight",
	"对子"=>"Pair",
	"半顺"=>"Semi-shun",
	"杂六"=>"Miscellaneous",
	"冠、亚和"=>"Guan, Yahe",
	"冠亚和"=>"Guan, Yahe",
	"冠军"=>"champion",
	"亚军"=>"runner up",
	"第三名"=>"Third place",
	"第四名"=>"fourth place",
	"第五名"=>"the fifth place",
	"第六名"=>"Sixth place",
	"第七名"=>"Seventh place",
	"第八名"=>"eight place",
	"第九名"=>"Ninth",
	"第十名"=>"Tenth place",
	"特肖"=>'Teshaw',
	"已选择"=>"chosen",
    "注玩法"=>"Note play",
	"总充值"=>"Total recharge",
    "总提现"=>"Total withdrawal",
	"今天"=>'Today',
	'最近7天'=>'Last 7 days',
	'最近30天'=>'Last 30 days',
	'最近90天'=>'Last 90 days',
	'尊敬的会员：<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本平台的收款银行账号可能随时发生变化，请您务必每次充值前联系在线客服获取时时收款账号！若因您的错误转到错误账号，本站有权不为您的该笔转账上分！请您理解支持，祝您生活愉快，中奖多多！！'=>'Dear members: <br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; the receiving bank account of this platform may change at any time. Please contact online customer service to obtain the current collection account before recharging! If you transfer to the wrong account number due to your error, we have the right not to divide the transfer for you! Please understand and support, I wish you a happy life and win many prizes!!',
	"密码登录"=>"Password login",
    "账号注册"=>"Register an account",
    "输入账号"=>"Enter the account number",
    "输入密码"=>"enter password",
    "输入验证码"=>"Enter confirmation code",
    "登 录"=>"log in",
    "免费注册"=>"free registration",
    "请输入用户名！"=>"please enter user name!",
    "用户名长度20字符内！"=>"The user name is within 20 characters!",
    "请输入密码！"=>"Please enter the password!",
    "密码长度6-20位！"=>"Password length is 6-20 digits!",
    "请输入验证码！"=>"please enter verification code!",
    "请输入4位验证码！"=>"Please enter a 4-digit verification code!",
    "免费注册"=>"free registration",
    "账号登录"=>"Account login",
    "输入账号"=>"Enter the account number",
    "请输入6~20位字母、数字或组合"=>"Please enter 6~20 letters, numbers or combinations",
    "请输入6~16位的字母、数字组合"=>"Please enter a combination of 6-16 letters and numbers",
    "输入验证码"=>"Enter confirmation code",
    "请输入4位的字母、数字的验证码"=>"Please enter a 4-digit alphanumeric verification code",
    "真实姓名（提款使用不可修改）"=>"Real name (withdrawal cannot be modified)",
    "请填写您的代理人邀请码"=>"Please fill in your agent invitation code",
    "我已年满18周岁且同意接受"=>"I am over 18 years old and agree to accept",
    "注册条款"=>"Registration Terms",
    "立即注册"=>"Sign up now",
    "请输入用户名！"=>"please enter user name!",
    "用户名格式错误！"=>"User name format error!",
    "请输入真实的姓名！"=>"Please enter a real name!",
    "姓名应该为2-5个字！"=>"The name should be 2-5 words!",
    "请输入密码！"=>"Please enter the password!",
    "密码长度6-20位！"=>"Password length is 6-20 digits!",
    "请输入验证码！"=>"please enter verification code!",
    "请输入4位验证码！"=>"Please enter a 4-digit verification code!",

];

function _lang($char){
	global $EnLib, $zh_s, $zh_t;
	
	if(LANG == 't' || LANG == 's'){
		if (LANG == 't') {
		   $s1 = $zh_s;
		   $s2 = $zh_t;
		}else if(LANG == 's') {
		   $s1 = $zh_t;
		   $s2 = $zh_s;
		}
		$a = '';
		
		$old = mb_str_split($char);
		for($i=0;$i<count($old);$i++){
			$pos = array_search($old[$i], $s1);
			if($pos > 0){
				$a .= $s2[$pos];
			}else{
				$a .= $old[$i];
			}
		}
		return $a;
	}elseif(LANG == 'e'){
		if(array_key_exists($char, $EnLib)){
			return $EnLib[$char];
		}else{
			return $char;
		}
	}
}

class base {
	/**
	 * 初始化应用程序
	 */
	public static function creat_app() {
		return self :: load_sys_class('application');
	}
	/**
	 * 加载系统类方法
	 *
	 * @param string $classname 类名
	 * @param string $path 扩展地址
	 * @param intger $initialize 是否初始化
	 */
	public static function load_sys_class($classname, $path = '', $initialize = 1) {
		return self :: _load_class($classname, $path, $initialize);
	}

	/**
	 * 加载应用类方法
	 *
	 * @param string $classname 类名
	 * @param string $m 模块
	 * @param intger $initialize 是否初始化
	 */
	public static function load_app_class($classname, $m = '', $initialize = 1) {
		$m = empty($m) && defined('ROUTE_M') ? ROUTE_M : $m;
		if (empty($m)) return false;
		return self :: _load_class($classname, 'modules' . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR . 'classes', $initialize);
	}

	/**
	 * 加载数据模型
	 *
	 * @param string $classname 类名
	 */
	public static function load_model($classname) {
		return self :: _load_class($classname, 'model');
	}

	/**
	 * 加载类文件函数
	 *
	 * @param string $classname 类名
	 * @param string $path 扩展地址
	 * @param intger $initialize 是否初始化
	 */
	private static function _load_class($classname, $path = '', $initialize = 1) {
		static $classes = array();
		if (empty($path)) $path = 'libs' . DIRECTORY_SEPARATOR . 'classes';
		$key = md5($path . $classname);
		if (isset($classes[$key])) {
			if (!empty($classes[$key])) {
				return $classes[$key];
			} else {
				return true;
			}
		}
		if (file_exists(FILE_PATH . $path . DIRECTORY_SEPARATOR . $classname . '.class.php')) {
			include FILE_PATH . $path . DIRECTORY_SEPARATOR . $classname . '.class.php';
			$name = $classname;
			if ($my_path = self :: my_path(FILE_PATH . $path . DIRECTORY_SEPARATOR . $classname . '.class.php')) {
				include $my_path;
				$name = 'MY_' . $classname;
			}
			if ($initialize) {
				$classes[$key] = new $name;
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}

	/**
	 * 加载系统的函数库
	 *
	 * @param string $func 函数库名
	 */
	public static function load_sys_func($func) {
		return self :: _load_func($func);
	}

	/**
	 * 加载应用函数库
	 *
	 * @param string $func 函数库名
	 * @param string $m 模型名
	 */
	public static function load_app_func($func, $m = '') {
		$m = empty($m) && defined('ROUTE_M') ? ROUTE_M : $m;
		if (empty($m)) return false;
		return self :: _load_func($func, 'modules' . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR . 'functions');
	}

	/**
	 * 加载函数库
	 *
	 * @param string $func 函数名
	 * @param string $path 地址
	 */
	private static function _load_func($func, $path = '') {
		static $funcs = array();
		if (empty($path)) $path = 'libs' . DIRECTORY_SEPARATOR . 'functions';
		$path .= DIRECTORY_SEPARATOR . $func . '.func.php';
		$key = md5($path);
		if (isset($funcs[$key])) return true;
		if (file_exists(FILE_PATH . $path)) {
			include FILE_PATH . $path;
		} else {
			$funcs[$key] = false;
			return false;
		}
		$funcs[$key] = true;
		return true;
	}

	/**
	 * 是否有自己的扩展文件
	 *
	 * @param string $filepath 路径
	 */
	public static function my_path($filepath) {
		$path = pathinfo($filepath);
		if (file_exists($path['dirname'] . DIRECTORY_SEPARATOR . 'MY_' . $path['basename'])) {
			return $path['dirname'] . DIRECTORY_SEPARATOR . 'MY_' . $path['basename'];
		} else {
			return false;
		}
	}

	/**
	 * 加载配置文件
	 *
	 * @param string $file 配置文件
	 * @param string $key 要获取的配置荐
	 * @param string $default 默认配置。当获取配置项目失败时该值发生作用。
	 * @param boolean $reload 强制重新加载。
	 */
	public static function load_config($file, $key = '', $default = '', $reload = false) {
		static $configs = array();
		if (!$reload && isset($configs[$file])) {
			if (empty($key)) {
				return $configs[$file];
			} elseif (isset($configs[$file][$key])) {
				return $configs[$file][$key];
			} else {
				return $default;
			}
		}

		$path = MYFILE_PATH . 'configs' . DIRECTORY_SEPARATOR . $file . '.php';
		if (file_exists($path)) {
			$configs[$file] = include $path;
		}
		if (empty($key)) {
			return $configs[$file];
		} elseif (isset($configs[$file][$key])) {
			return $configs[$file][$key];
		} else {
			return $default;
		}
	}

	public static function substr_cut($user_name) {
		$strlen     = mb_strlen($user_name, 'utf-8');
	    $firstStr     = mb_substr($user_name, 0, 1, 'utf-8');
	    $lastStr     = mb_substr($user_name, -1, 1, 'utf-8');
	    return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
	}
}
