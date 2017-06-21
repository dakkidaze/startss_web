<footer class="page-footer green">
	<div class="container">
		<div class="row">
			<div class="col l6 s12">
				<h5 class="white-text">關於</h5>
				<p class="grey-text text-lighten-4">以愛衝破圍牆.</p>


			</div>
			<div class="col l3 s12">
				<h5 class="white-text">用戶</h5>
				<ul>{if $user->isLogin}
					<li><a class="white-text" href="/user">用戶中心</a></li>
					{else}
					<li><a class="white-text" href="/auth/login">登錄</a></li>
					<li><a class="white-text" href="/auth/register">註冊</a></li>
					{/if}
				</ul>
			</div>
			<div class="col l3 s12">
				<h5 class="white-text">更多</h5>
				<ul>
					<li><a class="white-text" href="/code">邀請碼</a></li>
					<li><a class="white-text" href="/tos">TOS</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container">
			&copy; {$config["appName"]}
		 
		</div>
	</div>
</footer>


<!--  Scripts-->
<script src="/assets/public/js/jquery.min.js"></script>
<script src="/assets/materialize/js/materialize.min.js"></script>
<script src="/assets/materialize/js/init.js"></script>

</body>
</html>