{include file='header.tpl'}
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
        <br><br>
        <div class="row center">
            <h5>邀請碼實時刷新</h5>
            <h5>如遇到無邀請碼請找已經註冊的用戶獲取。</h5>
        </div>
    </div>
</div>

<div class="container">
    <div class="section">
        <!--   Icon Section   -->
        <div class="row">
            <div class="row marketing">
                <h2 class="sub-header">邀請碼</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>###</th>
                            <th>邀請碼 (點擊邀請碼進入註冊頁面)</th>
                            <th>狀態</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach $codes as $code}
                        <tr>
                            <td>{$code->id}</td>
                            <td><a href="/auth/register?code={$code->code}">{$code->code}</a></td>
                            <td>可用</td>
                        </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>
{include file='footer.tpl'}