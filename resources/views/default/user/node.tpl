{include file='user/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            節點列表
            <small>Node List</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- START PROGRESS BARS -->
        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-warning">
                    <h4>注意!</h4>
                    <p>請勿在任何地方公開節點地址！</p>
                    <p>流量比例為1.5及使用1000MB按照1500MB流量記錄記錄結算.</p>
                    <p>流量比例為1.8及使用1000MB按照1800MB流量記錄記錄結算.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header">
                        <i class="fa fa-th-list"></i>

                        <h3 class="box-title">節點</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        {foreach $nodes as $node}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="info-box">
                                        <a href="./node/{$node->id}"><span class="info-box-icon bg-aqua"><i class="fa fa-server"></i></span></a>

                                        <div class="info-box-content">
                                            <div class=row>
                                                <div class="col-sm-6">
                                                    <div class="info-box-number">
                                                        <a href="./node/{$node->id}">{$node->name}</a> <sub><span
                                                                    class="label label-success">{$node->status}</span></sub>
                                                    </div>

                                                    <div class="info-box-text row">
                                                        <div class="col-xs-4 col-sm-2">地址：</div>
                                                        <div class="col-xs-8 col-sm-4"><span
                                                                    class="label label-danger">{$node->server}</span>
                                                        </div>
                                                        <div class="col-xs-4 col-sm-2">端口：</div>
                                                        <div class="col-xs-8 col-sm-4"><span
                                                                    class="label label-danger">{$user->port}</span>
                                                        </div>
                                                        <div class="col-xs-4 col-sm-2">密碼：</div>
                                                        <div class="col-xs-8 col-sm-4"><span
                                                                    class="label label-danger" style="text-transform: none;">{$user->passwd}</span>
                                                        </div>
                                                        <div class="col-xs-4 col-sm-2">加密：</div>
                                                        <div class="col-xs-8 col-sm-4">
                                                        <span class="label label-danger">
                                                            {if $node->custom_method == 1}
                                                                {$user->method}
                                                            {else}
                                                                {$node->method}
                                                            {/if}
                                                        </span>

                                                        </div>
                                                        <div class="col-xs-4 col-sm-2">流量比例：</div>
                                                        <div class="col-xs-8 col-sm-4"><span
                                                                    class="label label-danger">{$node->traffic_rate}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="progress-description">{$node->info}</div>
                                                </div>
                                            </div>


                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                            </div>
                        {/foreach}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col (left) -->
        </div>
        <!-- /.row --><!-- END PROGRESS BARS -->
    </section>
    <!-- /.content -->
</div><!-- /.content-wrapper -->


{include file='user/footer.tpl'}