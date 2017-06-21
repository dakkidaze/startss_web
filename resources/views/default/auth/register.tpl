{include file='auth/header.tpl'}
<body class="register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="../"><b>{$config['appName']}</b></a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">註冊，然後變成一隻貓。</p>

        <div class="form-group has-feedback">
            <input type="text" id="name" class="form-control" placeholder="暱稱"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="text" id="email" class="form-control" placeholder="郵箱"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" id="passwd" class="form-control" placeholder="密碼"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" id="repasswd" class="form-control" placeholder="重復密碼"/>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="text" id="code" value="{$code}" class="form-control" placeholder="邀請碼"/>
            <span class="glyphicon glyphicon-send form-control-feedback"></span>
        </div>


        <div class="form-group has-feedback">
            <p>註冊即代表同意<a href="/tos">服務條款</a></p>
        </div>

        <div class="form-group has-feedback">
            <button type="submit" id="reg" class="btn btn-primary btn-block btn-flat">同意服務條款並提交註冊</button>
        </div>

        <div id="msg-success" class="alert alert-info alert-dismissable" style="display: none;">
            <button type="button" class="close" id="ok-close" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-info"></i> 成功!</h4>
            <p id="msg-success-p"></p>
        </div>

        <div id="msg-error" class="alert alert-warning alert-dismissable" style="display: none;">
            <button type="button" class="close" id="error-close" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> 出錯了!</h4>
            <p id="msg-error-p"></p>
        </div>

        <a href="/auth/login" class="text-center">已經註冊？請登錄</a>
    </div><!-- /.form-box -->
</div><!-- /.register-box -->

<!-- jQuery 2.1.3 -->
<script src="/assets/public/js/jquery.min.js"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="/assets/public/js/bootstrap.min.js" type="text/javascript"></script>
<!-- iCheck -->
<script src="/assets/public/js/icheck.min.js" type="text/javascript"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
        // $("#msg-error").hide(100);
        // $("#msg-success").hide(100);

    });
</script>
<script>
    $(document).ready(function(){
        function register(){
            $.ajax({
                type:"POST",
                url:"/auth/register",
                dataType:"json",
                data:{
                    email: $("#email").val(),
                    name: $("#name").val(),
                    passwd: $("#passwd").val(),
                    repasswd: $("#repasswd").val(),
                    code: $("#code").val(),
                    agree: $("#agree").val()
                },
                success:function(data){
                    if(data.ret == 1){
                        $("#msg-error").hide(10);
                        $("#msg-success").show(100);
                        $("#msg-success-p").html(data.msg);
                        window.setTimeout("location.href='/auth/login'", 2000);
                    }else{
                        $("#msg-success").hide(10);
                        $("#msg-error").show(100);
                        $("#msg-error-p").html(data.msg);
                        $("#reg").prop('disabled', false);
                    }
                },
                error:function(jqXHR){
                    $("#msg-error").hide(10);
                    $("#msg-error").show(100);
                    $("#msg-error-p").html("發生錯誤："+jqXHR.status);
                    $("#reg").prop('disabled', false);
                }
            });
        }
        $("html").keydown(function(event){
            if(event.keyCode==13){
                register();
            }
        });
        $("#reg").click(function(){
        	$("#reg").prop('disabled', true); 
            register();
        });
        $("#ok-close").click(function(){
            $("#msg-success").hide(100);
        });
        $("#error-close").click(function(){
            $("#msg-error").hide(100);
        });
    })
</script>
</body>
</html>
