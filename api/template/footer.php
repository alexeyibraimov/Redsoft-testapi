	</section>
    	</div>
    	</div>
	<footer>
		<div>
			<p><small>&copy; Алексей Ибраимов 2020</small></p>
		</div>
	</footer>
</div>
<script src="https://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
/*мобильное меню*/	
	$('.dat-menu-button').click(function(e){
		$('#main-menu').toggleClass('active');
		if($('#main-menu').hasClass('active')){
			$('#main-menu .dat-menu-button i').removeClass('fa-bars').addClass('fa-window-close');
		}else{
			$('#main-menu .dat-menu-button i').removeClass('fa-window-close').addClass('fa-bars');
		}
		return false;
	});
});
</script>
</body>
</html>