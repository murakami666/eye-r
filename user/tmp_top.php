<div id="bg">
	<header>
		<div id="header_in">
			<div id="header_logo">
				<div id="header_logo">
					<a href="" id="home"><img src="<?= BASE_URL?>img/header_logo.png?t=<?= filemtime(BASE_ROOT."img/header_logo.png");?>"></a>
					<a href="<?= BASE_URL?>user/my.php" class="hbt">TOPへ戻る</a>
				</div>
			</div>
			<div id="prof">
				<p class="img"><img src="<?= $user_data['user_line_img']?>"></p>
				<p class="name"><?= $user_data['user_name']?></p>
			</div>
		</div>
	</header>
