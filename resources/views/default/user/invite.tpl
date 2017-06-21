{include file='user/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            邀請
            <small>Invite</small>
        </h1>
    </section>

    <!-- Main content --><!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div id="msg-error" class="alert alert-warning alert-dismissable" style="display:none">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> 出錯了!</h4>

                    <p id="msg-error-p"></p>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-rocket"></i>

                        <h3 class="box-title">邀請</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <p>當前您可以生成<code>{$user->invite_num}</code>個邀請碼。 </p>
                        {if $user->invite_num }
                            <button id="invite" class="btn btn-sm btn-info">生成我的邀請碼</button>
                        {/if}
                    </div>
                    <!-- /.box -->
                    <div class="box-header">
                        <h3 class="box-title">我的邀請碼</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>###</th>
                                <th>邀請碼(點右鍵複製鏈接)</th>
                                <th>狀態</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $codes as $code}
                                <tr>
                                    <td><b>{$code->id}</b></td>
                                    
                                    <td><div class="captcha"><strong>{$code->code}</strong></div>
                                    
                                    </td>
                                    <td>可用</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="callout callout-warning">
                    <h4>注意！</h4>

                    <p>邀請碼請給認識的需要的人。</p>

                    <p>邀請有記錄，若被邀請的人違反用戶協議，您將會有連帶責任。</p>
                </div>

                <div class="callout callout-info">
                    <h4>說明</h4>

                    <p>用戶註冊48小時後，才可以生成邀請碼。</p>

                    <p>邀請碼暫時無法購買，請珍惜。</p>

                    <p>公共頁面不定期發放邀請碼，如果用完邀請碼可以關注公共邀請。</p>
                </div>
            </div>
            <!-- /.col (right) -->
        </div>
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        $("#invite").click(function () {
            $.ajax({
                type: "POST",
                url: "/user/invite",
                dataType: "json",
                success: function (data) {
                    window.location.reload();
                },
                error: function (jqXHR) {
                    alert("發生錯誤：" + jqXHR.status);
                }
            })
        })
    })
</script>

{include file='user/footer.tpl'}