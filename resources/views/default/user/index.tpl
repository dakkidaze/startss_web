{include file='user/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            用戶中心
            <small>User Center</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- START PROGRESS BARS -->
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-bullhorn"></i>

                        <h3 class="box-title">公告&FAQ</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    	<table>
                    		<tr><th>日期</th><th>內容</th></tr>
	                    	{foreach $announces as $announce}
	                    	<tr>
	                    		<td style="white-space: nowrap;"><strong>{$announce->createDate()}</strong></td>
	                    		<td>{$announce->content}</td>
	                    	</tr>
	                    	{/foreach}
                    	</table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col (right) -->

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-exchange"></i>

                        <h3 class="box-title">帳戶使用情況</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="progress progress-striped">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40"
                                         aria-valuemin="0" aria-valuemax="100"
                                         style="width: {$user->trafficUsagePercent()}%">
                                        <span class="sr-only">Transfer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <dl class="dl-horizontal">
                            <dt>總流量</dt>
                            <dd>{$user->enableTraffic()}</dd>
                            <dt>已用流量</dt>
                            <dd>{$user->usedTraffic()} ({$user->trafficRatio()} %)</dd>
                            <dt>剩餘流量</dt>
                            <dd>{$user->unusedTraffic()}</dd>
                            {if $user->credit > 0}
                            <dt>剩餘結餘</dt>
                            <dd>{$user->credit()}
                            {/if}

                                    <div>
                            		{if $user->IsAdmin()}
                            		<a class="btn btn-info btn-sm" href="/user/addcredit">充值</a>
                            		{/if}

                            		<a class="btn btn-info btn-sm" href="/user/resetflow">重置已用流量</a>

            					</div>
        					</dd>

                        </dl>

                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col (left) -->

            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-pencil"></i>

                        <h3 class="box-title">簽到獲取流量</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
						<p> 每天可以簽到一次。</p>
                        <p>上次簽到時間：<code>{$user->lastCheckInTime()}</code></p>
                        {if $user->isAbleToCheckin() }
                            <p id="checkin-btn">
                                <button id="checkin" class="btn btn-success  btn-flat">簽到</button>
                            </p>
                        {else}
                            <p><a class="btn btn-success btn-flat disabled" href="#">不能簽到</a></p>
                        {/if}
                        <p id="checkin-msg"></p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-money"></i>

                        <h3 class="box-title">抽流量</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
						<p> 今天可以抽<b id="draw-remain">{$user->drawChance()}</b>次。</p>
                        <p>上次抽流量時間：<code>{$user->lastDrawTime()}</code></p>
                        {if $user->isAbleToDraw() }
                            <p id="draw-btn">
                                <button id="draw" class="btn btn-success  btn-flat">抽流量(每次扣500MB)</button>
                            </p>
                        {else}
                            <p><a class="btn btn-success btn-flat disabled" href="#">抽流量機會用完</a></p>
                        {/if}
                        <p id="draw-msg"></p>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col (right) -->

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa  fa-paper-plane"></i>

                        <h3 class="box-title">連接信息</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>端口</dt>
                            <dd>{$user->port}</dd>
                            <dt>密碼</dt>
                            <dd>{$user->passwd}</dd>
                            <!--
                            <dt>加密方式</dt>
                            <dd>{$user->method}</dd>
                            -->
                            <dt>上次使用</dt>
                            <dd>{$user->lastSsTime()}</dd>
                        </dl>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col (right) -->
        </div>
        <!-- /.row --><!-- END PROGRESS BARS -->
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        $("#checkin").click(function () {
            $.ajax({
                type: "POST",
                url: "/user/checkin",
                dataType: "json",
                success: function (data) {
                    $("#checkin-msg").html(data.msg);
                    $("#checkin-btn").hide();
                },
                error: function (jqXHR) {
                    alert("發生錯誤：" + jqXHR.status);
                }
            })
        })
        $("#draw").click(function () {
            $.ajax({
                type: "POST",
                url: "/user/draw",
                dataType: "json",
                success: function (data) {
                    $("#draw-msg").html(data.msg);
                    console.log(data.msg);
                    $("#draw-remain").html(data.remain);
                    if(data.remain <= 0){
                        $("#draw-btn").hide();
                    }  
                },
                error: function (jqXHR) {
                    alert("發生錯誤：" + jqXHR.status);
                }
            })
        })
    })
</script>


{include file='user/footer.tpl'}
