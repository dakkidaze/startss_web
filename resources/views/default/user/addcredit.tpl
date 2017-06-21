{include file='user/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            充值
            <small>Add Credit</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- <div class="col-md-12">
                <div class="col-xs-12">
                <div id="msg-error" class="alert alert-warning alert-dismissable" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> 出錯了!</h4>

                    <p id="msg-error-p"></p>
                </div>
                <div id="msg-success" class="alert alert-success alert-dismissable" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-info"></i> 充值成功!</h4>

                    <p id="msg-success-p"></p>
                </div>
            </div>
            </div> -->
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    
                    <div class="box-body table-responsive no-padding">
                       <div class="box box-primary">
		                    <div class="box-header">
		                        <i class="fa fa-shopping-cart"></i>
		                        <h3 class="box-title">充值詳情</h3>
		                    </div>
	                    <form method="post" name="form" action="https://www.sandbox.paypal.com/cgi-bin/webscr">
	                    	<input type="hidden" name="business" value="windshadow.lam@gmail.com" />
	                    	<input type="hidden" value="1" name="item_number" />
	                    	<input type="hidden" value="http://acgapp.moe/alantest/success.php" name="return">
	                    	<input type="hidden" name="currency_code" value="HKD">
		                    <div class="box-body">
		                    	<dl class="dl-horizontal">
		                            <dt>現時結餘</dt>
		                            <dd></dd>
		                            <dt>充值</dt>
		                            <dd><select name="item_name" id="item_name" onfocus="this.selectedIndex=0;" onchange="itemAmountVals();">
		                            	<option value="-1">請選擇</option>
		                            	<option value="5">ACG 50點 $5.00 HKD</option>
										<option value="9.5">ACG 100點 $9.50 HKD</option>
										<option value="45">ACG 500點 $45.00 HKD</option>
										<option value="0.01">測試 $0.01 HKD</option>
									</select></dd>
									<dt>支付</dt>
		                            <dd><input type="hidden" name="amount" id="amount" value="-1" /><label id="amount_label" name="amount_label" /></dd>
		                            <dt>購買後結餘</dt>
		                            <dd></dd>
		                        </dl>
		                    </div>
		                	<div class="box-footer">
		                    	<button type="submit" id="confirm-buy" class="btn btn-primary">購買</button>
		                	</div>
	                	</form>
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
		// console.log($( "#item_name option:selected" ).text());
		// console.log($( "#item_name option:selected" ).val());
		// console.log($( "#amount" ).val());
		$( "#amount" ).val($( "#item_name option:selected" ).val());
		$( "#amount_label" ).text(""+$( "#amount" ).val());
		// console.log($( "#amount" ).val());
	}
</script>

{include file='user/footer.tpl'}