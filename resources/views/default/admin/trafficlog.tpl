{include file='admin/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            流量使用記錄
            <small>Traffic Log</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-warning">
                    <h4>注意!</h4>
                    <p>部分節點不支持流量記錄.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        {$logs->render()}
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>使用節點</th>
                                <th>倍率</th>
                                <th>實際使用流量</th>
                                <th>結算流量</th>
                                <th>記錄時間</th>
                                <th>用戶</th>
                            </tr>
                            {foreach $logs as $log}
                                <tr>
                                    <td>#{$log->id}</td>
                                    <td>{$log->node()->name}</td>
                                    <td>{$log->rate}</td>
                                    <td>{$log->totalUsed()}</td>
                                    <td>{$log->traffic()}</td>
                                    <td>{$log->logTime()}</td>
                                    <td>{$log->user_id()}</td>
                                </tr>
                            {/foreach}
                        </table>
                        {$logs->render()}
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

{include file='admin/footer.tpl'}