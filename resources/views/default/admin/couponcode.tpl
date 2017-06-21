{include file='admin/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            	重置碼
            <small>Reset Code</small>
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
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">添加重置碼</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">

                        <div class="form-horizontal">

                            <div class="form-group">
                                <label for="cate_title" class="col-sm-3 control-label">重置碼前缀</label>

                                <div class="col-sm-9">
                                    <input class="form-control" id="prefix" placeholder="小于8个字符">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cate_title" class="col-sm-3 control-label">用户端口</label>

                                <div class="col-sm-9">
                                    <input class="form-control" id="port" type="number" placeholder="用户端">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cate_title" class="col-sm-3 control-label">重置碼数量</label>

                                <div class="col-sm-9">
                                    <input class="form-control" id="num" type="number" placeholder="要生成的重置碼数量">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button id="invite" type="submit" name="action" value="add" class="btn btn-primary">生成</button>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">注意</h3>
                    </div>
                    <div class="box-footer">
                        <p>所有重置碼。</p>
                        <section class="content">
					        <div class="row">
					            <div class="col-xs-12">
					                <div class="box">
					                    <div class="box-body table-responsive no-padding">
					                        
					                        <table class="table table-hover">
					                            <tr>
					                                <th>重置碼</th>
					                                <th>生成日</th>
					                                <th>到期日</th>
					                                <th>用户ID</th>
					                                <th>管理用户ID</th>
					                                <th>狀態</th>
					                                <th>操作</th>
					                            </tr>
					                            {foreach $codes as $code}
					                            
					                            
					                            <tr>
					                                <td>{$code->code}</td>
					                                <td>{$code->createDate()}</td>
					                                <td>{$code->expireDate()}</td>
					                                <td>{$code->createuser}</td>
					                                <td>{$code->userid}</td>
					                                <td>{$code->status()}</td>
					                                <td></td>
					                            </tr>
					                            
					                            {/foreach}
					                        </table>
					                        
					                    </div><!-- /.box-body -->
					                </div><!-- /.box -->
					            </div>
					        </div>
					
					    </section><!-- /.content -->
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        $("#invite").click(function () {
            $.ajax({
                type: "POST",
                url: "/admin/couponcode",
                dataType: "json",
                data: {
                    prefix: $("#prefix").val(),
                    port: $("#port").val(),
                    num: $("#num").val()
                },
                success: function (data) {
                    if (data.ret) {
                        $("#msg-success").show(100);
                        $("#msg-success-p").html(data.msg);
                        //window.setTimeout("location.href='/admin/invite'", 2000);
                    }
                    // window.location.reload();
                },
                error: function (jqXHR) {
                    alert("发生错误：" + jqXHR.status);
                }
            })
        })
    })
</script>

{include file='admin/footer.tpl'}