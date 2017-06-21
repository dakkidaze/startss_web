{include file='user/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            流量重置
            <small>Bandwidth Reset</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div id="msg-success" class="alert alert-info alert-dismissable" style="display: none;">
                    <button type="button" class="close" id="ok-close" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> 成功!</h4>

                    <p id="msg-success-p"></p>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-body table-responsive no-padding">
                       <div class="box box-primary">
		                    <div class="box-header">
		                        <i class="fa fa-retweet"></i>
		                        <h3 class="box-title">重置詳情</h3>
		                    </div>
	                    <div>
	                    	{if sizeof($coupons) > 0}
		                    <div class="box-body">
		                    	<dl class="dl-horizontal">
		                    		
		                    		<dt>可用重置碼</dt>
		                    		 <dd>
		                    		 	<select name="item_name" id="item_name" onfocus="this.selectedIndex=0;" onchange="itemAmountVals();">
				                            {foreach $coupons as $code}
				                          	<option value="{$code->code}">{$code->code} (到期日:{$code->expiredate})</option>
				                            
		                          			{/foreach}
		                          		</select>
		                          </dd>
		                          
		                        </dl>
		                    </div>
		                	<div class="box-footer">
		                		<a href="javascript:doReset();" class="btn btn-info btn-sm" id="search">重置流量</a>
		                    	
		                	</div>
		                	{else}
		                	<div class="box-body">
		                    	<dl class="dl-horizontal">
		                    		
		                    		<dt>没有可用重置碼</dt>
		                    		</dl>
		                    </div>
		                	
		                	{/if}
	                	</div>
                    </div><!-- /.box-body -->
                   
                </div><!-- /.box -->
            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $("#msg-success").hide();
    $("#msg-error").hide();
    $("#ss-msg-success").hide();
</script>
<script>
	function itemAmountVals() {
		 console.log($( "#item_name option:selected" ).text());
		 console.log($( "#item_name option:selected" ).val());
		
		$( "#amount" ).val($( "#item_name option:selected" ).val());
		$( "#amount_label" ).text(""+$( "#amount" ).val());
		
	}
	function doReset()
	{
		$.ajax({
                type: "POST",
                url: "/user/resetflow/"+$( "#item_name option:selected" ).val(),
                dataType: "json",
                success: function (data) {
                   if (data.ret) {
                        $("#msg-success").show(100);
                        $("#msg-success-p").html(data.msg);
                        window.setTimeout("location.href='/user'", 2000);
                    }
                },
                error: function (jqXHR) {
                    alert("發生錯誤：" + jqXHR.status);
                }
            })
	}
</script>

{include file='user/footer.tpl'}