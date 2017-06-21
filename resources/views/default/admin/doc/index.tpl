{include file='admin/main.tpl'}

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            公告列表
            <small>Announcement List</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <p> <a class="btn btn-success btn-sm" href="/admin/doc/create">添加</a> </p>
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>ID</th>
                                <th>描述</th>
                                <th>日期</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            {foreach $documents as $doc}
                            <tr>
                                <td>#{$doc->id}</td>
                                <td>{$doc->content}</td>
                                <td>{$doc->datetime}</td>
                                <td>{$doc->sort_order}</td>
                                <td>
                                    <a class="btn btn-danger btn-sm" id="delete" value="{$doc->id}" href="/admin/doc/{$doc->id}/delete">删除</a>
                                </td>
                            </tr>
                            {/foreach}
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->


<script>
    $(document).ready(function(){
        
    })
</script>

{include file='admin/footer.tpl'}